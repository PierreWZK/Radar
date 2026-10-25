<?php

namespace App\Http\Controllers;

use App\Exceptions\PageSpeedException;
use App\Services\PageSpeedClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class TesterController extends Controller
{
    public function formulaire()
    {
        return view('tester', ['resultat' => null]);
    }

    public function analyser(Request $request)
    {
        $donnees = $request->validate(['url' => ['required', 'url', 'max:2048']]);

        $reponse = RateLimiter::attempt('test-sans-compte:'.$request->ip(), 3, function () use ($donnees) {
            try {
                $resultat = (new PageSpeedClient)->mesurer($donnees['url'], 'mobile');
            } catch (PageSpeedException) {
                return back()->withErrors(['url' => 'PageSpeed ne répond pas pour le moment. Réessayez dans quelques minutes.'])->withInput();
            }

            return view('tester', ['resultat' => $resultat, 'url' => $donnees['url']]);
        }, 86400);

        return $reponse ?: abort(429, 'Limite de 3 tests par jour atteinte.');
    }
}
