<?php

namespace Tests\Feature;

use App\Exceptions\PageSpeedException;
use App\Jobs\MesurerPage;
use App\Models\Mesure;
use App\Models\Page;
use App\Models\User;
use App\Services\PageSpeedClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MesurerPageTest extends TestCase
{
    use RefreshDatabase;

    private function rapport(int $performance): array
    {
        $rapport = json_decode(file_get_contents(__DIR__.'/../Fixtures/pagespeed-mobile.json'), true);
        $rapport['lighthouseResult']['categories']['performance']['score'] = $performance / 100;

        return $rapport;
    }

    private function page(): Page
    {
        return Page::create([
            'user_id' => User::factory()->create()->id,
            'url' => 'https://projectp.fr/',
        ]);
    }

    public function test_la_mesure_est_enregistree_pour_les_deux_appareils(): void
    {
        Http::fake(['*' => Http::response($this->rapport(94), 200)]);

        (new MesurerPage($this->page()))->handle(new PageSpeedClient('cle-de-test'));

        $this->assertDatabaseCount('mesures', 2);
        $this->assertSame(['desktop', 'mobile'], Mesure::orderBy('strategie')->pluck('strategie')->all());
        $this->assertSame(94, Mesure::where('strategie', 'mobile')->value('performance'));
    }

    public function test_la_note_retenue_est_la_mediane_de_trois_mesures(): void
    {
        Http::fake([
            '*' => Http::sequence()
                ->push($this->rapport(60), 200)
                ->push($this->rapport(94), 200)
                ->push($this->rapport(96), 200)
                ->whenEmpty(Http::response($this->rapport(94), 200)),
        ]);

        (new MesurerPage($this->page()))->handle(new PageSpeedClient('cle-de-test'));

        $this->assertSame(94, Mesure::where('strategie', 'mobile')->value('performance'));
    }

    public function test_une_erreur_de_l_api_est_relancee_sans_rien_enregistrer(): void
    {
        Http::fake(['*' => Http::response(['error' => 'quota'], 429)]);

        $this->expectException(PageSpeedException::class);

        try {
            (new MesurerPage($this->page()))->handle(new PageSpeedClient('cle-de-test'));
        } finally {
            $this->assertDatabaseCount('mesures', 0);
        }
    }

    public function test_la_tache_a_trois_essais_avec_attente(): void
    {
        $job = new MesurerPage($this->page());

        $this->assertSame(3, $job->tries);
        $this->assertSame([30, 120], $job->backoff);
        $this->assertSame(120, $job->timeout);
    }
}
