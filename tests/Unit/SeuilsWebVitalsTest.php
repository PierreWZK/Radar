<?php

namespace Tests\Unit;

use App\Services\SeuilsWebVitals;
use PHPUnit\Framework\TestCase;

class SeuilsWebVitalsTest extends TestCase
{
    public function test_les_seuils_lcp_suivent_google(): void
    {
        $this->assertSame(SeuilsWebVitals::BON, SeuilsWebVitals::categorie('lcp', 2500));
        $this->assertSame(SeuilsWebVitals::A_AMELIORER, SeuilsWebVitals::categorie('lcp', 2501));
        $this->assertSame(SeuilsWebVitals::MAUVAIS, SeuilsWebVitals::categorie('lcp', 4001));
    }

    public function test_les_seuils_cls_suivent_google(): void
    {
        $this->assertSame(SeuilsWebVitals::BON, SeuilsWebVitals::categorie('cls', 0.1));
        $this->assertSame(SeuilsWebVitals::MAUVAIS, SeuilsWebVitals::categorie('cls', 0.26));
    }

    public function test_une_valeur_absente_n_a_pas_de_categorie(): void
    {
        $this->assertNull(SeuilsWebVitals::categorie('inp', null));
    }
}
