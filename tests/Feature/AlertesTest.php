<?php

namespace Tests\Feature;

use App\Models\Alerte;
use App\Models\Page;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertesTest extends TestCase
{
    use RefreshDatabase;

    private function alerte(User $user, string $url, string $raison): Alerte
    {
        $site = Site::create(['user_id' => $user->id, 'nom' => 'Site de '.$user->name, 'url' => 'https://'.$url.'/']);
        $page = Page::create(['user_id' => $user->id, 'site_id' => $site->id, 'url' => 'https://'.$url.'/']);

        return Alerte::create([
            'page_id' => $page->id,
            'strategie' => 'mobile',
            'raison' => $raison,
            'performance_avant' => 92,
            'performance_apres' => 84,
            'conseils' => [['titre' => 'Compresser les images', 'gain_ms' => 1200]],
        ]);
    }

    public function test_la_page_alertes_demande_une_connexion(): void
    {
        $this->get('/alertes')->assertRedirect(route('login'));
    }

    public function test_l_utilisateur_ne_voit_que_ses_alertes(): void
    {
        $moi = User::factory()->create();
        $autre = User::factory()->create();
        $this->alerte($moi, 'mon-site.fr', 'Performance en baisse de 8 points');
        $this->alerte($autre, 'secret-client.fr', 'Performance en baisse de 3 points');

        $this->actingAs($moi)
            ->get('/alertes')
            ->assertOk()
            ->assertSee('mon-site.fr')
            ->assertSee('Performance en baisse de 8 points')
            ->assertSee('Compresser les images')
            ->assertDontSee('secret-client.fr');
    }

    public function test_la_page_alertes_affiche_un_etat_vide(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/alertes')
            ->assertOk()
            ->assertSee('Aucune alerte pour le moment.');
    }
}
