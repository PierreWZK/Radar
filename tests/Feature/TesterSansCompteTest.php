<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class TesterSansCompteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        RateLimiter::clear('test-sans-compte:127.0.0.1');
        Http::fake(['*' => Http::response(json_decode(file_get_contents(__DIR__.'/../Fixtures/pagespeed-mobile.json'), true), 200)]);
    }

    public function test_trois_tests_par_jour_sont_acceptes(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post('/tester', ['url' => 'https://projectp.fr/'])->assertOk()->assertSee('94');
        }
    }

    public function test_le_quatrieme_test_du_jour_est_refuse(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post('/tester', ['url' => 'https://projectp.fr/']);
        }

        $this->post('/tester', ['url' => 'https://projectp.fr/'])->assertStatus(429);
    }

    public function test_une_adresse_invalide_est_refusee(): void
    {
        $this->post('/tester', ['url' => 'pas une adresse'])->assertSessionHasErrors('url');
    }
}
