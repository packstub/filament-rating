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

- One codebase for Filament 4.x and 5.x: only use Filament APIs that exist in the lowest 4.x Composer installs (for example `Illuminate\View\ComponentAttributeBag`, not Filament's); CI's `prefer-lowest` job covers it.
- Testbench caches compiled views across installs: clear `vendor/orchestra/testbench-core/laravel/storage/framework/views` after switching Filament versions.
- Every change needs a test and a `CHANGELOG.md` line under `## Unreleased`: one short, plain line on what a user gets or what changed (a class or method name only when the user needs it, never a list of them; no CI, tests or refactors). A release is an optional lead sentence and at most ~6 such bullets; breaking changes and migrations get an `**Upgrading:**` line saying what to do, and a security fix says so. The release workflow puts that section in the GitHub release and appends the merged pull requests under it.
