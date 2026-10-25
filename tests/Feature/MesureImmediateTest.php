<?php

namespace Tests\Feature;

use App\Jobs\MesurerPage;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MesureImmediateTest extends TestCase
{
    use RefreshDatabase;

    private function site(string $plan): Site
    {
        $user = User::factory()->create(['plan' => $plan]);

        return Site::create(['user_id' => $user->id, 'nom' => 'Projet P', 'url' => 'https://projectp.fr/']);
    }

    public function test_une_page_ajoutee_est_mesuree_tout_de_suite_sur_mobile_en_gratuit(): void
    {
        Queue::fake();
        $site = $this->site('gratuit');

        $this->actingAs($site->user)->post(route('sites.pages.store', $site), ['url' => 'https://projectp.fr/'])
            ->assertRedirect(route('sites.show', $site));

        Queue::assertPushed(MesurerPage::class, fn (MesurerPage $job) => $job->page->url === 'https://projectp.fr/' && $job->strategies === ['mobile']);
    }

    public function test_une_page_ajoutee_est_mesuree_sur_mobile_et_ordinateur_en_offre_payante(): void
    {
        Queue::fake();
        $site = $this->site('solo');

        $this->actingAs($site->user)->post(route('sites.pages.store', $site), ['url' => 'https://projectp.fr/']);

        Queue::assertPushed(MesurerPage::class, fn (MesurerPage $job) => $job->strategies === ['mobile', 'desktop']);
    }

    public function test_rien_n_est_mesure_si_la_page_est_refusee(): void
    {
        Queue::fake();
        $site = $this->site('gratuit');

        $this->actingAs($site->user)->post(route('sites.pages.store', $site), ['url' => 'pas-une-adresse']);

        Queue::assertNothingPushed();
    }
}
