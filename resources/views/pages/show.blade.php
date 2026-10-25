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
            <h2 class="font-semibold mb-4">Performance sur 90 jours</h2>
            <canvas id="courbe-performance" height="120"></canvas>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
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
