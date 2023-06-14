<?php

namespace Erenilhan\CierraPatch\Commands;

use App\Models\Patch;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakePatch extends Command
{
    protected $signature = 'cierra:make:patch {name : The name of the patch}';

    protected $description = 'Generate a new patch file';

    public function handle(): void
    {
        if(!File::exists(base_path('stubs/patch.stub'))) {
            $this->error('Patch stub file not found!');

            return;
        }
        $this->info('Creating patch... : ' . $this->argument('name'));

        $name = $this->argument('name');
        $stub = File::get(base_path('stubs/patch.stub'));
        $name = date('Y_m_d_His') . '_' . Str::lower($name);

        $patchPath = database_path('patches') . '/' . $name . '.php';
        //check if file exists already then throw error
        if (File::exists($patchPath)) {
            $this->error('Patch already exists!');

            return;
        }
        $stub = $this->changeStub($stub, $this->resolve($name));

        File::ensureDirectoryExists(database_path('patches'));
        File::put($patchPath, $stub);

        Patch::create([
            'name' => $name,
            'ran' => false,
            'batch' => $this->getNextBatchNumber(),
        ]);

        $this->info('Patch created successfully!');
    }

//getNextBatchNumber() method

    protected function changeStub($stub, $name): string
    {
        return str_replace('{{className}}', $name, $stub);
    }

    public function resolve(string $file): string
    {
        return Str::studly(implode('_', array_slice(explode('_', $file), 4)));
    }

    protected function getNextBatchNumber(): int
    {
        $lastBatch = Patch::orderBy('batch', 'desc')->first();

        return $lastBatch ? $lastBatch->batch + 1 : 1;
    }
}
