<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\Badge;
use Illuminate\Support\Facades\Cache;

class BadgeController extends Controller
{
    public function svg(int $page)
    {
        $page = Page::findOrFail($page);

        $svg = Cache::remember("badge:{$page->id}", now()->addHours(3), function () use ($page) {
            $note = $page->mesures()->where('strategie', 'mobile')->latest('mesuree_le')->value('performance');

            return view('badges.performance', [
                'note' => $note,
                'texte' => $note === null ? '—' : (string) $note,
                'couleur' => Badge::couleur($note),
            ])->render();
        });

        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }
}
