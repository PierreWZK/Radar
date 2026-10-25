<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::query()
            ->whereIn('site_id', $request->user()->sites()->select('id'))
            ->with('site')
            ->orderBy('url')
            ->get();

        // Une page demandée qui n'appartient pas au compte est ignorée : on retombe sur la première.
        $page = $pages->firstWhere('id', (int) $request->query('page')) ?? $pages->first();

        if (! $page) {
            return view('dashboard', ['vue' => null]);
        }

        $mesures = $page->mesures()
            ->where('mesuree_le', '>=', now()->subDays(90))
            ->orderBy('mesuree_le')
            ->get()
            ->groupBy('strategie');
        $mobile = $mesures->get('mobile', collect())->values();

        $vue = [
            'pages' => $pages,
            'page' => $page,
            'actuelle' => $mobile->last(),
            'precedente' => $mobile->count() > 1 ? $mobile[$mobile->count() - 2] : null,
            'ordinateur' => $mesures->get('desktop', collect())->last(),
            'alerte' => $page->alertes()->where('created_at', '>=', now()->subDays(7))->latest()->first(),
            'graphique' => [
                'dates' => $mobile->map(fn ($m) => $m->mesuree_le->format('d/m')),
                'performance' => $mobile->pluck('performance'),
                'accessibilite' => $mobile->pluck('accessibilite'),
                'bonnes_pratiques' => $mobile->pluck('bonnes_pratiques'),
                'seo' => $mobile->pluck('seo'),
            ],
            'graphiques' => $request->user()->offre()['graphiques'],
            'lecture' => false,
        ];

        return view('dashboard', ['vue' => $vue]);
    }
}
