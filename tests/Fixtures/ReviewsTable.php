<?php

namespace Packstub\FilamentRating\Tests\Fixtures;

use Closure;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ReviewsTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public static ?Closure $columns = null;

    public static ?Closure $filters = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(Review::query())
            ->columns((static::$columns)())
            ->filters(static::$filters ? (static::$filters)() : []);
    }

    public function render(): View
    {
        return view('reviews-table');
    }
}
