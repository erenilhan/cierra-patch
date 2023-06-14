<?php

namespace Erenilhan\CierraPatch;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CierraPatch
{
    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function getPatchPath(): string
    {
        return database_path('patches');
    }

    public function getPatchFiles($path): array
    {
        $files = [];

        if (Str::endsWith($path, '.php')) {
            $files[] = $path;
        } else {
            $files = array_merge($files, $this->files->glob($path . '/*_*.php'));
        }

        return collect($files)
            ->filter()
            ->values()
            ->keyBy(fn($file) => $this->getPatchName($file))
            ->sortBy(fn($_file, $key) => $key)
            ->all();
    }

    public function getPatchName(string $path): string
    {
        return str_replace('.php', '', basename($path));
    }

    public function getClassName($name): string
    {
        return Str::studly($name);
    }

    public function requireFiles(array $files): void
    {
        foreach ($files as $file) {
            $this->files->requireOnce($file);
        }
    }

    public function resolve(string $file): object
    {
        return new $file;
    }

    public function generatePatchName(string $name): string
    {
        $timestamp = now()->format('Y_m_d_His');

        $name = Str::lower($name);

        return $timestamp . '_' . $name;
    }

    public function resolvePatchPath(string $name): string
    {
        return database_path('patches') . '/' . $name . '.php';
    }

    public function pendingPatches(array $files, array $ran): array
    {
        return collect($files)
            ->reject(function ($file) use ($ran) {
                return in_array($this->getPatchName($file), $ran);
            })->values()->all();
    }

    //Queries
    //TODO : If there will be more than one query, move them to a separate class and inject it here.
    //BUT IT'S NOT NECESSARY FOR NOW. 15.06.2023 - Eren İlhan - erenilhan1@gmailcom
    public function getRanInDB()
    {
        return DB::table('cierra_patches')
            ->get(['name'])
            ->pluck('name')
            ->toArray();
    }
}
