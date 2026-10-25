<?php

namespace Tests\Feature;

use App\Models\Mesure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExempleTest extends TestCase
{
    use RefreshDatabase;

    public function test_l_exemple_est_public_et_signale_ses_donnees_fictives(): void
    {
        $this->get('/exemple')
            ->assertOk()
            ->assertSee("Données d'exemple", false)
            ->assertSee('boulangerie-exemple.fr')
            ->assertDontSee('secret-client.fr');
    }

    public function test_l_exemple_n_ecrit_rien_en_base(): void
    {
        $this->get('/exemple')->assertOk();

        $this->assertSame(0, Mesure::count());
    }
}
