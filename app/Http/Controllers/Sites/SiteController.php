<?php

namespace App\Http\Controllers\Sites;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Services\SitemapImporter;
use Illuminate\Http\Request;
use RuntimeException;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        return view('sites.index', ['sites' => $request->user()->sites()->latest()->get()]);
    }

    public function create(Request $request)
    {
        return view('sites.create', ['offre' => $request->user()->offre()]);
    }

    public function store(Request $request)
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:80'],
            'url' => ['required', 'url', 'max:2048'],
            'site_entier' => ['sometimes', 'boolean'],
        ]);

        $offre = $request->user()->offre();

        if ($request->user()->sites()->count() >= $offre['sites']) {
            return back()->withErrors(['nom' => "Votre offre permet {$offre['sites']} site(s)."])->withInput();
        }

        if (! empty($donnees['site_entier']) && ! $offre['site_entier']) {
            return back()->withErrors(['site_entier' => "L'option « Site entier » n'est pas incluse dans votre offre."])->withInput();
        }

        $site = $request->user()->sites()->create([
            'nom' => $donnees['nom'],
            'url' => $donnees['url'],
            'site_entier' => ! empty($donnees['site_entier']),
        ]);

        return redirect()->route('sites.show', $site);
    }

    public function show(Request $request, int $site)
    {
        $site = $this->monSite($request, $site);

        return view('sites.show', ['site' => $site, 'offre' => $request->user()->offre()]);
    }

    public function importerSitemap(Request $request, int $site)
    {
        $site = $this->monSite($request, $site);

        if (! $site->site_entier) {
            return back()->withErrors(['sitemap' => "Activez l'option « Site entier » pour importer le sitemap."]);
        }

        try {
            $importees = SitemapImporter::importer($site);
        } catch (RuntimeException $e) {
            return back()->withErrors(['sitemap' => $e->getMessage()]);
        }

        return back()->with('statut', "{$importees} page(s) importée(s).");
    }

    public function destroy(Request $request, int $site)
    {
        $this->monSite($request, $site)->delete();

        return redirect()->route('sites.index');
    }

    private function monSite(Request $request, int $id): Site
    {
        return $request->user()->sites()->findOrFail($id);
    }
}
