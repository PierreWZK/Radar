<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TesterPannePageSpeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_une_panne_de_pagespeed_affiche_un_message_sans_erreur_serveur(): void
    {
        Http::fake(['*' => Http::response('Service indisponible', 503)]);

        $this->post('/tester', ['url' => 'https://projectp.fr/'])
            ->assertRedirect()
            ->assertSessionHasErrors(['url' => 'PageSpeed ne répond pas pour le moment. Réessayez dans quelques minutes.']);
    }
}
