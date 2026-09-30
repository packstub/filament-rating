<?php

use Packstub\FilamentRating\Columns\RatingColumn;
use Packstub\FilamentRating\Filters\RatingFilter;
use Packstub\FilamentRating\Tests\Fixtures\Review;

it('keeps ratings from the chosen star and up', function () {
    $reviews = collect([1, 3, 4, 5, null])->map(fn (?int $rating) => Review::create(['title' => "Rated {$rating}", 'rating' => $rating]));

    reviewsTable(fn () => [RatingColumn::make('rating')], fn () => [RatingFilter::make('rating')])
        ->filterTable('rating', 4)
        ->assertCanSeeTableRecords($reviews->filter(fn (Review $review): bool => $review->rating >= 4))
        ->assertCanNotSeeTableRecords($reviews->filter(fn (Review $review): bool => $review->rating < 4));
});

it('matches one rating with exact(), counting half ratings toward their star', function () {
    $three = Review::create(['title' => 'Three', 'score' => 3]);
    $threeAndAHalf = Review::create(['title' => 'Three and a half', 'score' => 3.5]);
    $four = Review::create(['title' => 'Four', 'score' => 4]);

    reviewsTable(fn () => [RatingColumn::make('score')], fn () => [RatingFilter::make('score')->exact()])
        ->filterTable('score', 3)
        ->assertCanSeeTableRecords([$three, $threeAndAHalf])
        ->assertCanNotSeeTableRecords([$four]);
});

it('builds the options from the number of stars', function () {
    expect(RatingFilter::make('rating')->stars(3)->getRatingOptions())->toBe([
        3 => '3 stars',
        2 => '2 stars & up',
        1 => '1 star & up',
    ])->and(RatingFilter::make('rating')->stars(2)->exact()->getRatingOptions())->toBe([
        2 => '2 stars',
        1 => '1 star',
    ]);
});
