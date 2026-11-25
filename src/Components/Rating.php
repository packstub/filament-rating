<?php

namespace Packstub\Rating\Components;

use Filament\Forms\Components\Concerns\CanBeReadOnly;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\StateCasts\Contracts\StateCast;
use Filament\Schemas\Components\StateCasts\NumberStateCast;
use Packstub\Rating\Concerns\HasColors;
use Packstub\Rating\Concerns\HasIcons;
use Packstub\Rating\Concerns\HasLabels;
use Packstub\Rating\Concerns\HasRatingInput;
use Packstub\Rating\Concerns\HasSize;
use Packstub\Rating\Concerns\HasStars;
use Packstub\Rating\Concerns\HasTheme;

class Rating extends Field
{
    use CanBeReadOnly;
    use HasColors;
    use HasIcons;
    use HasLabels;
    use HasRatingInput;
    use HasSize;
    use HasStars;
    use HasTheme;

    protected string $view = 'filament-rating::field';

    protected function setUp(): void
    {
        parent::setUp();

        $this->rules(static fn (Rating $component): array => $component->getRatingRules());
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
}
