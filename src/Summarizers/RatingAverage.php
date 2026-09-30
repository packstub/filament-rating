<?php

namespace Packstub\FilamentRating\Summarizers;

use Filament\Tables\Columns\Summarizers\Average;
use Packstub\FilamentRating\Concerns\DisplaysRating;
use Packstub\FilamentRating\Concerns\HasColors;
use Packstub\FilamentRating\Concerns\HasIcons;
use Packstub\FilamentRating\Concerns\HasLabels;
use Packstub\FilamentRating\Concerns\HasSize;
use Packstub\FilamentRating\Concerns\HasStars;
use Packstub\FilamentRating\Concerns\HasTheme;
use Packstub\FilamentRating\Concerns\InheritsRatingColumn;

/**
 * The column's average as stars. On a RatingColumn it inherits the stars, colors, icons, size and labels.
 */
class RatingAverage extends Average
{
    use DisplaysRating;
    use HasColors, HasIcons, HasLabels, HasSize, HasStars, HasTheme, InheritsRatingColumn {
        InheritsRatingColumn::getDefaultStars insteadof HasStars;
        InheritsRatingColumn::getDefaultAllowZero insteadof HasStars;
        InheritsRatingColumn::getDefaultTheme insteadof HasTheme;
        InheritsRatingColumn::getDefaultSize insteadof HasSize;
        InheritsRatingColumn::getDefaultColor insteadof HasColors;
        InheritsRatingColumn::getDefaultEmptyColor insteadof HasColors;
        InheritsRatingColumn::getDefaultColors insteadof HasColors;
        InheritsRatingColumn::getDefaultIcon insteadof HasIcons;
        InheritsRatingColumn::getDefaultEmptyIcon insteadof HasIcons;
        InheritsRatingColumn::getDefaultLabels insteadof HasLabels;
    }

    protected string $view = 'filament-rating::summary';

    public function getDefaultLabel(): ?string
    {
        return __('filament-rating::rating.summary.average');
    }

    public function getDefaultShowValue(): bool
    {
        return true;
    }

    public function getDefaultPrecision(): int
    {
        return $this->getRatingColumn()?->getPrecision() ?? 1;
    }
}
