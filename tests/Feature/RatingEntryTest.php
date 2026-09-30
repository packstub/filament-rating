<?php

use Packstub\FilamentRating\Entries\RatingEntry;
use Packstub\FilamentRating\Tests\Fixtures\Review;

it('renders the entry the way the TQM feedback view uses it', function () {
    $review = Review::create(['title' => 'Great', 'rating' => 4]);

    $html = reviewInfolist($review, fn () => [RatingEntry::make('rating')->label('Rating')])
        ->assertSee('Rating')
        ->html();

    expect($html)
        ->toContain('aria-label="4 of 5 stars"')
        ->and(substr_count($html, '--fi-rating-fill: 100%'))->toBe(4);
});

it('renders decimals, the value and a count', function () {
    $review = Review::create(['title' => 'Average', 'score' => 4.5]);

    expect(reviewInfolist($review, fn () => [
        RatingEntry::make('score')->stars(5)->showValue()->showCount(12)->size('lg')->color('amber'),
    ])->html())
        ->toContain('--fi-rating-fill: 50%')
        ->toContain('aria-label="4.5 of 5 stars"')
        ->toContain('>4.5</span>')
        ->toContain('(12)')
        ->toContain('fi-rating-size-lg')
        ->toContain('--fi-rating-color-500:oklch(');
});

it('clamps values above the maximum', function () {
    $review = Review::create(['title' => 'Too many', 'rating' => 9]);

    expect(reviewInfolist($review, fn () => [RatingEntry::make('rating')])->html())
        ->toContain('aria-label="5 of 5 stars"');
});

it('translates the labels', function () {
    app()->setLocale('ro');

    $review = Review::create(['title' => 'Bun', 'rating' => 4]);

    expect(reviewInfolist($review, fn () => [RatingEntry::make('rating')->stars(20)])->html())
        ->toContain('aria-label="4 din 20 de stele"');
});
