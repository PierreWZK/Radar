<?php

namespace App\Http\Controllers\Sites;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function store(Request $request, int $site)
    {
        $site = $request->user()->sites()->findOrFail($site);
        $donnees = $request->validate(['url' => ['required', 'url', 'max:2048']]);

        $limite = $site->site_entier ? 200 : $request->user()->offre()['pages_par_site'];

        if ($site->pages()->count() >= $limite) {
            return back()->withErrors(['url' => "Votre offre permet {$limite} page(s) par site."])->withInput();
        }

        $site->pages()->create(['user_id' => $request->user()->id, 'url' => $donnees['url']]);

        return redirect()->route('sites.show', $site);
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
