<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    private function utilisateur(string $plan): User
    {
        return User::factory()->create(['plan' => $plan]);
    }

    public function test_le_plan_gratuit_ne_peut_creer_qu_un_site(): void
    {
        $user = $this->utilisateur('gratuit');
        Site::create(['user_id' => $user->id, 'nom' => 'Premier', 'url' => 'https://a.fr/']);

        $this->actingAs($user)
            ->post('/sites', ['nom' => 'Second', 'url' => 'https://b.fr/'])
            ->assertSessionHasErrors('nom');

        $this->assertSame(1, Site::count());
    }

    public function test_le_plan_gratuit_ne_peut_suivre_qu_une_page_par_site(): void
    {
        $user = $this->utilisateur('gratuit');
        $site = Site::create(['user_id' => $user->id, 'nom' => 'Site', 'url' => 'https://a.fr/']);
        Page::create(['user_id' => $user->id, 'site_id' => $site->id, 'url' => 'https://a.fr/']);

        $this->actingAs($user)
            ->post("/sites/{$site->id}/pages", ['url' => 'https://a.fr/contact'])
            ->assertSessionHasErrors('url');

        $this->assertSame(1, Page::count());
    }

    public function test_le_plan_solo_limite_a_cinq_pages_par_site(): void
    {
        $user = $this->utilisateur('solo');
        $site = Site::create(['user_id' => $user->id, 'nom' => 'Site', 'url' => 'https://a.fr/']);
        for ($i = 0; $i < 5; $i++) {
            Page::create(['user_id' => $user->id, 'site_id' => $site->id, 'url' => "https://a.fr/$i"]);
        }

        $this->actingAs($user)
            ->post("/sites/{$site->id}/pages", ['url' => 'https://a.fr/6'])
            ->assertSessionHasErrors('url');

        $this->assertSame(5, $site->pages()->count());
    }

    public function test_l_option_site_entier_est_refusee_au_plan_gratuit(): void
    {
        $user = $this->utilisateur('gratuit');

        $this->actingAs($user)
            ->post('/sites', ['nom' => 'Site', 'url' => 'https://a.fr/', 'site_entier' => '1'])
            ->assertSessionHasErrors('site_entier');

        $this->assertSame(0, Site::count());
    }

    public function test_l_import_du_sitemap_remplit_les_pages_jusqu_a_deux_cents(): void
    {
        $user = $this->utilisateur('solo');
        $site = Site::create(['user_id' => $user->id, 'nom' => 'Site', 'url' => 'https://a.fr', 'site_entier' => true]);
        $urls = collect(range(1, 250))->map(fn ($i) => "<url><loc>https://a.fr/page-$i</loc></url>")->implode('');
        Http::fake(['https://a.fr/sitemap.xml' => Http::response("<urlset>$urls</urlset>", 200)]);

        $this->actingAs($user)->post("/sites/{$site->id}/sitemap")->assertRedirect();

        $this->assertSame(200, $site->pages()->count());
    }

    public function test_un_site_d_un_autre_compte_est_introuvable(): void
    {
        $autre = $this->utilisateur('agence');
        $site = Site::create(['user_id' => $autre->id, 'nom' => 'Secret', 'url' => 'https://a.fr/']);

        $this->actingAs($this->utilisateur('agence'))
            ->get("/sites/{$site->id}")
            ->assertNotFound();
    }
}
