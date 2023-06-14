<?php

namespace Erenilhan\CierraPatch\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class PatchStatus extends Command
{
    protected $signature = 'cierra:patch:status';

    protected $description = 'Show patch status';

    public function handle(): void
    {
        $patches = DB::table('cierra_patches')
            ->get();

        $this->table(['ID', 'Name of Patch', 'Is Ran?'], $patches->map(function ($patch) {
            return [
                $patch->id,
                $patch->name,
                $patch->ran ? '<fg=green>YES</>' : '<fg=red>No</>',
            ];
        }));
    }
}
