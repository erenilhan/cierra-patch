<?php

namespace Erenilhan\CierraPatch\Commands;

use Illuminate\Console\Command;

class CierraPatchCommand extends Command
{
    public $signature = 'cierra-patch';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
