<?php

namespace Packstub\FilamentRating\Concerns;

use Closure;

/**
 * Picking a rating with the stars, shared by the form field and the editable table column.
 */
trait HasRatingInput
{
    protected bool|Closure $isClearable = false;

    /**
     * Clicking the selected star again (or pressing Delete) empties the rating.
     */
    public function clearable(bool|Closure $condition = true): static
    {
        $this->isClearable = $condition;

        return $this;
    }

    public function isClearable(): bool
    {
        return (bool) $this->evaluate($this->isClearable);
    }

    /**
     * @return array<string>
     */
    public function getRatingRules(): array
    {
        return [
            'numeric',
            'min:'.$this->getMinValue(),
            'max:'.$this->getMaxValue(),
            $this->allowsHalf() ? 'multiple_of:0.5' : 'integer',
        ];
    }

    public function normalizeRatingState(mixed $state): ?float
    {
        if (blank($state) || (! is_numeric($state))) {
            return null;
        }

        return max(0, min((float) $state, $this->getStars()));
    }

    /**
     * "4 of 5 stars", or "Very good, 4 of 5 stars" with labels().
     */
    public function getValueText(float|int $value): string
    {
        $text = trans_choice('filament-rating::rating.value_of_max', $this->getStars(), [
            'value' => rtrim(rtrim(number_format($value, 1, '.', ''), '0'), '.'),
            'max' => $this->getStars(),
        ]);

        return filled($label = $this->getLabelFor($value))
            ? __('filament-rating::rating.labelled', ['label' => $label, 'value' => $text])
            : $text;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRatingInputConfig(): array
    {
        $colorThresholds = $this->getColorThresholdVariables();

        return [
            'stars' => $this->getStars(),
            'step' => $this->getStep(),
            'allowZero' => $this->shouldAllowZero(),
            'isClearable' => $this->isClearable(),
            'isInteractive' => $this->isInteractive(),
            'valueTextTemplate' => trans_choice('filament-rating::rating.value_of_max', $this->getStars(), ['value' => ':value', 'max' => $this->getStars()]),
            'notRatedText' => __('filament-rating::rating.not_rated'),
            'labels' => (object) $this->getLabels(),
            'labelledTemplate' => __('filament-rating::rating.labelled', ['label' => ':label', 'value' => ':value']),
            'colorThresholds' => $colorThresholds,
            'baseColor' => $colorThresholds ? $this->getBaseColorVariables() : [],
        ];
    }
}
