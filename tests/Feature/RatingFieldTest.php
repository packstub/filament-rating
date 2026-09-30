<?php

use Packstub\FilamentRating\Components\Rating;

it('renders in a standalone Livewire form the way the TQM feedback form uses it', function () {
    ratingForm(fn () => [
        Rating::make('satisfaction')->required(true)->default(5)->size('xl'),
    ])
        ->assertSet('data.satisfaction', 5)
        ->assertSeeHtml('fi-rating-size-xl')
        ->assertSeeHtml('role="radiogroup"')
        ->assertSeeHtml('aria-required="true"')
        ->assertSeeHtml('x-load-src=')
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertSet('saved', ['satisfaction' => 5]);
});

it('renders one radio per star with an accessible name and the current value checked', function () {
    $html = ratingForm(fn () => [Rating::make('rating')->default(3)])->html();

    expect(substr_count($html, 'role="radio"'))->toBe(5)
        ->and($html)->toContain('aria-label="3 of 5 stars"')
        ->and(substr_count($html, 'aria-checked="true"'))->toBe(1)
        ->and(substr_count($html, 'tabindex="0"'))->toBe(1)
        ->and($html)->toMatch('/aria-checked="true"[^>]*tabindex="0"/')
        ->and($html)->toContain('--fi-rating-fill: 100%')
        ->and($html)->toContain('--fi-rating-fill: 0%');
});

it('sets and clears the state', function () {
    ratingForm(fn () => [Rating::make('rating')])
        ->assertSet('data.rating', null)
        ->set('data.rating', 4)
        ->call('save')
        ->assertSet('saved', ['rating' => 4])
        ->set('data.rating', null)
        ->call('save')
        ->assertSet('saved', ['rating' => null]);
});

it('casts string states from the browser to integers', function () {
    ratingForm(fn () => [Rating::make('rating')])
        ->set('data.rating', '4')
        ->call('save')
        ->assertSet('saved', ['rating' => 4]);
});

it('validates required', function () {
    ratingForm(fn () => [Rating::make('rating')->required()])
        ->call('save')
        ->assertHasFormErrors(['rating' => 'required']);
});

it('validates the range against the number of stars', function (int|float|string $value, ?string $error) {
    $form = ratingForm(fn () => [Rating::make('rating')->stars(5)])
        ->set('data.rating', $value)
        ->call('save');

    $error === null
        ? $form->assertHasNoFormErrors()
        : $form->assertHasFormErrors(['rating' => $error]);
})->with([
    'one' => [1, null],
    'five' => [5, null],
    'zero' => [0, 'min'],
    'six' => [6, 'max'],
    'half' => [3.5, 'integer'],
    'text' => ['abc', 'numeric'],
]);

it('allows zero when asked', function () {
    ratingForm(fn () => [Rating::make('rating')->allowZero()])
        ->set('data.rating', 0)
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertSet('saved', ['rating' => 0])
        ->assertSeeHtml('aria-label="No stars"')
        ->assertSeeHtml('fi-rating-zero-active');
});

it('stores half values with allowHalf and renders a slider', function () {
    $form = ratingForm(fn () => [Rating::make('rating')->allowHalf()->default(3.5)])
        ->assertSeeHtml('role="slider"')
        ->assertSeeHtml('aria-valuenow="3.5"')
        ->assertSeeHtml('aria-valuetext="3.5 of 5 stars"')
        ->assertSeeHtml('--fi-rating-fill: 50%')
        ->call('save')
        ->assertSet('saved', ['rating' => 3.5]);

    $form->set('data.rating', 3.25)->call('save')->assertHasFormErrors(['rating' => 'multiple_of']);
    $form->set('data.rating', 0.5)->call('save')->assertHasNoFormErrors();
    $form->set('data.rating', 0)->call('save')->assertHasFormErrors(['rating' => 'min']);
});

it('respects the number of stars', function () {
    $html = ratingForm(fn () => [Rating::make('rating')->stars(fn () => 10)])->html();

    expect(substr_count($html, 'role="radio"'))->toBe(10)
        ->and($html)->toContain('aria-label="10 of 10 stars"');
});

it('passes the configuration to the Alpine component', function () {
    $html = ratingForm(fn () => [Rating::make('rating')->stars(7)->allowHalf()->allowZero()->clearable()])->html();

    expect(str_replace('\u0022', '"', html_entity_decode($html)))
        ->toContain('"stars":7')
        ->toContain('"step":0.5')
        ->toContain('"allowZero":true')
        ->toContain('"isClearable":true')
        ->toContain('"isInteractive":true')
        ->toContain('"valueTextTemplate":":value of 7 stars"')
        ->toContain("\$entangle('data.rating'");
});

it('uses live binding when the field is live', function () {
    // Livewire 3 and 4 spell a live entangle differently.
    expect(html_entity_decode(ratingForm(fn () => [Rating::make('rating')->live()])->html()))
        ->toMatch("/\\\$entangle\\('data\\.rating'(, true\\)|\\)\\.live)/");
});

it('shows a clear button when clearable', function () {
    ratingForm(fn () => [Rating::make('rating')->clearable()->default(2)])
        ->assertSeeHtml('fi-rating-clear')
        ->assertSeeHtml('aria-label="Clear rating"');

    ratingForm(fn () => [Rating::make('rating')->default(2)])
        ->assertDontSeeHtml('fi-rating-clear');
});

it('is not interactive when disabled or read-only', function () {
    ratingForm(fn () => [Rating::make('rating')->disabled()->clearable()])
        ->assertSeeHtml('aria-disabled="true"')
        ->assertSeeHtml('fi-disabled')
        ->assertDontSeeHtml('fi-rating-interactive')
        ->assertDontSeeHtml('fi-rating-clear');

    ratingForm(fn () => [Rating::make('rating')->readOnly()])
        ->assertSeeHtml('aria-readonly="true"')
        ->assertDontSeeHtml('fi-rating-interactive');
});

it('uses the label as the group name', function () {
    ratingForm(fn () => [Rating::make('rating')->label('How was it?')])
        ->assertSeeHtml('aria-label="How was it?"');
});

it('colors the stars without Tailwind classes', function (string|array $color, string $expected) {
    $html = ratingForm(fn () => [Rating::make('rating')->color($color)])->html();

    expect($html)->toContain($expected);
})->with([
    'registered' => ['warning', '--fi-rating-color-500:var(--warning-500)'],
    'palette name' => ['amber', '--fi-rating-color-500:oklch('],
    'hex' => ['#f59e0b', '--fi-rating-color-500:oklch('],
    'palette' => [['500' => 'rgb(1, 2, 3)'], '--fi-rating-color-500:rgb(1, 2, 3)'],
]);

it('renders custom icons', function () {
    ratingForm(fn () => [Rating::make('rating')->icon('heroicon-s-heart')->emptyIcon('heroicon-o-heart')])
        ->assertSeeHtml('fi-rating-star');
});
