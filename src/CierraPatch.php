<?php

namespace Erenilhan\CierraPatch;

use Erenilhan\CierraPatch\Services\PatchDBService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class CierraPatch
{
    protected Filesystem $files;
    protected PatchDBService $patchDBService;

    public function __construct(Filesystem $files, PatchDBService $patchDBService)
    {
        $this->files = $files;
        $this->patchDBService = $patchDBService;
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

    /**
     * @throws FileNotFoundException
     */
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
            })
            ->values()
            ->all();
    }
}
