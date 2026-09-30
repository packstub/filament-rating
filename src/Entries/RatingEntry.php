<?php

namespace Packstub\FilamentRating\Entries;

use Filament\Infolists\Components\Entry;
use Packstub\FilamentRating\Concerns\DisplaysRating;
use Packstub\FilamentRating\Concerns\HasColors;
use Packstub\FilamentRating\Concerns\HasIcons;
use Packstub\FilamentRating\Concerns\HasLabels;
use Packstub\FilamentRating\Concerns\HasSize;
use Packstub\FilamentRating\Concerns\HasStars;
use Packstub\FilamentRating\Concerns\HasTheme;

class RatingEntry extends Entry
{
    use DisplaysRating;
    use HasColors;
    use HasIcons;
    use HasLabels;
    use HasSize;
    use HasStars;
    use HasTheme;

    protected string $view = 'filament-rating::entry';
}
