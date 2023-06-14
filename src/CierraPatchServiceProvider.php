<?php

namespace Erenilhan\CierraPatch;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Erenilhan\CierraPatch\Commands\CierraPatchCommand;

class CierraPatchServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('cierra-patch')
            ->hasConfigFile()
            ->hasMigration('create_cierra_patch_table')
            ->hasCommands([
                Commands\MakePatch::class,
                Commands\RunPatch::class,
                Commands\PatchStatus::class,
            ]);
    }
}
