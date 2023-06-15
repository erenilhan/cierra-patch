<?php

namespace Erenilhan\CierraPatch\Commands;

use Erenilhan\CierraPatch\CierraPatch;
use Erenilhan\CierraPatch\Services\PatchDBService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RunPatch extends Command
{
    protected $signature = 'cierra:patch';

    protected $description = 'Run patches from database/patches folder and mark them as ran in the database';

    protected CierraPatch $cierraPatch;
    protected PatchDBService $patchDBService;

    public function __construct(CierraPatch $cierraPatch, PatchDBService $patchDBService)
    {
        parent::__construct();

        $this->patchDBService = $patchDBService;
        $this->cierraPatch = $cierraPatch;
    }

    /**
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->info('Running patches...');

        $files = $this->cierraPatch->getPatchFiles($this->cierraPatch->getPatchPath());
        $patches = $this->cierraPatch->pendingPatches($files, $this->cierraPatch->getRanInDB());

        $this->cierraPatch->requireFiles($patches);

        $this->runPatch($patches);

        $this->info('All patches have been executed.');
    }

    protected function runPatch(array $patches): void
    {
        foreach ($patches as $patch) {
            $patchName = $this->cierraPatch->getPatchName($patch);
            $className = $this->getClassNameFromFileName($patchName);
            $this->cierraPatch->resolve($className)->run();
//
//            DB::table('cierra_patches')->insert([
//                'name' => $patchName,
//                'created_at' => now(),
//            ]);
            $this->patchDBService->storePatch($patchName);

            $this->line('<info>Ran:</info> ' . $patchName);
        }
    }

    protected function getClassNameFromFileName($fileName): array|string
    {
        $name = Str::studly(implode('_', array_slice(explode('_', $fileName), 4)));

        return str_replace('.php', '', $name);
    }
}
