<?php

use Filament\Forms\Components\Field;
use Filament\Infolists\Components\Entry;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\Column;
use Packstub\FilamentRating\Columns\RatingColumn;
use Packstub\FilamentRating\Components\Rating;
use Packstub\FilamentRating\Entries\RatingEntry;
use Packstub\FilamentRating\FilamentRatingPlugin;
use Packstub\FilamentRating\RatingTheme;

dataset('components', [
    'field' => fn () => Rating::make('rating'),
    'column' => fn () => RatingColumn::make('rating'),
    'entry' => fn () => RatingEntry::make('rating'),
]);

it('aliases every mokhosh class to this package', function (string $mokhosh, string $ours) {
    expect(class_exists($mokhosh) || enum_exists($mokhosh))->toBeTrue()
        ->and((new ReflectionClass($mokhosh))->getName())->toBe($ours);
})->with([
    ['Mokhosh\FilamentRating\Components\Rating', Rating::class],
    ['Mokhosh\FilamentRating\Columns\RatingColumn', RatingColumn::class],
    ['Mokhosh\FilamentRating\Entries\RatingEntry', RatingEntry::class],
    ['Mokhosh\FilamentRating\RatingTheme', RatingTheme::class],
    ['Mokhosh\FilamentRating\FilamentRatingPlugin', FilamentRatingPlugin::class],
]);

it('keeps the mokhosh base classes', function () {
    expect(new Mokhosh\FilamentRating\Components\Rating('rating'))->toBeInstanceOf(Field::class)
        ->and(Mokhosh\FilamentRating\Columns\RatingColumn::make('rating'))->toBeInstanceOf(Column::class)
        ->and(Mokhosh\FilamentRating\Entries\RatingEntry::make('rating'))->toBeInstanceOf(Entry::class)
        ->and(Mokhosh\FilamentRating\RatingTheme::HalfStars)->toBe(RatingTheme::HalfStars);
});

it('has mokhosh defaults', function ($component) {
    expect($component->getStars())->toBe(5)
        ->and($component->getStarsArray())->toBe([1, 2, 3, 4, 5])
        ->and($component->shouldAllowZero())->toBeFalse()
        ->and($component->getSize())->toBe('md')
        ->and($component->getColor())->toBe('primary')
        ->and($component->getTheme())->toBe(RatingTheme::Simple);
})->with('components');

it('supports the mokhosh fluent methods', function ($component) {
    $component
        ->stars(10)
        ->allowZero()
        ->size('xl')
        ->color('success')
        ->theme(RatingTheme::HalfStars);

    expect($component->getStars())->toBe(10)
        ->and($component->getStarsArray())->toBe(range(1, 10))
        ->and($component->shouldAllowZero())->toBeTrue()
        ->and($component->getSize())->toBe('xl')
        ->and($component->getColor())->toBe('success')
        ->and($component->getTheme())->toBe(RatingTheme::HalfStars);

    expect($component->allowZero(false)->shouldAllowZero())->toBeFalse()
        ->and($component->stars()->getStars())->toBe(5);
})->with('components');

it('accepts closures for every option', function ($component) {
    $component
        ->stars(fn () => 7)
        ->allowZero(fn () => true)
        ->allowHalf(fn () => true)
        ->size(fn () => IconSize::Large)
        ->color(fn () => 'danger')
        ->emptyColor(fn () => 'info')
        ->theme(fn () => RatingTheme::HalfStars);

    expect($component->getStars())->toBe(7)
        ->and($component->shouldAllowZero())->toBeTrue()
        ->and($component->allowsHalf())->toBeTrue()
        ->and($component->getSize())->toBe('lg')
        ->and($component->getColor())->toBe('danger')
        ->and($component->getEmptyColor())->toBe('info')
        ->and($component->getTheme())->toBe(RatingTheme::HalfStars);
})->with('components');

it('treats the half-stars theme as half steps', function ($component) {
    expect($component->getStep())->toBe(1)
        ->and($component->theme(RatingTheme::HalfStars)->getStep())->toBe(0.5);
})->with('components');

it('exposes the optional plugin with the mokhosh id', function () {
    expect(FilamentRatingPlugin::make())->toBeInstanceOf(FilamentRatingPlugin::class)
        ->and(FilamentRatingPlugin::make()->getId())->toBe('filament-rating');
});

it('keeps the mokhosh install command', function () {
    $this->artisan('filament-rating:install')
        ->expectsConfirmation('Would you like to star our repo on GitHub?', 'no')
        ->assertSuccessful();

    expect(public_path('css/packstub/filament-rating/filament-rating.css'))->toBeFile()
        ->and(public_path('js/packstub/filament-rating/components/rating.js'))->toBeFile();
});
