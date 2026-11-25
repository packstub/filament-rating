<?php

namespace Packstub\Rating\Columns;

use Filament\Tables\Columns\Column;
use Packstub\Rating\Concerns\DisplaysRating;
use Packstub\Rating\Concerns\HasColors;
use Packstub\Rating\Concerns\HasIcons;
use Packstub\Rating\Concerns\HasLabels;
use Packstub\Rating\Concerns\HasSize;
use Packstub\Rating\Concerns\HasStars;
use Packstub\Rating\Concerns\HasTheme;

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
