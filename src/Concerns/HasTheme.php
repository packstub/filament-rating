<?php

namespace Packstub\FilamentRating\Concerns;

use Closure;
use Packstub\FilamentRating\RatingTheme;

trait HasTheme
{
    protected RatingTheme|Closure|null $theme = null;

    public function theme(RatingTheme|Closure|null $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getTheme(): RatingTheme
    {
        return $this->evaluate($this->theme) ?? $this->getDefaultTheme();
    }

    public function getDefaultTheme(): RatingTheme
    {
        return RatingTheme::Simple;
    }
}
