<?php

namespace Packstub\Rating\Summarizers;

use Closure;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Database\Query\Builder;
use Packstub\Rating\Concerns\HasColors;
use Packstub\Rating\Concerns\HasIcons;
use Packstub\Rating\Concerns\HasLabels;
use Packstub\Rating\Concerns\HasSize;
use Packstub\Rating\Concerns\HasStars;
use Packstub\Rating\Concerns\HasTheme;
use Packstub\Rating\Concerns\InheritsRatingColumn;

/**
 * How many records have each rating, as bars from the highest rating down. Half ratings count toward
 * the star below (3.5 counts as 3), and 0.5 toward 1. On a RatingColumn it inherits the stars, colors, icon and labels.
 */
class RatingDistribution extends Summarizer
{
    use HasColors, HasIcons, HasLabels, HasSize, HasStars, HasTheme, InheritsRatingColumn {
        InheritsRatingColumn::getDefaultStars insteadof HasStars;
        InheritsRatingColumn::getDefaultAllowZero insteadof HasStars;
        InheritsRatingColumn::getDefaultTheme insteadof HasTheme;
        InheritsRatingColumn::getDefaultSize insteadof HasSize;
        InheritsRatingColumn::getDefaultColor insteadof HasColors;
        InheritsRatingColumn::getDefaultEmptyColor insteadof HasColors;
        InheritsRatingColumn::getDefaultColors insteadof HasColors;
        InheritsRatingColumn::getDefaultIcon insteadof HasIcons;
        InheritsRatingColumn::getDefaultEmptyIcon insteadof HasIcons;
        InheritsRatingColumn::getDefaultLabels insteadof HasLabels;
    }

    protected string $view = 'filament-rating::distribution';

    protected bool|Closure $showPercentages = false;

    /**
     * Show each rating's share ("40%") instead of its count.
     */
    public function percentages(bool|Closure $condition = true): static
    {
        $this->showPercentages = $condition;

        return $this;
    }

    public function shouldShowPercentages(): bool
    {
        return (bool) $this->evaluate($this->showPercentages);
    }

    /**
     * @return array<int, int> the number of records per whole rating
     */
    public function summarize(Builder $query, string $attribute): array
    {
        $counts = $query->clone()
            ->whereNotNull($attribute)
            ->select($attribute)
            ->selectRaw('count(*) as aggregate')
            ->groupBy($attribute)
            ->get();

        $distribution = [];

        foreach ($counts as $row) {
            $row = (array) $row;
            $value = $row[$attribute] ?? null;

            if (! is_numeric($value)) {
                continue;
            }

            $value = max(0, min((float) $value, $this->getStars()));

            // Only a real zero lands on the "no stars" row: 0.5 counts toward 1.
            $star = $value > 0 ? max(1, (int) floor($value)) : 0;
            $distribution[$star] = ($distribution[$star] ?? 0) + (int) $row['aggregate'];
        }

        return $distribution;
    }

    /**
     * @return array<int, array{count: int, percentage: float}> keyed by rating, from the highest down
     */
    public function getRows(): array
    {
        $state = $this->getState();
        $counts = is_array($state) ? $state : [];

        $ratings = range($this->getStars(), $this->shouldAllowZero() ? 0 : 1);

        // Shares of the rows shown, so they add up to 100% even when zeros are stored without allowZero().
        $total = array_sum(array_intersect_key($counts, array_flip($ratings)));
        $rows = [];

        foreach ($ratings as $rating) {
            $count = (int) ($counts[$rating] ?? 0);

            $rows[$rating] = [
                'count' => $count,
                'percentage' => $total > 0 ? ($count / $total) * 100 : 0.0,
            ];
        }

        return $rows;
    }

    public function getDefaultLabel(): ?string
    {
        return __('filament-rating::rating.summary.distribution');
    }
}
