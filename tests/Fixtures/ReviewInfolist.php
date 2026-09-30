<?php

namespace Packstub\FilamentRating\Tests\Fixtures;

use Closure;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ReviewInfolist extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public static ?Closure $components = null;

    public Review $review;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->review)
            ->components((static::$components)());
    }

    public function render(): View
    {
        return view('review-infolist');
    }
}
