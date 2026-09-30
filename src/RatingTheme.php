<?php

namespace Packstub\FilamentRating;

enum RatingTheme
{
    /**
     * Whole stars in the field; exact partial fill (3.7) when displaying.
     */
    case Simple;

    /**
     * Half-star steps: the field picks 0.5 steps (same as `allowHalf()`), display rounds down to the nearest half.
     */
    case HalfStars;
}
