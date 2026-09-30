<?php

namespace Packstub\FilamentRating\Concerns;

use BackedEnum;
use Closure;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Packstub\FilamentRating\RatingIcon;

trait HasIcons
{
    protected string|BackedEnum|Htmlable|Closure|null $icon = null;

    protected string|BackedEnum|Htmlable|Closure|null $emptyIcon = null;

    public function icon(string|BackedEnum|Htmlable|Closure|null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function emptyIcon(string|BackedEnum|Htmlable|Closure|null $icon): static
    {
        $this->emptyIcon = $icon;

        return $this;
    }

    public function getIcon(): string|BackedEnum|Htmlable
    {
        return $this->evaluate($this->icon)
            ?? $this->getDefaultIcon()
            ?? FilamentIcon::resolve(RatingIcon::Star)
            ?? 'heroicon-s-star';
    }

    /**
     * Defaults to the filled icon, drawn in the empty color.
     */
    public function getEmptyIcon(): string|BackedEnum|Htmlable
    {
        return $this->evaluate($this->emptyIcon)
            ?? $this->getDefaultEmptyIcon()
            ?? FilamentIcon::resolve(RatingIcon::StarEmpty)
            ?? $this->getIcon();
    }

    public function getDefaultIcon(): string|BackedEnum|Htmlable|null
    {
        return null;
    }

    public function getDefaultEmptyIcon(): string|BackedEnum|Htmlable|null
    {
        return null;
    }

    public function getClearIcon(): string|BackedEnum|Htmlable
    {
        return FilamentIcon::resolve(RatingIcon::Clear) ?? 'heroicon-m-x-mark';
    }

    public function getZeroIcon(): string|BackedEnum|Htmlable
    {
        return FilamentIcon::resolve(RatingIcon::Zero) ?? 'heroicon-c-no-symbol';
    }
}
