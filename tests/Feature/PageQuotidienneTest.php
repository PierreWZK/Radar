<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Site;
use App\Models\User;
use App\Services\Planification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PageQuotidienneTest extends TestCase
{
    use RefreshDatabase;

    public function test_une_page_choisie_d_un_site_entier_est_mesuree_chaque_jour(): void
    {
        $user = User::factory()->create(['plan' => 'solo']);
        $site = Site::create(['user_id' => $user->id, 'nom' => 'Grand site', 'url' => 'https://grand.fr/', 'site_entier' => true]);
        $page = Page::create(['user_id' => $user->id, 'site_id' => $site->id, 'url' => 'https://grand.fr/tarifs', 'quotidienne' => true]);

        foreach (range(0, 6) as $decalage) {
            $ligne = Planification::pour(Carbon::parse('2026-10-26')->addDays($decalage))->firstWhere('page.id', $page->id);
            $this->assertSame(['mobile', 'desktop'], $ligne['strategies'] ?? []);
        }
    }

    public function test_le_proprietaire_regle_la_mesure_quotidienne_d_une_page(): void
    {
        $user = User::factory()->create(['plan' => 'solo']);
        $site = Site::create(['user_id' => $user->id, 'nom' => 'Grand site', 'url' => 'https://grand.fr/', 'site_entier' => true]);
        $page = Page::create(['user_id' => $user->id, 'site_id' => $site->id, 'url' => 'https://grand.fr/tarifs']);

        $this->actingAs($user)->patch(route('pages.update', $page), ['quotidienne' => '1'])
            ->assertRedirect(route('sites.show', $site));
        $this->assertTrue($page->fresh()->quotidienne);

        $this->actingAs($user)->patch(route('pages.update', $page), ['quotidienne' => '0']);
        $this->assertFalse($page->fresh()->quotidienne);
    }

    public function test_un_autre_compte_ne_peut_pas_modifier_la_page(): void
    {
        $proprietaire = User::factory()->create(['plan' => 'solo']);
        $site = Site::create(['user_id' => $proprietaire->id, 'nom' => 'Grand site', 'url' => 'https://grand.fr/', 'site_entier' => true]);
        $page = Page::create(['user_id' => $proprietaire->id, 'site_id' => $site->id, 'url' => 'https://grand.fr/tarifs']);

        $this->actingAs(User::factory()->create())->patch(route('pages.update', $page), ['quotidienne' => '1'])->assertNotFound();
        $this->assertFalse($page->fresh()->quotidienne);
    }
}
