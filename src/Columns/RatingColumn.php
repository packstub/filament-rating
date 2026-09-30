<?php

namespace Packstub\FilamentRating\Columns;

use Filament\Tables\Columns\Column;
use Packstub\FilamentRating\Concerns\DisplaysRating;
use Packstub\FilamentRating\Concerns\HasColors;
use Packstub\FilamentRating\Concerns\HasIcons;
use Packstub\FilamentRating\Concerns\HasLabels;
use Packstub\FilamentRating\Concerns\HasSize;
use Packstub\FilamentRating\Concerns\HasStars;
use Packstub\FilamentRating\Concerns\HasTheme;

class RatingColumn extends Column
{
    use DisplaysRating;
    use HasColors;
    use HasIcons;
    use HasLabels;
    use HasSize;
    use HasStars;
    use HasTheme;

    protected string $view = 'filament-rating::column';
}
