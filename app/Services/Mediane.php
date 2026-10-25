<?php

namespace App\Services;

class Mediane
{
    /** @param  array<int|float>  $valeurs */
    public static function de(array $valeurs): int|float
    {
        sort($valeurs);
        $milieu = intdiv(count($valeurs), 2);

        if (count($valeurs) % 2 === 1) {
            return $valeurs[$milieu];
        }

        return ($valeurs[$milieu - 1] + $valeurs[$milieu]) / 2;
    }
}
