# Filament Rating

Star ratings for Filament v4 and v5: a form field, a table column, an infolist entry and an average summarizer. It supports half stars, keyboard and screen readers, dark mode and RTL, and needs no npm build.

It is also a drop-in replacement for [`mokhosh/filament-rating`](https://github.com/mokhosh/filament-rating): the same methods, the same data, and the old `Mokhosh\FilamentRating\...` imports keep working (see [Migrating from mokhosh/filament-rating](#migrating-from-mokhoshfilament-rating)).

## Features

- **Form field** `Rating`: click, hover preview, arrow keys / Home / End, optional half stars (`allowHalf()`), zero (`allowZero()`), `clearable()`, `readOnly()`, `disabled()`.
- **Table column** `RatingColumn` and **infolist entry** `RatingEntry`: exact partial fill for averages (3.7 fills 70% of the fourth star), `showValue()`, `showCount()`, `precision()`, tooltips and placeholders.
- **Summarizer** `RatingAverage`: the column's average drawn as stars, using the column's settings.
- **Validation included**: `numeric`, `min` (0 or the first step), `max` (the number of stars), and `integer` or `multiple_of:0.5`.
- **Any color, any icon**: Filament color names, Tailwind palette names (`amber`), `Color::*` palettes or hex, with no Tailwind classes needed. Icons can be overridden per component or app-wide through Filament's icon aliases.
- **Accessible**: `radiogroup` / `radio` (or `slider` in half-star mode), a roving tab stop, a visible focus ring, and "3 of 5 stars" labels for screen readers.
- **Works anywhere Filament does**: inside panels and in standalone Livewire forms, on Filament 4 (Livewire 3) and Filament 5 (Livewire 4). Every option accepts a closure.
- **Translated**: English and Romanian included.

## Compatibility

| Plugin | Filament | Livewire | Laravel | PHP |
| --- | --- | --- | --- | --- |
| 0.x | 4.x, 5.x | 3.x, 4.x | 11.x – 13.x | 8.2+ |

## Installation

```bash
composer require packstub/filament-rating
php artisan filament:assets
```

`filament:assets` publishes the plugin's CSS and its small Alpine component. Filament already runs it on `composer update` if you use its `post-autoload-dump` script. You don't need to register a panel plugin, rebuild a theme or run npm.

Outside a panel (a plain Livewire component using Filament forms), the assets load with the usual `@filamentStyles` and `@filamentScripts` directives in your layout.

## Usage

### Form field

```php
use Packstub\FilamentRating\Components\Rating;

Rating::make('rating')
    ->required()
    ->default(5);
```

The state is an integer from 1 to `stars()` (or `0` with `allowZero()`, or `0.5` steps with `allowHalf()`), and `null` when nothing is picked. It fits an integer (or decimal) column.

```php
Rating::make('rating')
    ->stars(10)          // default 5
    ->allowZero()        // adds a "no stars" button that stores 0
    ->allowHalf()        // 0.5 steps: the left half of a star picks the half value
    ->clearable()        // click the selected star again, press Delete, or use the × button
    ->size('lg')         // xs, sm, md (default), lg, xl, 2xl, or an IconSize
    ->color('warning')   // default primary
    ->readOnly();
```

Keyboard: arrow keys move by one step (they follow the reading direction in RTL), Home and End jump to the minimum and maximum, and Delete or Backspace clears when `clearable()`.

### Table column

```php
use Packstub\FilamentRating\Columns\RatingColumn;

RatingColumn::make('rating')
    ->sortable()
    ->searchable();
```

Show an average with its value and the number of ratings:

```php
RatingColumn::make('reviews_avg_rating')
    ->avg('reviews', 'rating')
    ->showValue()                                                        // "4.3"
    ->precision(1)                                                       // max decimals, trailing zeros dropped
    ->showCount(fn (Product $record): int => $record->reviews_count)     // "(128)"
    ->placeholder('No reviews yet')
    ->tooltip(fn ($state): string => "Average of {$state}");
```

### Average summarizer

```php
use Packstub\FilamentRating\Summarizers\RatingAverage;

RatingColumn::make('rating')
    ->stars(10)
    ->color('amber')
    ->summarize(RatingAverage::make());
```

`RatingAverage` draws the column's average as stars with the value next to them. It takes the column's stars, colors, icons and size unless you set them on the summarizer.

### Infolist entry

```php
use Packstub\FilamentRating\Entries\RatingEntry;

RatingEntry::make('rating')
    ->showValue()
    ->size('lg');
```

## Customization

All options are available on the field, the column, the entry and the summarizer, and each one accepts a closure.

| Method | Default | Notes |
| --- | --- | --- |
| `stars(int)` | `5` | Number of stars, also the validation `max`. |
| `allowZero(bool)` | `false` | Allows `0`; the field shows a "no stars" button, displays show a no-symbol icon. |
| `allowHalf(bool)` | `false` | Half-star steps in the field. |
| `size(string\|IconSize)` | `md` | `xs` 12px, `sm` 16px, `md` 24px, `lg` 32px, `xl` 40px, `2xl` 48px. |
| `color(string\|array)` | `primary` | Registered color, Tailwind palette name, `Color::Amber`, or `#f59e0b`. |
| `emptyColor(string\|array)` | `gray` | Color of the unfilled part. |
| `icon(...)` | solid star | Any icon Filament accepts: a Blade icon name, a `Heroicon` case, an `Htmlable`. |
| `emptyIcon(...)` | the filled icon | For example `emptyIcon('heroicon-o-star')` for outlined empty stars. |
| `theme(RatingTheme)` | `Simple` | `HalfStars`: half steps in the field and rounded-down halves in displays (kept from mokhosh). |
| `showValue()`, `precision(int)`, `showCount(int)` | off, `1`, none | Column, entry and summarizer only. |
| `clearable()`, `readOnly()` | off | Field only. |

To change the icons everywhere, register their aliases in a service provider:

```php
use Filament\Support\Facades\FilamentIcon;
use Packstub\FilamentRating\RatingIcon;

FilamentIcon::register([
    RatingIcon::Star => 'heroicon-s-heart',
    RatingIcon::StarEmpty => 'heroicon-o-heart',
]);
```

The stars use their own plain CSS, so there's nothing to add to a custom theme. If you style the `fi-rating-*` classes with Tailwind in your theme, add the views to its sources:

```css
@source '../../../../vendor/packstub/filament-rating/resources/views/**/*';
```

## Migrating from mokhosh/filament-rating

The plugin keeps mokhosh v2's public API, so moving over takes three steps:

```bash
composer remove mokhosh/filament-rating
composer require packstub/filament-rating
php artisan filament:assets
```

1. Your existing `use Mokhosh\FilamentRating\...` imports keep working: the package aliases those five classes to its own. Aliases are created lazily and only when mokhosh itself isn't installed.
2. When convenient, switch the imports to the new namespace (a search and replace of `Mokhosh\FilamentRating` with `Packstub\FilamentRating`).
3. Delete the old published assets, `public/css/mokhosh` and `public/js/mokhosh`.

| mokhosh v2 | packstub/filament-rating |
| --- | --- |
| `Mokhosh\FilamentRating\Components\Rating` | `Packstub\FilamentRating\Components\Rating` |
| `Mokhosh\FilamentRating\Columns\RatingColumn` | `Packstub\FilamentRating\Columns\RatingColumn` |
| `Mokhosh\FilamentRating\Entries\RatingEntry` | `Packstub\FilamentRating\Entries\RatingEntry` |
| `Mokhosh\FilamentRating\RatingTheme` | `Packstub\FilamentRating\RatingTheme` (same cases) |
| `Mokhosh\FilamentRating\FilamentRatingPlugin` | `Packstub\FilamentRating\FilamentRatingPlugin` (same id, optional) |
| `stars(int)`, `getStars()`, `getStarsArray()` | same, also accepts a closure |
| `allowZero()`, `shouldAllowZero()` | same |
| `size('xs'…'xl')`, `getSize()` | same sizes in pixels, plus `2xl` and `IconSize` |
| `color('primary')`, `getColor()` | same, plus palette names, `Color::*` arrays and hex |
| `theme(RatingTheme::HalfStars)`, `getTheme()` | same; `allowHalf()` is the explicit spelling |
| `php artisan filament-rating:install` | same command (publishes the assets) |

A few things behave differently:

- Integer ratings look the same as before: the same sizes, filled stars in the chosen color and empty stars in light gray.
- A decimal like 3.7 in a column or entry now fills 70% of the fourth star. mokhosh showed three full stars. Use `theme(RatingTheme::HalfStars)` to round down to halves.
- The field now validates its range (`min`, `max`, `integer` or `multiple_of:0.5`). If you stored values outside `1..stars`, raise `stars()` or add `allowZero()`.
- Published mokhosh views in `resources/views/vendor/filament-rating` are not used, because the view names are different.

## Links

- Repository: [github.com/packstub/filament-rating](https://github.com/packstub/filament-rating)
- Packagist: [packstub/filament-rating](https://packagist.org/packages/packstub/filament-rating)
- Support: [GitHub issues](https://github.com/packstub/filament-rating/issues)
