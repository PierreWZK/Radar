<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use App\Models\Mesure;
use App\Models\Page;
use App\Models\Site;

class ExempleController extends Controller
{
    // Données fictives, jamais enregistrées : aucune requête, aucune écriture.
    public function index()
    {
        $site = new Site(['nom' => 'Boulangerie (exemple)', 'url' => 'https://boulangerie-exemple.fr/']);
        $pages = collect(['https://boulangerie-exemple.fr/', 'https://boulangerie-exemple.fr/produits', 'https://boulangerie-exemple.fr/contact'])
            ->map(function (string $url) use ($site) {
                $page = new Page(['url' => $url]);
                $page->setRelation('site', $site);

                return $page;
            });

        // 30 jours de notes stables, puis la baisse d'hier à aujourd'hui après un déploiement.
        $jours = collect(range(29, 0))->map(fn (int $il_y_a) => [
            'date' => now()->subDays($il_y_a),
            'performance' => match ($il_y_a) { 0 => 84, 1 => 92, default => 90 + ($il_y_a % 3) },
            'accessibilite' => 95 + ($il_y_a % 2),
            'bonnes_pratiques' => 92 + ($il_y_a % 3),
            'seo' => 90,
        ]);

        $mesure = fn (array $jour, array $vitaux = []) => new Mesure($jour + $vitaux + ['mesuree_le' => $jour['date']]);

        return view('exemple', ['vue' => [
            'pages' => $pages,
            'page' => $pages->first(),
            'actuelle' => $mesure($jours->last(), ['lcp_ms' => 2900, 'cls' => 0.05, 'inp_ms' => 120]),
            'precedente' => $mesure($jours[$jours->count() - 2]),
            'ordinateur' => new Mesure(['performance' => 97]),
            'alerte' => new Alerte([
                'raison' => 'Performance en baisse de 8 points',
                'performance_avant' => 92,
                'performance_apres' => 84,
                'conseils' => [
                    ['titre' => "Compresser l'image d'accueil", 'gain_ms' => 1200],
                    ['titre' => 'Différer le script de chat', 'gain_ms' => 400],
                ],
            ]),
            'graphique' => [
                'dates' => $jours->map(fn ($j) => $j['date']->format('d/m')),
                'performance' => $jours->pluck('performance'),
                'accessibilite' => $jours->pluck('accessibilite'),
                'bonnes_pratiques' => $jours->pluck('bonnes_pratiques'),
                'seo' => $jours->pluck('seo'),
            ],
            'graphiques' => true,
            'lecture' => true,
        ]]);
    }
}
