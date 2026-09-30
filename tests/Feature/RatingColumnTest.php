<?php

use Filament\Tables\Columns\TextColumn;
use Packstub\FilamentRating\Columns\RatingColumn;
use Packstub\FilamentRating\RatingTheme;
use Packstub\FilamentRating\Summarizers\RatingAverage;
use Packstub\FilamentRating\Tests\Fixtures\Review;

it('renders the column the way the TQM feedback table uses it', function () {
    $reviews = collect([
        Review::create(['title' => 'Great', 'rating' => 5]),
        Review::create(['title' => 'Fine', 'rating' => 3]),
        Review::create(['title' => 'None', 'rating' => null]),
    ]);

    reviewsTable(fn () => [RatingColumn::make('rating')->label('Rating')->sortable()->searchable()])
        ->assertCanSeeTableRecords($reviews)
        ->assertSeeHtml('aria-label="5 of 5 stars"')
        ->assertSeeHtml('aria-label="3 of 5 stars"')
        ->assertSeeHtml('aria-label="Not rated"')
        ->sortTable('rating', 'desc')
        ->assertCanSeeTableRecords($reviews->sortByDesc('rating'), inOrder: true)
        ->searchTable('5')
        ->assertCanSeeTableRecords($reviews->where('rating', 5))
        ->assertCanNotSeeTableRecords($reviews->where('rating', '!=', 5));
});

it('fills stars the same way mokhosh did for whole numbers', function () {
    Review::create(['title' => 'Three', 'rating' => 3]);

    $html = reviewsTable(fn () => [RatingColumn::make('rating')])->html();

    expect(substr_count($html, '--fi-rating-fill: 100%'))->toBe(3)
        ->and(substr_count($html, '--fi-rating-fill: 0%'))->toBe(2)
        ->and($html)->toContain('fi-rating-size-md');
});

it('fills stars partially for decimals', function () {
    Review::create(['title' => 'Average', 'rating' => 0, 'score' => 3.7]);

    $html = reviewsTable(fn () => [RatingColumn::make('score')->showValue()])->html();

    expect($html)
        ->toContain('--fi-rating-fill: 70%')
        ->toContain('aria-label="3.7 of 5 stars"')
        ->toContain('<span class="fi-rating-value" aria-hidden="true">3.7</span>');
});

it('rounds down to half stars with the half-stars theme', function () {
    Review::create(['title' => 'Average', 'score' => 3.7]);

    expect(reviewsTable(fn () => [RatingColumn::make('score')->theme(RatingTheme::HalfStars)])->html())
        ->toContain('--fi-rating-fill: 50%')
        ->toContain('aria-label="3.5 of 5 stars"');
});

it('shows the value with a precision and a count', function () {
    Review::create(['title' => 'Average', 'score' => 4]);

    expect(reviewsTable(fn () => [
        RatingColumn::make('score')->showValue()->precision(2)->showCount(fn (Review $record): int => 128),
    ])->html())
        ->toContain('<span class="fi-rating-value" aria-hidden="true">4</span>')
        ->toContain('<span class="fi-rating-count">(128)</span>');
});

it('shows the zero icon when zero is allowed', function () {
    Review::create(['title' => 'Zero', 'rating' => 0]);

    expect(reviewsTable(fn () => [RatingColumn::make('rating')->allowZero()])->html())
        ->toContain('fi-rating-zero fi-rating-zero-active');
});

it('shows the placeholder for an empty state', function () {
    Review::create(['title' => 'None']);

    expect(reviewsTable(fn () => [RatingColumn::make('rating')->placeholder('No rating yet')])->html())
        ->toContain('No rating yet')
        ->not->toContain('fi-rating-star');
});

it('adds a tooltip', function () {
    Review::create(['title' => 'Three', 'rating' => 3]);

    expect(reviewsTable(fn () => [RatingColumn::make('rating')->tooltip(fn (Review $record): string => "Rated by {$record->title}")])->html())
        ->toContain('Rated by Three');
});

it('summarizes the average as stars and inherits the column settings', function () {
    Review::create(['title' => 'A', 'rating' => 4]);
    Review::create(['title' => 'B', 'rating' => 3]);
    Review::create(['title' => 'C', 'rating' => 4]);

    // The first column holds the "Summary" heading, so the rating column goes second.
    $html = reviewsTable(fn () => [
        TextColumn::make('title'),
        RatingColumn::make('rating')->stars(10)->color('warning')->summarize(RatingAverage::make()),
    ])->html();

    expect($html)
        ->toContain('Average rating')
        ->toContain('aria-label="3.7 of 10 stars"')
        ->toContain('<span class="fi-rating-value" aria-hidden="true">3.7</span>')
        ->toContain('--fi-rating-color-500:var(--warning-500)');
});
