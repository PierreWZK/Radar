<?php

namespace Tests\Unit;

use App\Services\Mediane;
use PHPUnit\Framework\TestCase;

class MedianeTest extends TestCase
{
    public function test_la_mediane_de_trois_valeurs_ignore_la_valeur_extreme(): void
    {
        $this->assertSame(94, Mediane::de([94, 60, 96]));
    }

    public function test_la_mediane_d_un_nombre_pair_est_la_moyenne_des_deux_centraux(): void
    {
        $this->assertSame(2.5, Mediane::de([1, 2, 3, 4]));
    }
}
