<?php

namespace Packstub\FilamentRating\Summarizers;

use BackedEnum;
use Filament\Tables\Columns\Summarizers\Average;
use Illuminate\Contracts\Support\Htmlable;
use Packstub\FilamentRating\Columns\RatingColumn;
use Packstub\FilamentRating\Concerns\DisplaysRating;
use Packstub\FilamentRating\Concerns\HasColors;
use Packstub\FilamentRating\Concerns\HasIcons;
use Packstub\FilamentRating\Concerns\HasSize;
use Packstub\FilamentRating\Concerns\HasStars;
use Packstub\FilamentRating\Concerns\HasTheme;
use Packstub\FilamentRating\RatingTheme;

/**
 * The column's average as stars. On a RatingColumn it inherits the stars, colors, icons and size.
 */
class RatingAverage extends Average
{
    use DisplaysRating;
    use HasColors;
    use HasIcons;
    use HasSize;
    use HasStars;
    use HasTheme;

    protected string $view = 'filament-rating::summary';

    public function getDefaultLabel(): ?string
    {
        return __('filament-rating::rating.summary.average');
    }

    public function getRatingColumn(): ?RatingColumn
    {
        $column = $this->getColumn();

        return $column instanceof RatingColumn ? $column : null;
    }

    public function getDefaultStars(): int
    {
        return $this->getRatingColumn()?->getStars() ?? 5;
    }

    public function getDefaultTheme(): RatingTheme
    {
        return $this->getRatingColumn()?->getTheme() ?? RatingTheme::Simple;
    }

    public function getDefaultAllowZero(): bool
    {
        return $this->getRatingColumn()?->shouldAllowZero() ?? false;
    }

    public function getDefaultSize(): string
    {
        return $this->getRatingColumn()?->getSize() ?? 'sm';
    }

    /**
     * @return string | array<int | string, string>
     */
    public function getDefaultColor(): string|array
    {
        return $this->getRatingColumn()?->getColor() ?? 'primary';
    }

    /**
     * @return string | array<int | string, string>
     */
    public function getDefaultEmptyColor(): string|array
    {
        return $this->getRatingColumn()?->getEmptyColor() ?? 'gray';
    }

    public function getDefaultIcon(): string|BackedEnum|Htmlable|null
    {
        return $this->getRatingColumn()?->getIcon();
    }

    public function getDefaultEmptyIcon(): string|BackedEnum|Htmlable|null
    {
        return $this->getRatingColumn()?->getEmptyIcon();
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
