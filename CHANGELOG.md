# Changelog

All notable changes to `packstub/filament-rating` are documented here.

## Unreleased

- Each bar and star of `RatingDistribution` takes its own color from `colors()` (they all used the base color)
- A shorter Features list in the docs, and screenshots that show one feature each

## 0.2.0 — 2026-09-30

- `RatingInputColumn`: rate records straight from the table, saved on click (mokhosh/filament-rating#11)
- `labels()`: a word per rating, shown while hovering and read by screen readers
- `colors()`: color the stars by value (`[1 => 'danger', 4 => 'success']`)
- `RatingFilter`: a "4 stars & up" table filter, or one exact rating
- `RatingDistribution` summarizer: a bar per rating, with counts or percentages

## 0.1.0 — 2026-09-30

First release of Filament Rating: a star rating field, column, entry and average summarizer for Filament 4 and 5.

- A drop-in for `mokhosh/filament-rating` v2, its class names included
- Half stars, clearable and read-only ratings, keyboard control and screen reader support
- Decimals shown as partly filled stars, with the value and the count if you like
- Validation added for you, any Filament or Tailwind color, your own icons
- No npm build, and it works in plain Livewire forms too
- English and Romanian, dark mode and RTL

Docs: https://packstub.dev/docs/filament-rating
