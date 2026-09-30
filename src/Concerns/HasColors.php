<?php

namespace Packstub\FilamentRating\Concerns;

use Closure;
use Packstub\FilamentRating\Support\RatingColor;

trait HasColors
{
    /**
     * @var string | array<int | string, string> | Closure | null
     */
    protected string|array|Closure|null $color = null;

    /**
     * @var string | array<int | string, string> | Closure | null
     */
    protected string|array|Closure|null $emptyColor = null;

    /**
     * A Filament color name (`primary`, `warning`, a registered color, or a Tailwind palette name like `amber`),
     * a `Color::*` palette, or a CSS color (`#f59e0b`).
     *
     * @param  string | array<int | string, string> | Closure | null  $color
     */
    public function color(string|array|Closure|null $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @param  string | array<int | string, string> | Closure | null  $color
     */
    public function emptyColor(string|array|Closure|null $color): static
    {
        $this->emptyColor = $color;

        return $this;
    }

    /**
     * @return string | array<int | string, string>
     */
    public function getColor(): string|array
    {
        return $this->evaluate($this->color) ?? $this->getDefaultColor();
    }

    /**
     * @return string | array<int | string, string>
     */
    public function getEmptyColor(): string|array
    {
        return $this->evaluate($this->emptyColor) ?? $this->getDefaultEmptyColor();
    }

    /**
     * @return string | array<int | string, string>
     */
    public function getDefaultColor(): string|array
    {
        return 'primary';
    }

    /**
     * @return string | array<int | string, string>
     */
    public function getDefaultEmptyColor(): string|array
    {
        return 'gray';
    }

    public function getColorStyle(): string
    {
        return RatingColor::cssVariables($this->getColor(), 'fi-rating-color')
            .RatingColor::cssVariables($this->getEmptyColor(), 'fi-rating-empty-color');
    }
}
