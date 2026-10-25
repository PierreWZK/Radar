<x-app-layout>
    <div class="radar-page">
        <p class="text-sm"><a class="underline" href="{{ route('sites.show', $page->site) }}">{{ $page->site->nom }}</a></p>
        <h1 class="radar-titre text-4xl my-4 break-all">{{ $page->url }}</h1>

        @if ($derniere)
            <div class="grid grid-cols-4 gap-3 max-w-2xl">
                <div class="radar-note"><b>{{ $derniere->performance }}</b>Perf</div>
                <div class="radar-note"><b>{{ $derniere->accessibilite }}</b>Access.</div>
                <div class="radar-note"><b>{{ $derniere->bonnes_pratiques }}</b>Pratiques</div>
                <div class="radar-note"><b>{{ $derniere->seo }}</b>SEO</div>
            </div>
        @else
            <p>Première mesure en attente.</p>
        @endif

        <section class="radar-carte mt-8">
            <h2 class="font-semibold mb-2">Performance sur 90 jours</h2>
            @php($notesMobile = collect($graphique['mobile']))
            @if ($notesMobile->isNotEmpty())
                <p class="mb-4">Mobile : dernière note {{ $notesMobile->last() }}, plus haute {{ $notesMobile->max() }}, plus basse {{ $notesMobile->min() }}.</p>
            @endif
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
            <section class="radar-carte mt-8">
                <h2 class="font-semibold mb-2">Core Web Vitals</h2>
                @if ($derniere)
                    <ul>
                        <li>LCP : {{ $derniere->lcp_ms ? round($derniere->lcp_ms / 1000, 1).' s' : '—' }}</li>
                        <li>CLS : {{ $derniere->cls ?? '—' }}</li>
                        <li>INP : {{ $derniere->inp_ms ? $derniere->inp_ms.' ms' : '—' }}</li>
                    </ul>
                @endif
            </section>
        @else
            <p class="mt-8">Données détaillées : offre Solo ou Agence.</p>
        @endif
    </div>

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
