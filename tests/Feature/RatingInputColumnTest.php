<?php

use Packstub\FilamentRating\Columns\RatingInputColumn;
use Packstub\FilamentRating\Tests\Fixtures\Review;

it('renders interactive stars in the cell', function () {
    $review = Review::create(['title' => 'Great', 'rating' => 4]);

    $html = reviewsTable(fn () => [RatingInputColumn::make('rating')->label('Rating')])->html();

    expect(substr_count($html, 'role="radio"'))->toBe(5)
        ->and($html)
        ->toContain('role="radiogroup"')
        ->toContain('aria-label="Rating"')
        ->toContain('fi-rating-interactive')
        ->toContain('x-on:click.stop')
        ->toContain("wire:key=\"rating-column.rating.{$review->getKey()}.4.")
        ->and(str_replace('\u0022', '"', html_entity_decode($html)))
        ->toContain('"column":{"name":"rating","recordKey":"'.$review->getKey().'"}');
});

it('saves a new rating to the record', function () {
    $review = Review::create(['title' => 'Great', 'rating' => 2]);

    reviewsTable(fn () => [RatingInputColumn::make('rating')])
        ->call('updateTableColumnState', 'rating', (string) $review->getKey(), '5');

    expect($review->refresh()->rating)->toBe(5);
});

it('clears the rating', function () {
    $review = Review::create(['title' => 'Great', 'rating' => 2]);

    reviewsTable(fn () => [RatingInputColumn::make('rating')->clearable()])
        ->call('updateTableColumnState', 'rating', (string) $review->getKey(), null);

    expect($review->refresh()->rating)->toBeNull();
});

it('rejects ratings outside the stars', function (mixed $value) {
    $review = Review::create(['title' => 'Great', 'rating' => 2]);

    reviewsTable(fn () => [RatingInputColumn::make('rating')])
        ->call('updateTableColumnState', 'rating', (string) $review->getKey(), $value);

    expect($review->refresh()->rating)->toBe(2);
})->with([
    'too high' => [6],
    'zero' => [0],
    'half' => [3.5],
    'text' => ['abc'],
]);

it('saves half ratings with allowHalf', function () {
    $review = Review::create(['title' => 'Great', 'score' => 2]);

    reviewsTable(fn () => [RatingInputColumn::make('score')->allowHalf()])
        ->call('updateTableColumnState', 'score', (string) $review->getKey(), 3.5);

    expect((float) $review->refresh()->score)->toBe(3.5);
});

it('does not save when disabled', function () {
    $review = Review::create(['title' => 'Locked', 'rating' => 2]);

    $html = reviewsTable(fn () => [RatingInputColumn::make('rating')->disabled(fn (Review $record): bool => $record->title === 'Locked')])
        ->call('updateTableColumnState', 'rating', (string) $review->getKey(), 5)
        ->html();

    expect($review->refresh()->rating)->toBe(2)
        ->and($html)->toContain('aria-disabled="true"')
        ->not->toContain('fi-rating-interactive');
});

it('runs the state update hooks', function () {
    $review = Review::create(['title' => 'Great', 'rating' => 2]);

    reviewsTable(fn () => [
        RatingInputColumn::make('rating')->afterStateUpdated(fn (Review $record, $state) => $record->update(['title' => "Rated {$state}"])),
    ])->call('updateTableColumnState', 'rating', (string) $review->getKey(), 4);

    expect($review->refresh()->title)->toBe('Rated 4');
});
