<?php

namespace Packstub\Rating\Entries;

use Filament\Infolists\Components\Entry;
use Packstub\Rating\Concerns\DisplaysRating;
use Packstub\Rating\Concerns\HasColors;
use Packstub\Rating\Concerns\HasIcons;
use Packstub\Rating\Concerns\HasLabels;
use Packstub\Rating\Concerns\HasSize;
use Packstub\Rating\Concerns\HasStars;
use Packstub\Rating\Concerns\HasTheme;

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
