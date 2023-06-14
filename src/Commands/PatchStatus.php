<?php

namespace Erenilhan\CierraPatch\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\Table;

class PatchStatus extends Command
{
    protected $signature = 'cierra:patch-status';
    protected $description = 'Show the status of all patches';

    public function handle(): void
    {
        $patches = DB::table('cierra_patches')->get();
        $patchRows = $patches->map(function ($patch) {
            return [
                $patch->id,
                $patch->name,
                $patch->ran ? '<fg=green>YES</>' : '<fg=red>No</>',
            ];
        })->toArray();

        $table = new Table($this->output);
        $table->setHeaders(['ID', 'Name of Patch', 'Is Ran?'])
            ->setRows($patchRows)
            ->render();
    }
}
