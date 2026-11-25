<?php

use Livewire\Features\SupportTesting\Testable;
use Packstub\Rating\Tests\Fixtures\RatingForm;
use Packstub\Rating\Tests\Fixtures\Review;
use Packstub\Rating\Tests\Fixtures\ReviewInfolist;
use Packstub\Rating\Tests\Fixtures\ReviewsTable;
use Packstub\Rating\Tests\TestCase;

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
 * @param  (Closure(): array<mixed>)|null  $filters
 */
function reviewsTable(Closure $columns, ?Closure $filters = null): Testable
{
    ReviewsTable::$columns = $columns;
    ReviewsTable::$filters = $filters;

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
