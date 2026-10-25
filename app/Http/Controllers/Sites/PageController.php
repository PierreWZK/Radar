<?php

namespace App\Http\Controllers\Sites;

use App\Http\Controllers\Controller;
use App\Jobs\MesurerPage;
use App\Models\Page;
use App\Services\Planification;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request, int $page)
    {
        $page = Page::with('site')->findOrFail($page);

        abort_unless($page->site->user_id === $request->user()->id, 404);

        $mesures = $page->mesures()
            ->where('mesuree_le', '>=', now()->subDays(90))
            ->orderBy('mesuree_le')
            ->get()
            ->groupBy('strategie');

        $graphique = [
            'dates' => $mesures->get('mobile', collect())->map(fn ($m) => $m->mesuree_le->format('d/m'))->values(),
            'mobile' => $mesures->get('mobile', collect())->pluck('performance')->values(),
            'desktop' => $mesures->get('desktop', collect())->pluck('performance')->values(),
        ];

        $notesMobile = $graphique['mobile'];
        $resume = $notesMobile->isEmpty()
            ? 'Pas encore de mesure mobile.'
            : "Mobile : dernière note {$notesMobile->last()}, plus haute {$notesMobile->max()}, plus basse {$notesMobile->min()}.";

        return view('pages.show', [
            'page' => $page,
            'graphique' => $graphique,
            'resume' => $resume,
            'derniere' => $mesures->get('mobile', collect())->last(),
            'graphiques' => $request->user()->offre()['graphiques'],
        ]);
    }

    public function store(Request $request, int $site)
    {
        $site = $request->user()->sites()->findOrFail($site);
        $donnees = $request->validate(['url' => ['required', 'url', 'max:2048']]);

        $limite = $site->site_entier ? 200 : $request->user()->offre()['pages_par_site'];

        if ($site->pages()->count() >= $limite) {
            return back()->withErrors(['url' => "Votre offre permet {$limite} page(s) par site."])->withInput();
        }

        $page = $site->pages()->create(['user_id' => $request->user()->id, 'url' => $donnees['url']]);

        // Première mesure tout de suite, sans attendre la tournée de 3 h
        MesurerPage::dispatch($page, Planification::appareils($page));

        return redirect()->route('sites.show', $site);
    }

    public function update(Request $request, int $page)
    {
        $page = Page::with('site')->findOrFail($page);

        abort_unless($page->site->user_id === $request->user()->id, 404);

        $page->update(['quotidienne' => $request->boolean('quotidienne')]);

        return redirect()->route('sites.show', $page->site);
    }

    public function destroy(Request $request, int $page)
    {
        $page = Page::with('site')->findOrFail($page);

        abort_unless($page->site->user_id === $request->user()->id, 404);

        $site = $page->site;
        $page->delete();

        return redirect()->route('sites.show', $site);
    }
}
