<?php

namespace Packstub\FilamentRating\Columns;

use Filament\Tables\Columns\Concerns\CanBeValidated;
use Filament\Tables\Columns\Concerns\CanUpdateState;
use Filament\Tables\Columns\Contracts\Editable;
use Packstub\FilamentRating\Concerns\HasRatingInput;

/**
 * Rate a record straight from the table: a click saves the new rating, like Filament's ToggleColumn.
 *
 * Like Filament's own editable columns, it saves without checking model policies:
 * use `disabled()` with a closure to decide who can change a rating.
 */
class RatingInputColumn extends RatingColumn implements Editable
{
    use CanBeValidated {
        getRules as getBaseRules;
    }
    use CanUpdateState {
        updateState as saveState;
    }
    use HasRatingInput;

    protected string $view = 'filament-rating::input-column';

    protected function setUp(): void
    {
        parent::setUp();

        $this->disabledClick();
    }

    public function isInteractive(): bool
    {
        return ! $this->isDisabled();
    }

    /**
     * @return array<array-key>
     */
    public function getRules(): array
    {
        return [...$this->getBaseRules(), ...$this->getRatingRules()];
    }

    public function updateState(mixed $state): mixed
    {
        if (is_numeric($state)) {
            $state = $this->allowsHalf() ? (float) $state : (int) $state;
        }

        return $this->saveState($state);
    }
}
