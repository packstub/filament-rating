<?php

namespace Packstub\FilamentRating;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentRatingServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-rating';

    public static string $viewNamespace = 'filament-rating';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews(static::$viewNamespace)
            ->hasTranslations()
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->startWith(fn (InstallCommand $command) => $command->call('filament:assets'))
                    ->askToStarRepoOnGitHub('packstub/filament-rating');
            });
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('filament-rating', __DIR__.'/../resources/dist/filament-rating.css'),
            AlpineComponent::make('rating', __DIR__.'/../resources/dist/components/rating.js'),
        ], 'packstub/filament-rating');
    }
}
