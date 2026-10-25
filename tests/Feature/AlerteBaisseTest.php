<?php

namespace Tests\Feature;

use App\Mail\AlertePerformance;
use App\Models\Alerte;
use App\Models\Mesure;
use App\Models\Page;
use App\Models\Site;
use App\Models\User;
use App\Services\DetecteurBaisse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AlerteBaisseTest extends TestCase
{
    use RefreshDatabase;

    private function page(string $plan = 'solo'): Page
    {
        $user = User::factory()->create(['plan' => $plan]);
        $site = Site::create(['user_id' => $user->id, 'nom' => 'Projet P', 'url' => 'https://projectp.fr/']);

        return Page::create(['user_id' => $user->id, 'site_id' => $site->id, 'url' => 'https://projectp.fr/']);
    }

    private function mesure(Page $page, int $performance, array $extra = [], int $joursAvant = 1): Mesure
    {
        return Mesure::create(array_merge([
            'page_id' => $page->id,
            'strategie' => 'mobile',
            'performance' => $performance,
            'accessibilite' => 100,
            'bonnes_pratiques' => 100,
            'seo' => 100,
            'lcp_ms' => 2000,
            'cls' => 0.01,
            'inp_ms' => null,
            'conseils' => [['titre' => 'Compresser les images', 'gain_ms' => 1200]],
            'mesuree_le' => now()->subDays($joursAvant),
        ], $extra));
    }

    public function test_une_baisse_de_six_points_declenche_une_alerte_et_un_mail(): void
    {
        Mail::fake();
        $page = $this->page();
        $this->mesure($page, 94);
        $this->mesure($page, 94, [], 2);

        $alerte = DetecteurBaisse::pour($this->mesure($page, 88, [], 0));

        $this->assertNotNull($alerte);
        $this->assertSame(94, $alerte->performance_avant);
        $this->assertSame(88, $alerte->performance_apres);
        Mail::assertQueued(AlertePerformance::class);
    }

    public function test_une_baisse_de_deux_points_ne_declenche_rien(): void
    {
        $page = $this->page();
        $this->mesure($page, 94);

        $this->assertNull(DetecteurBaisse::pour($this->mesure($page, 92, [], 0)));
    }

    public function test_une_premiere_mesure_sans_historique_ne_declenche_rien(): void
    {
        $page = $this->page();

        $this->assertNull(DetecteurBaisse::pour($this->mesure($page, 40, [], 0)));
    }

    public function test_un_seul_mail_d_alerte_par_page_et_par_jour(): void
    {
        Mail::fake();
        $page = $this->page();
        $this->mesure($page, 94);

        DetecteurBaisse::pour($this->mesure($page, 80, [], 0));
        DetecteurBaisse::pour($this->mesure($page, 70, [], 0));

        $this->assertSame(1, Alerte::count());
        Mail::assertQueuedCount(1);
    }

    public function test_un_lcp_qui_passe_de_bon_a_mauvais_declenche_une_alerte_meme_sans_baisse_de_note(): void
    {
        $page = $this->page();
        $this->mesure($page, 94, ['lcp_ms' => 2000]);

        $alerte = DetecteurBaisse::pour($this->mesure($page, 94, ['lcp_ms' => 4500], 0));

        $this->assertNotNull($alerte);
        $this->assertStringContainsString('LCP', $alerte->raison);
    }

    public function test_le_plan_gratuit_ne_recoit_pas_de_conseil_si_la_note_reste_au_dessus_de_cinquante(): void
    {
        $page = $this->page('gratuit');
        $this->mesure($page, 90);

        $alerte = DetecteurBaisse::pour($this->mesure($page, 80, [], 0));

        $this->assertSame([], $alerte->conseils);
    }

    public function test_le_plan_gratuit_recoit_trois_conseils_si_la_note_passe_sous_cinquante(): void
    {
        $page = $this->page('gratuit');
        $this->mesure($page, 80);
        $conseils = collect(range(1, 6))->map(fn ($i) => ['titre' => "Conseil $i", 'gain_ms' => 1000 - $i])->all();

        $alerte = DetecteurBaisse::pour($this->mesure($page, 40, ['conseils' => $conseils], 0));

        $this->assertCount(3, $alerte->conseils);
    }

    public function test_le_plan_solo_recoit_jusqu_a_dix_conseils(): void
    {
        $page = $this->page('solo');
        $this->mesure($page, 90);
        $conseils = collect(range(1, 12))->map(fn ($i) => ['titre' => "Conseil $i", 'gain_ms' => 1000 - $i])->all();

        $alerte = DetecteurBaisse::pour($this->mesure($page, 80, ['conseils' => $conseils], 0));

        $this->assertCount(10, $alerte->conseils);
    }
}
