<?php

namespace Tests\Feature;

use Tests\TestCase;

class VoirMotDePasseTest extends TestCase
{
    public function test_la_connexion_propose_d_afficher_le_mot_de_passe(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('class="voir-mdp"', false)
            ->assertSee('aria-controls="password"', false);
    }

    public function test_l_inscription_propose_d_afficher_les_deux_mots_de_passe(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('aria-controls="password"', false)
            ->assertSee('aria-controls="password_confirmation"', false);
    }
}
