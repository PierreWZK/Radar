<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexationTest extends TestCase
{
    use RefreshDatabase;

    public function test_l_accueil_declare_son_adresse_canonique_et_ses_donnees_structurees(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.rtrim(config('app.url'), '/').'/">', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('"@type":"WebApplication"', false)
            ->assertSee('https://projectp.fr/', false)
            ->assertDontSee('noindex', false);
    }

    public function test_les_pages_publiques_ont_un_apercu_1200_sur_630(): void
    {
        $this->get('/exemple')
            ->assertOk()
            ->assertSee('property="og:image"', false)
            ->assertSee('property="og:image:width" content="1200"', false)
            ->assertSee('property="og:image:height" content="630"', false)
            ->assertSee('name="twitter:card" content="summary_large_image"', false);
    }

    public function test_les_pages_de_connexion_et_l_espace_connecte_ne_sont_pas_indexees(): void
    {
        $this->get('/login')->assertSee('noindex, nofollow', false);
        $this->actingAs(User::factory()->create())->get('/dashboard')->assertSee('noindex, nofollow', false);
    }

    public function test_robots_et_sitemap_sont_en_place(): void
    {
        $robots = file_get_contents(public_path('robots.txt'));
        $this->assertStringContainsString('Disallow: /dashboard', $robots);
        $this->assertStringContainsString('Sitemap: https://radar.projectp.fr/sitemap.xml', $robots);

        $sitemap = file_get_contents(public_path('sitemap.xml'));
        $this->assertStringContainsString('<loc>https://radar.projectp.fr/</loc>', $sitemap);
        $this->assertStringContainsString('<loc>https://radar.projectp.fr/exemple</loc>', $sitemap);
    }

    public function test_l_image_d_apercu_et_les_icones_existent(): void
    {
        $this->assertFileExists(public_path('images/og-radar.png'));
        $this->assertFileExists(public_path('apple-touch-icon.png'));
        $this->assertFileExists(public_path('favicon.svg'));
    }
}
