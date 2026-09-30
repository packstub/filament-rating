<?php

namespace Packstub\FilamentRating\Concerns;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Packstub\FilamentRating\Columns\RatingColumn;
use Packstub\FilamentRating\RatingTheme;

/**
 * Summarizer defaults taken from the RatingColumn they summarize: stars, colors, icons, size and labels.
 */
trait InheritsRatingColumn
{
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

    /**
     * @return array<int | string, string | array<int | string, string>>
     */
    public function getDefaultColors(): array
    {
        return $this->getRatingColumn()?->getColors() ?? [];
    }

    public function getDefaultIcon(): string|BackedEnum|Htmlable|null
    {
        return $this->getRatingColumn()?->getIcon();
    }

    public function getDefaultEmptyIcon(): string|BackedEnum|Htmlable|null
    {
        return $this->getRatingColumn()?->getEmptyIcon();
    }

    /**
     * @return array<int | string, string>
     */
    public function getDefaultLabels(): array
    {
        return $this->getRatingColumn()?->getLabels() ?? [];
    }
}
