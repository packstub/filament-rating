<?php

use Filament\Tables\Columns\TextColumn;
use Packstub\FilamentRating\Columns\RatingColumn;
use Packstub\FilamentRating\Components\Rating;
use Packstub\FilamentRating\Entries\RatingEntry;
use Packstub\FilamentRating\Summarizers\RatingAverage;
use Packstub\FilamentRating\Tests\Fixtures\Review;

$labels = [1 => 'Poor', 2 => 'Fair', 3 => 'Good', 4 => 'Very good', 5 => 'Excellent'];

it('names each star with its label in the field', function () use ($labels) {
    $html = ratingForm(fn () => [Rating::make('rating')->labels($labels)->default(4)])->html();

    expect($html)
        ->toContain('aria-label="Poor, 1 of 5 stars"')
        ->toContain('aria-label="Excellent, 5 of 5 stars"')
        ->toMatch('/<span class="fi-rating-label" aria-hidden="true" x-text="label\(\)">\s*Very good\s*<\/span>/')
        ->and(str_replace('\u0022', '"', html_entity_decode($html)))
        ->toContain('"labels":{"1":"Poor","2":"Fair","3":"Good","4":"Very good","5":"Excellent"}')
        ->toContain('"labelledTemplate":":label, :value"');
});

it('labels half values in the slider', function () {
    ratingForm(fn () => [Rating::make('rating')->allowHalf()->labels(['3.5' => 'Pretty good'])->default(3.5)])
        ->assertSeeHtml('aria-valuetext="Pretty good, 3.5 of 5 stars"');
});

it('leaves out the label caption without labels', function () {
    ratingForm(fn () => [Rating::make('rating')->default(4)])
        ->assertDontSeeHtml('fi-rating-label');
});

it('shows the label of the nearest rating in displays', function () use ($labels) {
    Review::create(['title' => 'Average', 'score' => 3.7]);

    expect(reviewsTable(fn () => [RatingColumn::make('score')->labels($labels)->showLabel()])->html())
        ->toContain('aria-label="Very good, 3.7 of 5 stars"')
        ->toContain('<span class="fi-rating-label" aria-hidden="true">Very good</span>');

    expect(reviewInfolist(Review::first(), fn () => [RatingEntry::make('score')->labels($labels)])->html())
        ->toContain('aria-label="Very good, 3.7 of 5 stars"')
        ->not->toContain('fi-rating-label');
});

it('colors displays by value', function (int $rating, string $color) {
    Review::create(['title' => 'Rated', 'rating' => $rating]);

    expect(reviewsTable(fn () => [
        RatingColumn::make('rating')->color('gray')->colors([2 => 'danger', 3 => 'warning', 4 => 'success']),
    ])->html())->toContain("--fi-rating-color-500:var(--{$color}-500)");
})->with([
    'below the lowest threshold' => [1, 'gray'],
    'on a threshold' => [3, 'warning'],
    'above the highest threshold' => [5, 'success'],
]);

it('passes the color thresholds to the field so the color follows the hover', function () {
    $html = ratingForm(fn () => [Rating::make('rating')->colors([1 => 'danger', 4 => 'success'])->default(4)])->html();

    expect($html)
        ->toContain('--fi-rating-color-500:var(--success-500)')
        ->toContain('x-bind:style="colorVariables()"')
        ->and(str_replace(['\u0022', '\/'], ['"', '/'], html_entity_decode($html)))
        ->toContain('"colorThresholds":[[1,{"--fi-rating-color-300":"var(--danger-300)"')
        ->toContain('"baseColor":{"--fi-rating-color-300":"var(--primary-300)"');
});

it('does not bind the style without color thresholds', function () {
    ratingForm(fn () => [Rating::make('rating')])
        ->assertDontSeeHtml('colorVariables()');
});

it('inherits labels and color thresholds in the average summarizer', function () use ($labels) {
    Review::create(['title' => 'A', 'rating' => 5]);
    Review::create(['title' => 'B', 'rating' => 4]);

    expect(reviewsTable(fn () => [
        TextColumn::make('title'),
        RatingColumn::make('rating')->labels($labels)->colors([4 => 'success'])->summarize(RatingAverage::make()->showLabel()),
    ])->html())
        ->toContain('aria-label="Excellent, 4.5 of 5 stars"')
        ->toContain('<span class="fi-rating-label" aria-hidden="true">Excellent</span>')
        ->toContain('--fi-rating-color-500:var(--success-500)');
});
