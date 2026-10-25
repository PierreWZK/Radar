<?php

namespace Tests\Unit;

use App\Exceptions\PageSpeedException;
use App\Services\PageSpeedClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PageSpeedClientTest extends TestCase
{
    public function test_il_lit_les_quatre_notes_et_les_vitals_du_rapport(): void
    {
        Http::fake(['*' => Http::response(json_decode(file_get_contents(__DIR__.'/../Fixtures/pagespeed-mobile.json'), true), 200)]);

        $resultat = (new PageSpeedClient('cle-de-test'))->mesurer('https://projectp.fr/', 'mobile');

        $this->assertSame(94, $resultat['performance']);
        $this->assertSame(100, $resultat['accessibilite']);
        $this->assertSame(96, $resultat['bonnes_pratiques']);
        $this->assertSame(92, $resultat['seo']);
        $this->assertSame(2900, $resultat['lcp_ms']);
        $this->assertSame(0.02, $resultat['cls']);
        $this->assertNull($resultat['inp_ms']);
    }

    public function test_la_cle_part_dans_la_requete_serveur_et_la_strategie_est_transmise(): void
    {
        Http::fake(['*' => Http::response(json_decode(file_get_contents(__DIR__.'/../Fixtures/pagespeed-mobile.json'), true), 200)]);

        (new PageSpeedClient('cle-de-test'))->mesurer('https://projectp.fr/', 'desktop');

        Http::assertSent(fn ($request) => str_contains($request->url(), 'key=cle-de-test')
            && str_contains($request->url(), 'strategy=desktop'));
    }

    public function test_une_reponse_en_erreur_leve_une_exception(): void
    {
        Http::fake(['*' => Http::response(['error' => 'quota'], 429)]);

        $this->expectException(PageSpeedException::class);

        (new PageSpeedClient('cle-de-test'))->mesurer('https://projectp.fr/', 'mobile');
    }
}
