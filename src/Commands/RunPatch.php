<?php

namespace Erenilhan\CierraPatch\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RunPatch extends Command
{
    protected $signature = 'cierra:patch';

    protected $description = 'Run pending patches';

    public function handle(): void
    {
        $this->info('Running patches...');

        $patches = DB::table('cierra_patches')
            ->where('ran', false)
            ->get();

        foreach ($patches as $patch) {
            $this->line('Running patch: ' . $patch->name);

            require_once(database_path('patches/' . $patch->name . '.php'));

            $className = $this->getClassNameFromFileName($patch->name);

            $class = new $className();
            $class->run();

            DB::table('cierra_patches')
                ->where('id', $patch->id)
                ->update(['ran' => true]);
        }

        $this->info('All patches have been executed.');
    }

    private function getClassNameFromFileName($fileName): array|string
    {
        return Str::studly(implode('_', array_slice(explode('_', $fileName), 4)));
    }
}
