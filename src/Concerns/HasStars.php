<?php

namespace Packstub\FilamentRating\Concerns;

use Closure;
use Packstub\FilamentRating\RatingTheme;

trait HasStars
{
    protected int|Closure|null $stars = null;

    protected bool|Closure|null $allowZero = null;

    protected bool|Closure|null $allowHalf = null;

    public function stars(int|Closure $stars = 5): static
    {
        $this->stars = $stars;

        return $this;
    }

    public function getStars(): int
    {
        return max(1, (int) ($this->evaluate($this->stars) ?? $this->getDefaultStars()));
    }

    /**
     * @return array<int>
     */
    public function getStarsArray(): array
    {
        return range(1, $this->getStars());
    }

    public function allowZero(bool|Closure $allowZero = true): static
    {
        $this->allowZero = $allowZero;

        return $this;
    }

    public function shouldAllowZero(): bool
    {
        return (bool) ($this->evaluate($this->allowZero) ?? $this->getDefaultAllowZero());
    }

    /**
     * Pick and show ratings in half-star steps (0.5, 1, 1.5 ...).
     */
    public function allowHalf(bool|Closure $allowHalf = true): static
    {
        $this->allowHalf = $allowHalf;

        return $this;
    }

    public function allowsHalf(): bool
    {
        return (bool) ($this->evaluate($this->allowHalf) ?? $this->getDefaultAllowHalf()) || ($this->getTheme() === RatingTheme::HalfStars);
    }

    public function getDefaultStars(): int
    {
        return 5;
    }

    public function getDefaultAllowZero(): bool
    {
        return false;
    }

    public function getDefaultAllowHalf(): bool
    {
        return false;
    }

    public function getStep(): float|int
    {
        return $this->allowsHalf() ? 0.5 : 1;
    }

    public function getMinValue(): float|int
    {
        return $this->shouldAllowZero() ? 0 : $this->getStep();
    }

    public function getMaxValue(): int
    {
        return $this->getStars();
    }
}
