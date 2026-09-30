<?php

namespace Packstub\FilamentRating\Tests\Fixtures;

use Closure;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * A standalone Livewire form outside any panel, the way the TQM app renders its feedback form.
 */
class RatingForm extends Component implements HasForms
{
    use InteractsWithForms;

    public static ?Closure $components = null;

    /**
     * @var array<string, mixed>
     */
    public ?array $data = [];

    /**
     * @var array<string, mixed>
     */
    public ?array $saved = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components((static::$components)())
            ->statePath('data');
    }

    public function save(): void
    {
        $this->saved = $this->form->getState();
    }

    public function render(): View
    {
        return view('rating-form');
    }
}
