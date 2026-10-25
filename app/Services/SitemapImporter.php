<?php

namespace App\Services;

use App\Models\Site;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SitemapImporter
{
    public const LIMITE = 200;

    public static function importer(Site $site): int
    {
        $reponse = Http::timeout(20)->get(rtrim($site->url, '/').'/sitemap.xml');

        if (! $reponse->successful()) {
            throw new RuntimeException("Sitemap introuvable (réponse {$reponse->status()}).");
        }

        preg_match_all('#<loc>\s*([^<\s]+)\s*</loc>#', $reponse->body(), $correspondances);

        $deja = $site->pages()->pluck('url')->all();
        $places = self::LIMITE - count($deja);
        $nouvelles = collect($correspondances[1])->unique()->reject(fn ($url) => in_array($url, $deja))->take($places);

        foreach ($nouvelles as $url) {
            $site->pages()->create(['user_id' => $site->user_id, 'url' => $url]);
        }

        return $nouvelles->count();
    }
}
