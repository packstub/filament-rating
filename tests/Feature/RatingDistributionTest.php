<?php

use Filament\Tables\Columns\TextColumn;
use Packstub\FilamentRating\Columns\RatingColumn;
use Packstub\FilamentRating\Summarizers\RatingDistribution;
use Packstub\FilamentRating\Tests\Fixtures\Review;

it('counts the records per rating from the highest down', function () {
    foreach ([5, 5, 4, 3.5, 1, null] as $score) {
        Review::create(['title' => 'Review', 'score' => $score]);
    }

    $html = reviewsTable(fn () => [
        TextColumn::make('title'),
        RatingColumn::make('score')->color('warning')->summarize(RatingDistribution::make()),
    ])->html();

    preg_match_all('/<span class="fi-rating-distribution-count">\s*(\d+)/', $html, $counts);
    preg_match_all('/style="width: ([\d.]+)%"/', $html, $widths);

    expect($counts[1])->toBe(['2', '1', '1', '0', '1'])
        ->and($widths[1])->toBe(['40', '20', '20', '0', '20'])
        ->and($html)
        ->toContain('Ratings')
        ->toContain('5 stars:')
        ->toContain('--fi-rating-color-500:var(--warning-500)');
});

it('shows percentages, a zero row and labels', function () {
    foreach ([0, 2, 2, 2] as $rating) {
        Review::create(['title' => 'Review', 'rating' => $rating]);
    }

    $html = reviewsTable(fn () => [
        TextColumn::make('title'),
        RatingColumn::make('rating')->stars(3)->allowZero()->labels([2 => 'Okay'])->summarize(RatingDistribution::make()->percentages()),
    ])->html();

    preg_match_all('/<span class="fi-rating-distribution-count">\s*([\d%]+)/', $html, $counts);

    expect($counts[1])->toBe(['0%', '75%', '0%', '25%'])
        ->and($html)
        ->toContain('title="Okay"')
        ->toContain('2 stars (Okay):');
});

it('fills each row with its own color from colors()', function () {
    Review::create(['title' => 'Review', 'score' => 5]);

    $html = reviewsTable(fn () => [
        TextColumn::make('title'),
        RatingColumn::make('score')
            ->colors([1 => 'danger', 4 => 'success'])
            ->summarize(RatingDistribution::make()),
    ])->html();

    preg_match_all('/<li\s+class="fi-rating-distribution-row"\s+style="([^"]*)"/', $html, $styles);

    expect($styles[1])->toHaveCount(5)
        ->and($styles[1][0])->toContain('--fi-rating-color-500:var(--success-500)')
        ->and($styles[1][4])->toContain('--fi-rating-color-500:var(--danger-500)');

    // The fill is resolved on the row itself, so the row's colors win over the list's.
    $css = file_get_contents(__DIR__.'/../../resources/dist/filament-rating.css');
    expect($css)->toMatch('/\.fi-rating-distribution \.fi-rating-distribution-row \{\s*--fi-rating-filled: var\(--fi-rating-color-500/');
});
