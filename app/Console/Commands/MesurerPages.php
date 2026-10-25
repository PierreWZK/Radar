<?php

namespace App\Console\Commands;

use App\Jobs\MesurerPage;
use App\Services\Planification;
use Illuminate\Console\Command;

class MesurerPages extends Command
{
    protected $signature = 'radar:mesurer';

    protected $description = 'Met en file les mesures PageSpeed du jour, selon l\'offre de chaque page';

    public function handle(): int
    {
        $lignes = Planification::pour(now());

        foreach ($lignes as $ligne) {
            MesurerPage::dispatch($ligne['page'], $ligne['strategies']);
        }

        $this->info($lignes->count().' page(s) mise(s) en file.');

        return self::SUCCESS;
    }
}
