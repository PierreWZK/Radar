<?php

namespace App\Services;

use App\Exceptions\PageSpeedException;
use Illuminate\Support\Facades\Http;

class PageSpeedClient
{
    private const ENDPOINT = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';

    public function __construct(private ?string $cle = null)
    {
        $this->cle ??= config('services.pagespeed.key');
    }

    /** @return array{performance: int, accessibilite: int, bonnes_pratiques: int, seo: int, lcp_ms: ?int, cls: ?float, inp_ms: ?int, conseils: array} */
    public function mesurer(string $url, string $strategie): array
    {
        $requete = http_build_query(['url' => $url, 'strategy' => $strategie, 'key' => $this->cle]);
        $requete .= '&category=performance&category=accessibility&category=best-practices&category=seo';

        $reponse = Http::timeout(60)->get(self::ENDPOINT.'?'.$requete);

        if (! $reponse->successful()) {
            throw new PageSpeedException("PageSpeed a répondu {$reponse->status()}.");
        }

        $rapport = $reponse->json('lighthouseResult');

        if (! is_array($rapport)) {
            throw new PageSpeedException('Rapport Lighthouse absent de la réponse.');
        }

        $notes = $rapport['categories'];
        $audits = $rapport['audits'];

        return [
            'performance' => (int) round($notes['performance']['score'] * 100),
            'accessibilite' => (int) round($notes['accessibility']['score'] * 100),
            'bonnes_pratiques' => (int) round($notes['best-practices']['score'] * 100),
            'seo' => (int) round($notes['seo']['score'] * 100),
            'lcp_ms' => isset($audits['largest-contentful-paint']) ? (int) round($audits['largest-contentful-paint']['numericValue']) : null,
            'cls' => isset($audits['cumulative-layout-shift']) ? round($audits['cumulative-layout-shift']['numericValue'], 3) : null,
            'inp_ms' => isset($audits['interaction-to-next-paint']) ? (int) round($audits['interaction-to-next-paint']['numericValue']) : null,
            'conseils' => $this->conseils($audits),
        ];
    }

    /** Opportunités Lighthouse avec un gain estimé, du plus rentable au moins rentable. */
    private function conseils(array $audits): array
    {
        return collect($audits)
            ->filter(fn ($audit) => ($audit['details']['type'] ?? null) === 'opportunity' && ($audit['details']['overallSavingsMs'] ?? 0) > 0)
            ->map(fn ($audit) => ['titre' => $audit['title'], 'gain_ms' => (int) round($audit['details']['overallSavingsMs'])])
            ->sortByDesc('gain_ms')
            ->values()
            ->all();
    }
}
