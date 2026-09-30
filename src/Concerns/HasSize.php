<?php

namespace Packstub\FilamentRating\Concerns;

use Closure;
use Filament\Support\Enums\IconSize;

trait HasSize
{
    protected string|IconSize|Closure|null $size = null;

    /**
     * @param  'xs' | 'sm' | 'md' | 'lg' | 'xl' | '2xl' | IconSize | Closure | null  $size
     */
    public function size(string|IconSize|Closure|null $size): static
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return 'xs' | 'sm' | 'md' | 'lg' | 'xl' | '2xl'
     */
    public function getSize(): string
    {
        $size = $this->evaluate($this->size) ?? $this->getDefaultSize();

        if ($size instanceof IconSize) {
            $size = $size->value;
        }

        return in_array($size, ['xs', 'sm', 'md', 'lg', 'xl', '2xl'], true) ? $size : 'md';
    }

    public function getDefaultSize(): string
    {
        return 'md';
    }
}
