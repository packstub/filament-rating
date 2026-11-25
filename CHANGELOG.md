# Changelog

All notable changes to `packstub/filament-rating` are documented here.

## 1.0.0 — Unreleased

The first stable release: the field, the columns, the entry, the filter and the summarizers now follow semantic versioning. The PHP namespace is now `Packstub\Rating`.

**Upgrading:** replace `Packstub\FilamentRating\` with `Packstub\Rating\` in your imports (a search and replace). The old `Mokhosh\FilamentRating\...` imports keep working.

- `RatingDistribution` colors each bar and star by its rating with `colors()` (they all used the base color)
- A 0.5 rating counts as one star in `RatingDistribution` and `RatingFilter` (it was left out of both), and the distribution's percentages add up to 100%
- Docs with a screenshot for each feature, and Packagist links to the plugin's page on packstub.dev

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
