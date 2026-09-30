<?php

use Packstub\FilamentRating\Columns\RatingColumn;
use Packstub\FilamentRating\Components\Rating;
use Packstub\FilamentRating\Entries\RatingEntry;
use Packstub\FilamentRating\FilamentRatingPlugin;
use Packstub\FilamentRating\RatingTheme;

/*
 * Drop-in compatibility with mokhosh/filament-rating: code that still imports
 * `Mokhosh\FilamentRating\...` resolves to this package's classes.
 *
 * The aliases are lazy: they are only created when a Mokhosh class is first used,
 * and only if Composer could not load the real one (mokhosh/filament-rating installed
 * next to this package keeps working untouched).
 */

spl_autoload_register(static function (string $class): void {
    $aliases = [
        'Mokhosh\FilamentRating\Components\Rating' => Rating::class,
        'Mokhosh\FilamentRating\Columns\RatingColumn' => RatingColumn::class,
        'Mokhosh\FilamentRating\Entries\RatingEntry' => RatingEntry::class,
        'Mokhosh\FilamentRating\RatingTheme' => RatingTheme::class,
        'Mokhosh\FilamentRating\FilamentRatingPlugin' => FilamentRatingPlugin::class,
    ];

    if (isset($aliases[$class]) && (! class_exists($class, false)) && (! enum_exists($class, false))) {
        class_alias($aliases[$class], $class);
    }
});
