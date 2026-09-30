# packstub/filament-rating

Free (MIT) Filament v4 + v5 plugin: star rating form field, table column, infolist entry and average summarizer; drop-in for `mokhosh/filament-rating` v2.

## Commands

```bash
composer test               # Pest
composer test:filter <name>
composer lint               # Pint
composer analyse            # Larastan (level 6)
composer refactor           # Rector
```

## Layout

- `src/Concerns/` shared options (stars, colors, size, icons, theme, display); `Support/RatingColor` turns a color into CSS variables.
- `src/compat/mokhosh.php` lazy class aliases for the mokhosh namespace; keep them in sync with any public class rename.
- `resources/views/` Blade views (field, column, entry, summary, partials); `resources/dist/` hand-written CSS and the Alpine component (no build step).
- `docs/` customer docs synced to packstub.dev.

## Conventions

- One codebase for Filament 4.0+ and 5.x: only use Filament APIs that exist in 4.0.0 (for example `Illuminate\View\ComponentAttributeBag`, not Filament's), and check `--prefer-lowest` on Filament 4.0.0 before a release.
- Testbench caches compiled views across installs: clear `vendor/orchestra/testbench-core/laravel/storage/framework/views` after switching Filament versions.
- Every change needs a test; keep `CHANGELOG.md` current.
