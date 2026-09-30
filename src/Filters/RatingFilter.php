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
     * Match one rating exactly instead of "and up". Half ratings count toward the star they fill (3.5 matches 3).
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

        $column = $query->qualifyColumn($this->getAttribute());

        if (! $this->isExact()) {
            return $query->where($column, '>=', (int) $value);
        }

        return $query
            ->where($column, '>=', (int) $value)
            ->where($column, '<', ((int) $value) + 1);
    }
}
