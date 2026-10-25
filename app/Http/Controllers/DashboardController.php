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

        $cartes = $pages->map(function (Page $page) {
            $dernieres = $page->mesures()->where('strategie', 'mobile')->latest('mesuree_le')->limit(2)->get();

            return ['page' => $page, 'actuelle' => $dernieres[0] ?? null, 'veille' => $dernieres[1] ?? null];
        });

        return view('dashboard', ['cartes' => $cartes]);
    }
}
