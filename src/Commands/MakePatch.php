<?php

namespace Erenilhan\CierraPatch\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakePatch extends Command
{
    protected $signature = 'cierra:make:patch {name : The name of the patch}';
    protected $description = 'Generate a new patch file';

    public function handle(): void
    {
        $name = $this->argument('name');
        $patchPath = $this->createPatchFile($name);

        if (!$patchPath) {
            return;
        }

        $this->addToPatchesTable($name);

        $this->info('Patch created successfully!');
    }

    /**
     * Create a new patch file. Return null if the file already exists.
     * @param string $name
     * @return string|null
     */
    protected function createPatchFile(string $name): ?string
    {
        $stubPath = base_path('stubs/patch.stub');

        if (!File::exists($stubPath)) {
            $this->error('Patch stub file not found!');
            return null;
        }

        $name = $this->generatePatchName($name);
        $patchPath = $this->resolvePatchPath($name);

        if (File::exists($patchPath)) {
            $this->error('Patch already exists!');
            return null;
        }

        $stub = File::get($stubPath);
        $stub = str_replace('{{className}}', $name, $stub);

        File::ensureDirectoryExists(database_path('patches'));
        File::put($patchPath, $stub);

        return $patchPath;
    }

    /**
     * Generate a patch name with timestamp prefix.
     * @param string $name
     * @return string
     */
    protected function generatePatchName(string $name): string
    {
        $timestamp = now()->format('Y_m_d_His');
        $name = Str::lower($name);

        return $timestamp . '_' . $name;
    }

    /**
     * Resolve the patch path with the given name.
     * @param string $name
     * @return string
     */
    protected function resolvePatchPath(string $name): string
    {
        return database_path('patches') . '/' . $name . '.php';
    }

    /**
     * Add the patch to the patches table.
     * @param string $name
     * @return void
     */
    protected function addToPatchesTable(string $name): void
    {
        DB::table('cierra_patches')->insert([
            'name' => $name,
            'ran' => false,
        ]);
    }
}
