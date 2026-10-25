<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use Illuminate\Http\Request;

class AlerteController extends Controller
{
    public function index(Request $request)
    {
        $alertes = Alerte::query()
            ->whereHas('page.site', fn ($site) => $site->where('user_id', $request->user()->id))
            ->with('page.site')
            ->latest()
            ->limit(50)
            ->get();

        return view('alertes', ['alertes' => $alertes]);
    }
}
