<?php

namespace Erenilhan\CierraPatch\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakePatch extends Command
{
    protected $signature = 'cierra:make-patch {name : The name of the patch}';
    protected $description = 'Generate a new patch file';

    public function handle(): void
    {
        $stubPath = base_path('stubs/patch.stub');

        if (!File::exists($stubPath)) {
            $this->error('Patch stub file not found!');
            return;
        }

        $name = $this->argument('name');
        $patchName = $this->generatePatchName($name);
        $patchPath = $this->resolvePatchPath($patchName);

        if (File::exists($patchPath)) {
            $this->error('Patch already exists!');
            return;
        }

        $stub = File::get($stubPath);
        $stub = str_replace('{{className}}', $name, $stub);

        File::ensureDirectoryExists(database_path('patches'));
        File::put($patchPath, $stub);

        DB::table('cierra_patches')->insert([
            'name' => $patchName,
            'ran' => false,
        ]);

        $this->info('Patch created successfully!');
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
}
