<?php

namespace App\Services;

use App\Mail\AlertePerformance;
use App\Models\Alerte;
use App\Models\Mesure;
use Illuminate\Support\Facades\Mail;

class DetecteurBaisse
{
    public const BAISSE_MINIMALE = 5;

    public const FENETRE_JOURS = 7;

    /** Compare une mesure à la médiane des 7 jours précédents ; crée une alerte si la note ou un Core Web Vital se dégrade. */
    public static function pour(Mesure $mesure): ?Alerte
    {
        $page = $mesure->page;

        if ($page->alertes()->whereDate('created_at', today())->exists()) {
            return null;
        }

        $historique = Mesure::where('page_id', $page->id)
            ->where('strategie', $mesure->strategie)
            ->where('id', '<>', $mesure->id)
            ->where('mesuree_le', '>=', now()->subDays(self::FENETRE_JOURS))
            ->get();

        if ($historique->isEmpty()) {
            return null;
        }

        $raison = self::raison($mesure, $historique);

        if ($raison === null) {
            return null;
        }

        $alerte = Alerte::create([
            'page_id' => $page->id,
            'strategie' => $mesure->strategie,
            'raison' => $raison,
            'performance_avant' => (int) round(Mediane::de($historique->pluck('performance')->all())),
            'performance_apres' => $mesure->performance,
            'conseils' => self::conseils($mesure, $page->user),
        ]);

        Mail::to($page->user)->send(new AlertePerformance($alerte));

        return $alerte;
    }

    private static function raison(Mesure $mesure, $historique): ?string
    {
        $baisse = Mediane::de($historique->pluck('performance')->all()) - $mesure->performance;

        if ($baisse >= self::BAISSE_MINIMALE) {
            return "Performance en baisse de {$baisse} point(s)";
        }

        foreach (['lcp' => ['lcp_ms', 'LCP'], 'cls' => ['cls', 'CLS'], 'inp' => ['inp_ms', 'INP']] as $metrique => [$colonne, $libelle]) {
            $valeurs = $historique->pluck($colonne)->filter(fn ($valeur) => $valeur !== null)->values()->all();

            if ($valeurs === []) {
                continue;
            }

            $avant = SeuilsWebVitals::categorie($metrique, Mediane::de($valeurs));
            $apres = SeuilsWebVitals::categorie($metrique, $mesure->{$colonne});

            if ($apres !== null && $apres > $avant) {
                return "$libelle dégradé";
            }
        }

        return null;
    }

    private static function conseils(Mesure $mesure, $user): array
    {
        $conseils = collect($mesure->conseils ?? [])->sortByDesc('gain_ms')->values();

        if ($user->plan === 'gratuit') {
            return $mesure->performance < 50 ? $conseils->take(3)->all() : [];
        }

        return $conseils->take(10)->all();
    }
}
