<?php

use Livewire\Features\SupportTesting\Testable;
use Packstub\FilamentRating\Tests\Fixtures\RatingForm;
use Packstub\FilamentRating\Tests\Fixtures\Review;
use Packstub\FilamentRating\Tests\Fixtures\ReviewInfolist;
use Packstub\FilamentRating\Tests\Fixtures\ReviewsTable;
use Packstub\FilamentRating\Tests\TestCase;

use function Pest\Livewire\livewire;

pest()->extend(TestCase::class)->in('Feature');

/**
 * @param  Closure(): array<mixed>  $components
 */
function ratingForm(Closure $components): Testable
{
    RatingForm::$components = $components;

    return livewire(RatingForm::class);
}

/**
 * @param  Closure(): array<mixed>  $columns
 */
function reviewsTable(Closure $columns): Testable
{
    ReviewsTable::$columns = $columns;

    return livewire(ReviewsTable::class);
}

/**
 * @param  Closure(): array<mixed>  $components
 */
function reviewInfolist(Review $review, Closure $components): Testable
{
    ReviewInfolist::$components = $components;

    return livewire(ReviewInfolist::class, ['review' => $review]);
}
