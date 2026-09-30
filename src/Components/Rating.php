<?php

namespace Packstub\FilamentRating\Components;

use Closure;
use Filament\Forms\Components\Concerns\CanBeReadOnly;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;
use Filament\Schemas\Components\StateCasts\NumberStateCast;
use Packstub\FilamentRating\Concerns\HasColors;
use Packstub\FilamentRating\Concerns\HasIcons;
use Packstub\FilamentRating\Concerns\HasSize;
use Packstub\FilamentRating\Concerns\HasStars;
use Packstub\FilamentRating\Concerns\HasTheme;

class Rating extends Field
{
    use CanBeReadOnly;
    use HasColors;
    use HasIcons;
    use HasSize;
    use HasStars;
    use HasTheme;

    protected string $view = 'filament-rating::field';

    protected bool|Closure $isClearable = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule('numeric');
        $this->rule(static fn (Rating $component): string => 'min:'.$component->getMinValue());
        $this->rule(static fn (Rating $component): string => 'max:'.$component->getMaxValue());
        $this->rule(static fn (Rating $component): string => $component->allowsHalf() ? 'multiple_of:0.5' : 'integer');
    }

    /**
     * Clicking the selected star again (or pressing Delete) empties the field.
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

    public function isInteractive(): bool
    {
        return (! $this->isDisabled()) && (! $this->isReadOnly());
    }

    /**
     * @return array<StateCast>
     */
    public function getDefaultStateCasts(): array
    {
        return [
            ...parent::getDefaultStateCasts(),
            app(NumberStateCast::class, ['isNullable' => true, 'isInteger' => ! $this->allowsHalf()]),
        ];
    }

    public function normalizeRatingState(mixed $state): ?float
    {
        if (blank($state) || (! is_numeric($state))) {
            return null;
        }

        return max(0, min((float) $state, $this->getStars()));
    }
}
