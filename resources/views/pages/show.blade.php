<x-app-layout>
    <x-slot name="header">
        <p class="surtitre"><a class="underline" href="{{ route('sites.show', $page->site) }}">{{ $page->site->nom }}</a></p>
        <h1 class="radar-titre titre-page break-all">{{ $page->url }}</h1>
    </x-slot>

    @if ($derniere)
        <section class="fiche-score" aria-labelledby="notes-titre">
            <h2 id="notes-titre" class="sr-only">Dernière mesure mobile</h2>
            <div class="carte-page fiche-principale">
                <x-anneau :note="$derniere->performance" />
                <div>
                    <p class="carte-libelle">Performance mobile, dernière mesure</p>
                    <p class="carte-site">{{ $derniere->mesuree_le->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            <div class="carte-notes fiche-notes">
                <div class="radar-note"><b>{{ $derniere->accessibilite }}</b>Access.</div>
                <div class="radar-note"><b>{{ $derniere->bonnes_pratiques }}</b>Pratiques</div>
                <div class="radar-note"><b>{{ $derniere->seo }}</b>SEO</div>
                <div class="radar-note"><b>{{ $derniere->lcp_ms ? round($derniere->lcp_ms / 1000, 1) : '—' }}</b>LCP s</div>
            </div>
        </section>
    @else
        <p>Première mesure en attente.</p>
    @endif

    <section class="carte-page fiche-courbe" aria-labelledby="courbe-titre">
        <h2 id="courbe-titre" class="carte-libelle">Performance sur 90 jours</h2>
        <p class="carte-site">{{ $resume }}</p>
        <canvas id="courbe-performance" height="120" aria-hidden="true"></canvas>
        <table class="sr-only">
            <caption>Notes de performance par jour</caption>
            <thead><tr><th scope="col">Date</th><th scope="col">Mobile</th><th scope="col">Ordinateur</th></tr></thead>
            <tbody>
                @foreach ($graphique['dates'] as $i => $date)
                    <tr><th scope="row">{{ $date }}</th><td>{{ $graphique['mobile'][$i] ?? '—' }}</td><td>{{ $graphique['desktop'][$i] ?? '—' }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </section>

    @if ($graphiques)
        <section class="carte-page fiche-courbe" aria-labelledby="vitaux-titre">
            <h2 id="vitaux-titre" class="carte-libelle">Core Web Vitals</h2>
            @if ($derniere)
                <ul class="vitaux">
                    <li><span>LCP</span><b>{{ $derniere->lcp_ms ? round($derniere->lcp_ms / 1000, 1).' s' : '—' }}</b></li>
                    <li><span>CLS</span><b>{{ $derniere->cls ?? '—' }}</b></li>
                    <li><span>INP</span><b>{{ $derniere->inp_ms ? $derniere->inp_ms.' ms' : '—' }}</b></li>
                </ul>
            @endif
        </section>
    @else
        <p class="bandeau-exemple">Données détaillées : offre Solo ou Agence.</p>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" integrity="sha384-9nhczxUqK87bcKHh20fSQcTGD4qq5GhayNYSYWqwBkINBhOfQLg/P5HG5lF1urn4" crossorigin="anonymous"></script>
    <script>
        const donnees = @json($graphique);
        new Chart(document.getElementById('courbe-performance'), {
            type: 'line',
            data: {
                labels: donnees.dates,
                datasets: [
                    { label: 'Mobile', data: donnees.mobile, borderColor: '#0a0a0a', tension: 0.3 },
                    { label: 'Ordinateur', data: donnees.desktop, borderColor: '#b0298f', tension: 0.3 },
                ],
            },
            options: { scales: { y: { min: 0, max: 100 } } },
        });
    </script>
</x-app-layout>
