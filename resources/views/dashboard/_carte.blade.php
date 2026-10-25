@php
    $actuelle = $carte['actuelle'];
    $veille = $carte['veille'];
    $ecart = $actuelle && $veille ? $actuelle->performance - $veille->performance : null;
@endphp

<article class="carte-page">
    <p class="carte-site">{{ $carte['page']->site->nom }}</p>
    @if ($lien)
        <a class="carte-url" href="{{ route('pages.show', $carte['page']) }}">{{ $carte['page']->url }}</a>
    @else
        <p class="carte-url">{{ $carte['page']->url }}</p>
    @endif

    @if ($actuelle)
        <div class="carte-score">
            <x-anneau :note="$actuelle->performance" />
            <div>
                <p class="carte-libelle">Performance mobile</p>
                @if ($ecart !== null && $ecart < 0)
                    <p class="radar-baisse">{{ str_replace("-", "−", $ecart) }} depuis la veille</p>
                @elseif ($ecart !== null)
                    <p class="carte-hausse">+{{ $ecart }} depuis la veille</p>
                @endif
            </div>
        </div>

        <div class="carte-notes">
            <div class="radar-note"><b>{{ $actuelle->accessibilite }}</b>Access.</div>
            <div class="radar-note"><b>{{ $actuelle->bonnes_pratiques }}</b>Pratiques</div>
            <div class="radar-note"><b>{{ $actuelle->seo }}</b>SEO</div>
            <div class="radar-note"><b>{{ $actuelle->lcp_ms ? str_replace('.', ',', round($actuelle->lcp_ms / 1000, 1)) : '—' }}</b>LCP s</div>
        </div>
    @else
        <p class="mt-4">Première mesure en attente.</p>
    @endif
</article>
