<?php

namespace Packstub\FilamentRating\Filters;

use Closure;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Filter records by rating: "4 stars & up" by default, or one exact rating with `exact()`.
 */
class RatingFilter extends SelectFilter
{
    protected int|Closure $stars = 5;

    protected bool|Closure $isExact = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->options(static fn (RatingFilter $filter): array => $filter->getRatingOptions());
    }

    public function stars(int|Closure $stars = 5): static
    {
        $this->stars = $stars;

        return $this;
    }

    public function getStars(): int
    {
        return max(1, (int) $this->evaluate($this->stars));
    }

    /**
     * Match one rating exactly instead of "and up". Half ratings count toward the star below (3.5 matches 3), and 0.5 toward 1.
     */
    public function exact(bool|Closure $condition = true): static
    {
        $this->isExact = $condition;

        return $this;
    }

    public function isExact(): bool
    {
        return (bool) $this->evaluate($this->isExact);
    }

    /**
     * @return array<int, string>
     */
    public function getRatingOptions(): array
    {
        $stars = $this->getStars();
        $options = [];

        foreach (range($stars, 1) as $value) {
            $options[$value] = ($this->isExact() || ($value === $stars))
                ? trans_choice('filament-rating::rating.stars', $value, ['value' => $value])
                : trans_choice('filament-rating::rating.filter.and_up', $value, ['value' => $value]);
        }

        return $options;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function apply(Builder $query, array $data = []): Builder
    {
        if ($this->hasQueryModificationCallback() || $this->isMultiple()) {
            return parent::apply($query, $data);
        }

        $value = $data['value'] ?? null;

        if (blank($value) || (! is_numeric($value))) {
            return $query;
        }

        $value = (int) $value;
        $column = $query->qualifyColumn($this->getAttribute());

        // "1 star" takes every rating above zero, so a 0.5 is found too.
        $query = ($value <= 1)
            ? $query->where($column, '>', 0)
            : $query->where($column, '>=', $value);

        return $this->isExact() ? $query->where($column, '<', $value + 1) : $query;
    }
}
