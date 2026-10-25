<?php

namespace Tests\Feature;

use App\Jobs\MesurerPage;
use App\Models\Page;
use App\Models\Site;
use App\Models\User;
use App\Services\Planification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PlanificationTest extends TestCase
{
    use RefreshDatabase;

    private function page(string $plan, bool $siteEntier = false, string $url = 'https://projectp.fr/'): Page
    {
        $user = User::factory()->create(['plan' => $plan]);
        $site = Site::create(['user_id' => $user->id, 'nom' => 'Projet P', 'url' => 'https://projectp.fr/', 'site_entier' => $siteEntier]);

        return Page::create(['user_id' => $user->id, 'site_id' => $site->id, 'url' => $url]);
    }

    private function strategies(Page $page, Carbon $jour): array
    {
        $ligne = Planification::pour($jour)->firstWhere('page.id', $page->id);

        return $ligne['strategies'] ?? [];
    }

    public function test_le_gratuit_est_mesure_le_lundi_seulement_et_sur_mobile(): void
    {
        $page = $this->page('gratuit');

        $this->assertSame(['mobile'], $this->strategies($page, Carbon::parse('2026-10-26')));
        $this->assertSame([], $this->strategies($page, Carbon::parse('2026-10-27')));
    }

    public function test_solo_est_mesure_chaque_jour_sur_mobile_et_ordinateur(): void
    {
        $page = $this->page('solo');

        foreach (['2026-10-26', '2026-10-27', '2026-10-31'] as $jour) {
            $this->assertSame(['mobile', 'desktop'], $this->strategies($page, Carbon::parse($jour)));
        }
    }

    public function test_chaque_page_d_un_site_entier_n_est_mesuree_qu_une_fois_par_semaine(): void
    {
        $user = User::factory()->create(['plan' => 'solo']);
        $site = Site::create(['user_id' => $user->id, 'nom' => 'Grand site', 'url' => 'https://grand.fr/', 'site_entier' => true]);
        $pages = collect(range(1, 7))->map(fn (int $i) => Page::create(['user_id' => $user->id, 'site_id' => $site->id, 'url' => "https://grand.fr/page-{$i}"]));

        $passages = [];
        for ($i = 0; $i < 7; $i++) {
            foreach (Planification::pour(Carbon::parse('2026-10-26')->addDays($i)) as $ligne) {
                $passages[$ligne['page']->id] = ($passages[$ligne['page']->id] ?? 0) + 1;
            }
        }

        ksort($passages);
        $this->assertSame($pages->pluck('id')->sort()->values()->all(), array_keys($passages));
        $this->assertSame(array_fill_keys(array_keys($passages), 1), $passages);
    }

    public function test_la_commande_met_en_file_une_mesure_par_page_du_jour(): void
    {
        Queue::fake();
        Carbon::setTestNow(Carbon::parse('2026-10-27'));
        $this->page('solo');
        $this->page('gratuit', false, 'https://projectp.fr/contact');

        $this->artisan('radar:mesurer')->assertSuccessful();

        Queue::assertPushed(MesurerPage::class, 1);
    }

    public function test_les_mesures_quotidiennes_et_hebdomadaires_sont_planifiees(): void
    {
        $commandes = collect(app(\Illuminate\Console\Scheduling\Schedule::class)->events())->map(fn ($evenement) => $evenement->command);

        $this->assertTrue($commandes->contains(fn ($commande) => str_contains($commande, 'radar:mesurer')));
    }
}
