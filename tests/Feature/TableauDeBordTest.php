<?php

namespace Tests\Feature;

use App\Models\Mesure;
use App\Models\Page;
use App\Models\Site;
use App\Models\User;
use App\Services\Badge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TableauDeBordTest extends TestCase
{
    use RefreshDatabase;

    private function page(User $user, string $url = 'https://projectp.fr/'): Page
    {
        $site = Site::create(['user_id' => $user->id, 'nom' => 'Projet P', 'url' => 'https://projectp.fr/']);

        return Page::create(['user_id' => $user->id, 'site_id' => $site->id, 'url' => $url]);
    }

    private function mesure(Page $page, int $performance): Mesure
    {
        return Mesure::create([
            'page_id' => $page->id,
            'strategie' => 'mobile',
            'performance' => $performance,
            'accessibilite' => 100,
            'bonnes_pratiques' => 96,
            'seo' => 92,
            'lcp_ms' => 2900,
            'cls' => 0.02,
            'inp_ms' => null,
            'conseils' => [],
            'mesuree_le' => now(),
        ]);
    }

    public function test_le_badge_est_vert_a_partir_de_quatre_vingt_dix(): void
    {
        $page = $this->page(User::factory()->create());
        $this->mesure($page, 94);

        $this->get("/badge/{$page->id}.svg")
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml')
            ->assertSee('94')
            ->assertSee(Badge::VERT);
    }

    public function test_le_badge_est_orange_entre_cinquante_et_quatre_vingt_neuf(): void
    {
        $this->assertSame(Badge::ORANGE, Badge::couleur(60));
    }

    public function test_le_badge_est_rouge_en_dessous_de_cinquante(): void
    {
        $this->assertSame(Badge::ROUGE, Badge::couleur(30));
    }

    public function test_le_badge_sans_mesure_affiche_un_tiret(): void
    {
        $page = $this->page(User::factory()->create());

        $this->get("/badge/{$page->id}.svg")->assertOk()->assertSee('—');
    }

    public function test_le_tableau_de_bord_ne_montre_que_les_pages_de_l_utilisateur(): void
    {
        $moi = User::factory()->create();
        $autre = User::factory()->create();
        $this->page($moi, 'https://mon-site.fr/');
        $this->page($autre, 'https://secret-client.fr/');

        $this->actingAs($moi)->get('/dashboard')
            ->assertOk()
            ->assertSee('https://mon-site.fr/')
            ->assertDontSee('https://secret-client.fr/');
    }

    public function test_la_fiche_d_une_page_d_un_autre_compte_est_introuvable(): void
    {
        $autre = User::factory()->create();
        $page = $this->page($autre);

        $this->actingAs(User::factory()->create())->get("/pages/{$page->id}")->assertNotFound();
    }

    public function test_les_core_web_vitals_ne_sont_pas_montres_au_plan_gratuit(): void
    {
        $user = User::factory()->create(['plan' => 'gratuit']);
        $page = $this->page($user);
        $this->mesure($page, 94);

        $this->actingAs($user)->get("/pages/{$page->id}")
            ->assertOk()
            ->assertDontSee('Core Web Vitals');
    }

    public function test_les_core_web_vitals_sont_montres_au_plan_solo(): void
    {
        $user = User::factory()->create(['plan' => 'solo']);
        $page = $this->page($user);
        $this->mesure($page, 94);

        $this->actingAs($user)->get("/pages/{$page->id}")
            ->assertOk()
            ->assertSee('Core Web Vitals');
    }
}
