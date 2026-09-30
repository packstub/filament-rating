<?php

namespace Packstub\FilamentRating\Summarizers;

use Closure;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Database\Query\Builder;
use Packstub\FilamentRating\Concerns\HasColors;
use Packstub\FilamentRating\Concerns\HasIcons;
use Packstub\FilamentRating\Concerns\HasLabels;
use Packstub\FilamentRating\Concerns\HasSize;
use Packstub\FilamentRating\Concerns\HasStars;
use Packstub\FilamentRating\Concerns\HasTheme;
use Packstub\FilamentRating\Concerns\InheritsRatingColumn;

/**
 * How many records have each rating, as bars from the highest rating down. Half ratings count toward
 * the star they fill (3.5 counts as 3). On a RatingColumn it inherits the stars, colors, icon and labels.
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

            $star = (int) floor(max(0, min((float) $value, $this->getStars())));
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
        $total = array_sum($counts);

        $ratings = range($this->getStars(), $this->shouldAllowZero() ? 0 : 1);
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
