<?php

namespace Erenilhan\CierraPatch\Commands;

use Erenilhan\CierraPatch\CierraPatch;
use Erenilhan\CierraPatch\Services\PatchDBService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;

class PatchStatus extends Command
{
    protected $signature = 'cierra:patch-status';

    protected $description = 'Show the status of all patches';

    protected CierraPatch $cierraPatch;
    protected PatchDBService $patchDBService;

    public function __construct(CierraPatch $cierraPatch, PatchDBService $patchDBService)
    {
        parent::__construct();
        $this->cierraPatch = $cierraPatch;
        $this->patchDBService = $patchDBService;
    }

    public function handle(): void
    {
        $dbPatches = $this->patchDBService->getRanPatches();
        $patchFiles = $this->cierraPatch->getPatchFiles($this->cierraPatch->getPatchPath());

        $this->output->title('Patches Status');
        $this->output->writeln('');

        $table = new Table($this->output);
        $table->setHeaders(['Ran?', 'Patch Name', 'Patch File'])
            ->setRows($this->getPatchStatus($dbPatches, $patchFiles))
            ->setFooterTitle('Total: ' . count($patchFiles) . ', Ran: ' . count($dbPatches) . ', Waiting: ' . (count($patchFiles) - count($dbPatches)))
            ->render();
    }

    protected function getPatchStatus(array $dbPatches, array $patchFiles): array
    {
        return array_map(function ($patchName, $patchFile) use ($dbPatches) {
            $ran = in_array($patchName, $dbPatches);
            return [
                $ran ? '<fg=green>YES</>' : '<fg=red>No</>',
                $patchName,
                $patchFile
            ];
        }, array_keys($patchFiles), $patchFiles);
    }
}
