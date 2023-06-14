<?php

namespace Erenilhan\CierraPatch;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Erenilhan\CierraPatch\Commands\CierraPatchCommand;

class CierraPatchServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('cierra-patch')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_cierra-patch_table')
            ->hasCommand(CierraPatchCommand::class);
    }
}
