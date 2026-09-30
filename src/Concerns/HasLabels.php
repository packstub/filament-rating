<?php

namespace Packstub\FilamentRating\Concerns;

use Closure;

trait HasLabels
{
    /**
     * @var array<int | string, string> | Closure | null
     */
    protected array|Closure|null $labels = null;

    /**
     * A word per rating, e.g. `[1 => 'Poor', 2 => 'Fair', 3 => 'Good', 4 => 'Very good', 5 => 'Excellent']`.
     * Keys can be half values (`'3.5'`) when half stars are allowed.
     *
     * @param  array<int | string, string> | Closure | null  $labels
     */
    public function labels(array|Closure|null $labels): static
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @return array<string, string> keyed by the value as the field sends it ("4", "3.5")
     */
    public function getLabels(): array
    {
        $labels = [];

        foreach ($this->evaluate($this->labels) ?? $this->getDefaultLabels() as $value => $label) {
            if (is_numeric($value) && filled($label)) {
                $labels[static::labelKey((float) $value)] = (string) $label;
            }
        }

        return $labels;
    }

    /**
     * @return array<int | string, string>
     */
    public function getDefaultLabels(): array
    {
        return [];
    }

    public function hasLabels(): bool
    {
        return filled($this->getLabels());
    }

    /**
     * The label of a value, or of the nearest whole rating for an average (3.7 uses the label of 4).
     */
    public function getLabelFor(float|int|null $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $labels = $this->getLabels();

        return $labels[static::labelKey((float) $value)] ?? $labels[static::labelKey(round((float) $value))] ?? null;
    }

    protected static function labelKey(float $value): string
    {
        return rtrim(rtrim(number_format($value, 1, '.', ''), '0'), '.');
    }
}
