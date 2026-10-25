<?php

namespace App\Http\Controllers;

use App\Models\Mesure;
use App\Models\Page;
use App\Models\Site;

class ExempleController extends Controller
{
    // Données fictives, jamais enregistrées : aucune requête, aucune écriture.
    public function index()
    {
        $exemples = [
            ['https://boulangerie-exemple.fr/', 'Boulangerie (exemple)', 84, 92, 96, 94, 90, 2900, 0.05, 120],
            ['https://blog-exemple.fr/article', 'Blog (exemple)', 96, 95, 100, 96, 100, 1900, 0.01, 90],
            ['https://boutique-exemple.fr/', 'Boutique (exemple)', 58, 71, 88, 92, 84, 4300, 0.31, 260],
        ];

        $cartes = collect($exemples)->map(function ($e) {
            [$url, $nom, $perf, $perfVeille, $acces, $bp, $seo, $lcp, $cls, $inp] = $e;

            $page = new Page(['url' => $url]);
            $page->setRelation('site', new Site(['nom' => $nom, 'url' => $url]));

            $actuelle = new Mesure(['performance' => $perf, 'accessibilite' => $acces, 'bonnes_pratiques' => $bp, 'seo' => $seo, 'lcp_ms' => $lcp, 'cls' => $cls, 'inp_ms' => $inp, 'mesuree_le' => now()]);
            $veille = new Mesure(['performance' => $perfVeille, 'mesuree_le' => now()->subDay()]);

            return ['page' => $page, 'actuelle' => $actuelle, 'veille' => $veille];
        });

        return view('exemple', ['cartes' => $cartes]);
    }
}
