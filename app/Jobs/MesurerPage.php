<?php

namespace App\Jobs;

use App\Models\Mesure;
use App\Models\Page;
use App\Services\Mediane;
use App\Services\PageSpeedClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;

class MesurerPage implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    /** @var array<int> */
    public array $backoff = [30, 120];

    public function __construct(public Page $page) {}

    public function handle(PageSpeedClient $client): void
    {
        foreach (['mobile', 'desktop'] as $strategie) {
            $mesures = collect(range(1, 3))->map(fn () => $client->mesurer($this->page->url, $strategie));

            Mesure::create([
                'page_id' => $this->page->id,
                'strategie' => $strategie,
                'performance' => $this->mediane($mesures, 'performance'),
                'accessibilite' => $this->mediane($mesures, 'accessibilite'),
                'bonnes_pratiques' => $this->mediane($mesures, 'bonnes_pratiques'),
                'seo' => $this->mediane($mesures, 'seo'),
                'lcp_ms' => $this->mediane($mesures, 'lcp_ms'),
                'cls' => $this->mediane($mesures, 'cls'),
                'inp_ms' => $this->mediane($mesures, 'inp_ms'),
                'mesuree_le' => now(),
            ]);
        }
    }

    private function mediane(Collection $mesures, string $cle): int|float|null
    {
        $valeurs = $mesures->pluck($cle)->filter(fn ($valeur) => $valeur !== null)->values()->all();

        return $valeurs === [] ? null : Mediane::de($valeurs);
    }
}
