<?php

namespace Erenilhan\CierraPatch\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PatchDBService
{
    public function getRanPatches(): array
    {
        return DB::table('cierra_patches')
            ->get(['name'])
            ->pluck('name')
            ->toArray();
    }

    public function storePatch($patchName): void
    {
        DB::table('cierra_patches')->insert([
            'name' => $patchName,
            'created_at' => now(),
        ]);
    }
}
