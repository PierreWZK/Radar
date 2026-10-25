<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccueilTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_heros_montre_le_titre_le_tableau_de_bord_d_exemple_et_le_test_gratuit(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('La santé de vos sites,')
            ->assertSee('semaine après semaine.')
            ->assertSee('Alerte détectée')
            ->assertSee(route('tester'))
            ->assertSee(route('register'))
            ->assertSee('https://projectp.fr/');
    }

    public function test_l_accueil_presente_les_fonctionnalites_et_le_jeu_du_jour(): void
    {
        $this->get('/')
            ->assertSeeInOrder(['Des mesures fiables', 'Une vue claire', 'Des alertes utiles', 'Pour tous les projets'])
            ->assertSee('Le jeu du jour')
            ->assertSee('<fieldset class="defi-options"', false);
    }

    public function test_l_accueil_presente_les_offres_la_faq_et_le_pied_de_page(): void
    {
        $this->get('/')
            ->assertSeeInOrder(['Gratuit', 'Solo', '4 €', 'Agence', '12 €'])
            ->assertSee('Questions fréquentes')
            ->assertSee('Jour 13 du défi')
            ->assertSee('https://github.com/PierreWZK/Radar');
    }

    public function test_un_compte_connecte_voit_le_lien_vers_son_tableau_de_bord(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertSee(route('dashboard'))
            ->assertDontSee(route('register'));
    }
}
