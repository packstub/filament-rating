<?php

namespace Packstub\FilamentRating\Concerns;

use BackedEnum;
use Closure;
use Packstub\FilamentRating\RatingTheme;

/**
 * Read-only rendering shared by the column, the entry and the summarizer.
 */
trait DisplaysRating
{
    protected bool|Closure|null $showValue = null;

    protected int|Closure|null $precision = null;

    protected int|Closure|null $count = null;

    protected bool|Closure $showLabel = false;

    /**
     * Show the number next to the stars, e.g. "4.5".
     */
    public function showValue(bool|Closure $condition = true): static
    {
        $this->showValue = $condition;

        return $this;
    }

    public function shouldShowValue(): bool
    {
        return (bool) ($this->evaluate($this->showValue) ?? $this->getDefaultShowValue());
    }

    /**
     * Maximum decimals of the shown value (trailing zeros are dropped: 4, 4.5, 3.67).
     */
    public function precision(int|Closure $precision): static
    {
        $this->precision = $precision;

        return $this;
    }

    public function getPrecision(): int
    {
        return max(0, (int) ($this->evaluate($this->precision) ?? $this->getDefaultPrecision()));
    }

    /**
     * Show how many ratings the value is based on, e.g. "(128)".
     */
    public function showCount(int|Closure|null $count): static
    {
        $this->count = $count;

        return $this;
    }

    public function getCount(): ?int
    {
        $count = $this->evaluate($this->count);

        return filled($count) ? (int) $count : null;
    }

    /**
     * Show the rating's label from `labels()` next to the stars, e.g. "Very good".
     */
    public function showLabel(bool|Closure $condition = true): static
    {
        $this->showLabel = $condition;

        return $this;
    }

    public function shouldShowLabel(): bool
    {
        return (bool) $this->evaluate($this->showLabel);
    }

    public function getDefaultShowValue(): bool
    {
        return false;
    }

    public function getDefaultPrecision(): int
    {
        return 1;
    }

    public function normalizeRatingState(mixed $state): ?float
    {
        if ($state instanceof BackedEnum) {
            $state = $state->value;
        }

        if (blank($state) || (! is_numeric($state))) {
            return null;
        }

        $state = max(0, min((float) $state, $this->getStars()));

        if ($this->getTheme() === RatingTheme::HalfStars) {
            return floor($state * 2) / 2;
        }

        return $state;
    }

    /**
     * How much of each star is filled, from 0 to 1, keyed by star number.
     *
     * @return array<int, float>
     */
    public function getFills(?float $state): array
    {
        $fills = [];

        foreach ($this->getStarsArray() as $star) {
            $fills[$star] = max(0, min(1, ($state ?? 0) - ($star - 1)));
        }

        return $fills;
    }

    public function formatRatingValue(?float $state): string
    {
        if ($state === null) {
            return '';
        }

        $value = number_format($state, $this->getPrecision(), '.', '');

        return str_contains($value, '.') ? rtrim(rtrim($value, '0'), '.') : $value;
    }

    public function getRatingLabel(?float $state): string
    {
        if ($state === null) {
            return __('filament-rating::rating.not_rated');
        }

        $text = trans_choice('filament-rating::rating.value_of_max', $this->getStars(), [
            'value' => $this->formatRatingValue($state),
            'max' => $this->getStars(),
        ]);

        if (filled($label = $this->getLabelFor($state))) {
            return __('filament-rating::rating.labelled', ['label' => $label, 'value' => $text]);
        }

        return $text;
    }
}
