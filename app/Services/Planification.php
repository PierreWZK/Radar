<?php

namespace App\Services;

use App\Models\Page;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Planification
{
    /**
     * Pages à mesurer ce jour-là, avec les appareils à mesurer.
     *
     * Gratuit : lundi, mobile seulement. Solo et Agence : chaque jour, mobile et ordinateur.
     * Pages d'un « Site entier » : une fois par semaine, en roulement (une page par jour), sauf les pages marquées quotidiennes.
     *
     * @return Collection<int, array{page: Page, strategies: array<int, string>}>
     */
    public static function pour(CarbonInterface $jour): Collection
    {
        return Page::query()
            ->with('site.user')
            ->get()
            ->map(fn (Page $page) => ['page' => $page, 'strategies' => self::strategies($page, Carbon::instance($jour))])
            ->filter(fn (array $ligne) => $ligne['strategies'] !== [])
            ->values();
    }

    /** @return array<int, string> */
    private static function strategies(Page $page, Carbon $jour): array
    {
        if (! $page->site->user->offre()['site_entier']) {
            return $jour->isMonday() ? ['mobile'] : [];
        }

        if ($page->site->site_entier && ! $page->quotidienne) {
            return $page->id % 7 === $jour->dayOfWeekIso - 1 ? ['mobile', 'desktop'] : [];
        }

        return ['mobile', 'desktop'];
    }
}
