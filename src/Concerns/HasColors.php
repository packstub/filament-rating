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
     * @var array<int | string, string | array<int | string, string>> | Closure | null
     */
    protected array|Closure|null $colors = null;

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
     * Color the stars by value: each key is the lowest rating that uses its color, e.g.
     * `[1 => 'danger', 3 => 'warning', 4 => 'success']`. Ratings below the lowest key use `color()`.
     *
     * @param  array<int | string, string | array<int | string, string>> | Closure | null  $colors
     */
    public function colors(array|Closure|null $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * @return array<string, string | array<int | string, string>> keyed by the lowest value, ascending
     */
    public function getColors(): array
    {
        $colors = [];

        foreach ($this->evaluate($this->colors) ?? $this->getDefaultColors() as $min => $color) {
            if (is_numeric($min) && filled($color)) {
                $colors[(string) (float) $min] = $color;
            }
        }

        uksort($colors, fn (string $a, string $b): int => (float) $a <=> (float) $b);

        return $colors;
    }

    /**
     * @return array<int | string, string | array<int | string, string>>
     */
    public function getDefaultColors(): array
    {
        return [];
    }

    /**
     * @return string | array<int | string, string>
     */
    public function getColorFor(float|int|null $value): string|array
    {
        $color = $this->getColor();

        if ($value === null) {
            return $color;
        }

        foreach ($this->getColors() as $min => $thresholdColor) {
            if ($value >= (float) $min) {
                $color = $thresholdColor;
            }
        }

        return $color;
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

    public function getColorStyle(float|int|null $value = null): string
    {
        return RatingColor::cssVariables($this->getColorFor($value), 'fi-rating-color')
            .RatingColor::cssVariables($this->getEmptyColor(), 'fi-rating-empty-color');
    }

    /**
     * The fill color variables per threshold, for the field to switch colors while hovering.
     *
     * @return array<int, array{0: float, 1: array<string, string>}>
     */
    public function getColorThresholdVariables(): array
    {
        $thresholds = [];

        foreach ($this->getColors() as $min => $color) {
            $thresholds[] = [(float) $min, RatingColor::variables($color, 'fi-rating-color')];
        }

        return $thresholds;
    }

    /**
     * @return array<string, string>
     */
    public function getBaseColorVariables(): array
    {
        return RatingColor::variables($this->getColor(), 'fi-rating-color');
    }
}
