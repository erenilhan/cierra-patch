<?php

namespace Erenilhan\CierraPatch\Commands;

use Erenilhan\CierraPatch\CierraPatch;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakePatch extends Command
{
    protected $signature = 'cierra:make-patch {name? : The name of the patch}}';

    protected $description = 'Generate a new patch file';

    protected CierraPatch $cierraPatch;

    protected Filesystem $filesystem;

    public function __construct(CierraPatch $cierraPatch, Filesystem $filesystem)
    {
        parent::__construct();

        $this->cierraPatch = $cierraPatch;
        $this->filesystem = $filesystem;
    }

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $name = $this->argument('name');

        if (!$name) {
            $name = $this->ask('Please enter the name of the patch');
        }

        $file = $this->create($name, $this->cierraPatch->getPatchPath());

        $this->line("<info>Created Patch:</info>" . $file);

        $this->info('Patch created successfully!');
    }

    /**
     * @throws FileNotFoundException
     */
    protected function create($name, $path): bool|string
    {
        $stubPath = __DIR__ . '/../../stubs/patch.stub';
        if (!$this->filesystem->exists($stubPath)) {
            $this->error('Patch stub file not found!');
            return false;
        }

        $path = $path . '/' . date('Y_m_d_His') . '_' . $name . '.php';

        $patchName = $this->cierraPatch->generatePatchName($name);
        $patchPath = $this->cierraPatch->resolvePatchPath($patchName);

        $stubFile = $this->filesystem->get($stubPath);
        $stubFile = str_replace('{{className}}', $this->cierraPatch->getClassName($name), $stubFile);

        $this->filesystem->ensureDirectoryExists(database_path('patches'));
        $this->filesystem->put($patchPath, $stubFile);

        return $path;
    }

    protected function generatePatchName(string $name): string
    {
        $timestamp = now()->format('Y_m_d_His');

        $name = Str::lower($name);

        return $timestamp . '_' . $name;
    }

    protected function resolvePatchPath(string $name): string
    {
        return database_path('patches') . '/' . $name . '.php';
    }

    /**
     * @throws FileNotFoundException
     */
    protected function writePatch($name): void
    {
        $file = $this->create($name, $this->cierraPatch->getPatchPath());

        $this->line("<info>Created Patch:</info>" . $file);
    }
}
