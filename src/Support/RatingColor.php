<?php

namespace Packstub\FilamentRating\Support;

use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Str;

/**
 * Turns a color option into CSS custom properties, so the stars need no Tailwind classes from the app's theme.
 */
final class RatingColor
{
    /**
     * @var array<int>
     */
    public const SHADES = [300, 400, 500, 600];

    /**
     * @param  string | array<int | string, string>  $color
     */
    public static function cssVariables(string|array $color, string $prefix): string
    {
        $palette = self::palette($color);

        $css = '';

        foreach (self::SHADES as $shade) {
            $css .= "--{$prefix}-{$shade}:{$palette[$shade]};";
        }

        return $css;
    }

    /**
     * @param  string | array<int | string, string>  $color
     * @return array<int, string>
     */
    public static function palette(string|array $color): array
    {
        if (is_array($color)) {
            return self::fill($color);
        }

        $color = trim($color);

        if (str_starts_with($color, '#') || str_starts_with($color, 'rgb')) {
            return self::fill(Color::hex($color));
        }

        if (preg_match('/^(oklch|hsl|hwb|lab|lch|color|var)\(/i', $color)) {
            return array_fill_keys(self::SHADES, $color);
        }

        // Registered Filament colors (primary, danger, gray, custom ones) are CSS variables on :root.
        if (array_key_exists($color, FilamentColor::getColors())) {
            return array_combine(self::SHADES, array_map(fn (int $shade): string => "var(--{$color}-{$shade})", self::SHADES));
        }

        // Any Tailwind palette name (amber, yellow, rose ...) works without being registered.
        $constant = Color::class.'::'.Str::studly($color);

        if (defined($constant)) {
            return self::fill(constant($constant));
        }

        return array_combine(self::SHADES, array_map(fn (int $shade): string => "var(--{$color}-{$shade}, var(--primary-{$shade}))", self::SHADES));
    }

    /**
     * @param  array<int | string, string>  $palette
     * @return array<int, string>
     */
    private static function fill(array $palette): array
    {
        $fallback = $palette[500] ?? reset($palette) ?: 'currentColor';

        return array_combine(self::SHADES, array_map(fn (int $shade): string => $palette[$shade] ?? $fallback, self::SHADES));
    }
}
