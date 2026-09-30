# Changelog

All notable changes to `packstub/filament-rating` are documented here.

## Unreleased

- `RatingInputColumn`: rate records straight from the table, validated and saved on click, with `clearable()`, `disabled()` and the usual state update hooks (mokhosh/filament-rating#11).
- `labels()`: a word per rating, shown next to the field's stars while hovering, announced by screen readers, and shown in displays with `showLabel()`.
- `colors()`: color the stars by value (`[1 => 'danger', 3 => 'warning', 4 => 'success']`); in the field the color follows the hover.
- `RatingFilter`: "4 stars & up" table filter, or one exact rating with `exact()`.
- `RatingDistribution` summarizer: a bar per rating with counts or `percentages()`.
- Summarizers also inherit the column's `labels()` and `colors()`.

## 0.1.0 — 2026-09-30

First release.

- `Rating` form field, `RatingColumn`, `RatingEntry` and the `RatingAverage` summarizer for Filament 4 (Livewire 3) and Filament 5 (Livewire 4).
- Drop-in API of `mokhosh/filament-rating` v2, with lazy `Mokhosh\FilamentRating\...` class aliases.
- Half stars (`allowHalf()`), `clearable()`, `readOnly()`, hover preview, keyboard control, and `radiogroup` / `slider` semantics with screen-reader labels.
- Partial fill for decimals, `showValue()`, `precision()`, `showCount()`, placeholders and tooltips in columns and entries.
- Automatic `numeric`, `min`, `max` and `integer` / `multiple_of:0.5` validation.
- Colors from Filament names, Tailwind palette names, `Color::*` palettes or hex; icons via `icon()`, `emptyIcon()` and `RatingIcon` aliases.
- Plain CSS and a hand-written Alpine component, registered as Filament assets: no npm build, and it works in standalone Livewire forms.
- English and Romanian translations; dark mode and RTL.
