<?php

namespace App\Services;

/** Seuils de Google pour les Core Web Vitals (LCP en ms, CLS sans unité, INP en ms). */
class SeuilsWebVitals
{
    public const BON = 0;

    public const A_AMELIORER = 1;

    public const MAUVAIS = 2;

    private const SEUILS = [
        'lcp' => [2500, 4000],
        'cls' => [0.1, 0.25],
        'inp' => [200, 500],
    ];

    public static function categorie(string $metrique, int|float|null $valeur): ?int
    {
        if ($valeur === null) {
            return null;
        }

        [$bon, $moyen] = self::SEUILS[$metrique];

        return match (true) {
            $valeur <= $bon => self::BON,
            $valeur <= $moyen => self::A_AMELIORER,
            default => self::MAUVAIS,
        };
    }
}
