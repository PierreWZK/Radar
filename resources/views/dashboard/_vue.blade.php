@php
    use App\Services\SeuilsWebVitals;

    $page = $vue['page'];
    $actuelle = $vue['actuelle'];
    $precedente = $vue['precedente'];
    $lecture = $vue['lecture'];

    $ecart = fn (string $cle) => $actuelle && $precedente ? $actuelle->$cle - $precedente->$cle : null;
    $signe = fn (?int $n) => $n === null ? '' : ($n > 0 ? '+'.$n : ($n < 0 ? '−'.abs($n) : '='));
    $virgule = fn ($n) => str_replace('.', ',', (string) $n);
    $point = fn (?int $categorie) => match ($categorie) {
        SeuilsWebVitals::BON => 'p-bon',
        SeuilsWebVitals::A_AMELIORER => 'p-moyen',
        SeuilsWebVitals::MAUVAIS => 'p-alerte',
        default => 'p-calme',
    };

    $notes = [
        ['performance', 'Performance', 'P', 'p-calme'],
        ['accessibilite', 'Accessibilité', 'A', 'p-bon'],
        ['bonnes_pratiques', 'Bonnes pratiques', 'B', 'p-moyen'],
        ['seo', 'SEO', 'S', 'p-encre'],
    ];
    $ecartPerf = $ecart('performance');
@endphp

<div class="tdb2">
    <div class="tdb2-principal">
        <div class="tdb2-haut">
            @if ($lecture)
                <p class="tdb2-champ">
                    <svg class="icone" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="M16 16 L20 20"/></svg>
                    <span>{{ $page->url }}</span>
                </p>
            @else
                <form method="GET" action="{{ route('dashboard') }}" class="tdb2-champ">
                    <svg class="icone" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="M16 16 L20 20"/></svg>
                    <label for="choix-page" class="sr-only">Page affichée</label>
                    <select id="choix-page" name="page" onchange="this.form.submit()">
                        @foreach ($vue['pages'] as $option)
                            <option value="{{ $option->id }}" @selected($option->id === $page->id)>{{ $option->url }}</option>
                        @endforeach
                    </select>
                    <noscript><button type="submit" class="lien-compte">Afficher</button></noscript>
                </form>
            @endif
            <p class="tdb2-date">
                <svg class="icone" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M4 10 H20 M9 3 V7 M15 3 V7"/></svg>
                @if ($actuelle)
                    {{ $actuelle->mesuree_le->isToday() ? "Aujourd'hui" : $actuelle->mesuree_le->format('d/m/Y') }}
                @else
                    Pas encore mesurée
                @endif
            </p>
        </div>

        <section class="tdb2-carte tdb2-notes" aria-labelledby="notes-titre">
            <h2 id="notes-titre" class="sr-only">Notes mobiles de {{ $page->url }}</h2>
            @if ($actuelle)
                <div class="tdb2-anneau">
                    <x-anneau :note="$actuelle->performance" />
                    @if ($ecartPerf !== null)
                        <span @class(['anneau-ecart', 'baisse' => $ecartPerf < 0])>{{ $signe($ecartPerf) }}</span>
                    @endif
                </div>
                <ul class="tdb2-lignes">
                    @foreach ($notes as [$cle, $libelle, $lettre, $couleur])
                        @php($e = $ecart($cle))
                        <li>
                            <i class="pastille {{ $couleur }}" aria-hidden="true">{{ $lettre }}</i>
                            <span>{{ $libelle }}</span>
                            <b>{{ $actuelle->$cle }}</b>
                            <em @class(['baisse' => $e !== null && $e < 0])>{{ $signe($e) }}</em>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Première mesure en attente. Radar mesure cette page à la prochaine tournée.</p>
            @endif
        </section>

        <div class="tdb2-bas">
            @if ($vue['graphiques'])
                <section class="tdb2-carte" aria-labelledby="courbes-titre">
                    <h2 id="courbes-titre" class="tdb2-titre">Évolution des notes <span>(90 jours)</span></h2>
                    @if (count($vue['graphique']['dates']) > 1)
                        <div class="tdb2-courbe"><canvas id="courbes" aria-hidden="true"></canvas></div>
                        <div class="sr-only"><table>
                            <caption>Notes mobiles par jour</caption>
                            <thead><tr><th scope="col">Date</th><th scope="col">Performance</th><th scope="col">Accessibilité</th><th scope="col">Bonnes pratiques</th><th scope="col">SEO</th></tr></thead>
                            <tbody>
                                @foreach ($vue['graphique']['dates'] as $i => $date)
                                    <tr><th scope="row">{{ $date }}</th><td>{{ $vue['graphique']['performance'][$i] }}</td><td>{{ $vue['graphique']['accessibilite'][$i] }}</td><td>{{ $vue['graphique']['bonnes_pratiques'][$i] }}</td><td>{{ $vue['graphique']['seo'][$i] }}</td></tr>
                                @endforeach
                            </tbody>
                        </table></div>
                    @else
                        <p class="tdb2-discret">La courbe apparaît après deux mesures.</p>
                    @endif
                </section>

                <section class="tdb2-carte" aria-labelledby="vitaux-titre">
                    <h2 id="vitaux-titre" class="tdb2-titre">Core Web Vitals</h2>
                    @if ($actuelle)
                        <ul class="tdb2-vitaux">
                            <li><i class="point {{ $point(SeuilsWebVitals::categorie('lcp', $actuelle->lcp_ms)) }}"></i>LCP<b>{{ $actuelle->lcp_ms ? $virgule(round($actuelle->lcp_ms / 1000, 1)).' s' : '—' }}</b></li>
                            <li><i class="point {{ $point(SeuilsWebVitals::categorie('cls', $actuelle->cls)) }}"></i>CLS<b>{{ $actuelle->cls !== null ? $virgule(round($actuelle->cls, 2)) : '—' }}</b></li>
                            <li><i class="point {{ $point(SeuilsWebVitals::categorie('inp', $actuelle->inp_ms)) }}"></i>INP<b>{{ $actuelle->inp_ms ? $actuelle->inp_ms.' ms' : '—' }}</b></li>
                        </ul>
                        <p class="tdb2-discret">Vert : bon. Orange : à améliorer. Magenta : mauvais.</p>
                    @endif
                </section>
            @else
                <section class="tdb2-carte tdb2-verrou">
                    <h2 class="tdb2-titre">Courbes et signaux détaillés</h2>
                    <p>L'évolution sur 90 jours et le détail LCP, CLS, INP sont inclus dans les offres Solo et Agence.</p>
                    @unless ($lecture)
                        <a class="lien-lecture" href="{{ url('/#tarifs') }}">Voir les offres <span aria-hidden="true">→</span></a>
                    @endunless
                </section>
            @endif
        </div>
    </div>

    <aside class="tdb2-cote" aria-label="Alertes et autres mesures">
        @if ($vue['alerte'])
            @php($alerte = $vue['alerte'])
            <section class="tdb2-alerte" aria-labelledby="alerte-titre">
                <h2 id="alerte-titre" class="alerte-titre">
                    <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M10 2 L19 18 H1 Z"/><path d="M10 8 V12 M10 14.5 V15" class="alerte-signe"/></svg>
                    Alerte détectée !
                </h2>
                <p>{{ $alerte->raison }} : la note Performance est passée de {{ $alerte->performance_avant }} à {{ $alerte->performance_apres }}@if ($alerte->created_at) le {{ $alerte->created_at->format('d/m') }}@endif.</p>
                @if (! empty($alerte->conseils))
                    <details class="tdb2-conseils">
                        <summary>Voir les conseils <span aria-hidden="true">→</span></summary>
                        <ol>
                            @foreach ($alerte->conseils as $conseil)
                                <li><span>{{ $conseil['titre'] }}</span><b>−{{ $virgule(round($conseil['gain_ms'] / 1000, 1)) }} s</b></li>
                            @endforeach
                        </ol>
                    </details>
                @endif
            </section>
        @endif

        @if ($vue['ordinateur'])
            <section class="tdb2-carte tdb2-mini" aria-labelledby="ordinateur-titre">
                <h2 id="ordinateur-titre" class="tdb2-titre">Performance ordinateur</h2>
                <x-anneau :note="$vue['ordinateur']->performance" />
            </section>
        @endif

        @if ($vue['pages']->count() > 1)
            <section class="tdb2-carte" aria-labelledby="autres-titre">
                <h2 id="autres-titre" class="tdb2-titre">Vos autres pages</h2>
                <ul class="tdb2-autres">
                    @foreach ($vue['pages']->where('url', '!=', $page->url) as $autre)
                        <li>
                            @if ($lecture)
                                <span>{{ $autre->url }}</span>
                            @else
                                <a href="{{ route('dashboard', ['page' => $autre->id]) }}">{{ $autre->url }}</a>
                            @endif
                            <small>{{ $autre->site->nom }}</small>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @unless ($lecture)
            <a class="lien-lecture" href="{{ route('pages.show', $page) }}">Fiche détaillée de la page <span aria-hidden="true">→</span></a>
        @endunless
    </aside>
</div>

@if ($vue['graphiques'] && count($vue['graphique']['dates']) > 1)
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" integrity="sha384-9nhczxUqK87bcKHh20fSQcTGD4qq5GhayNYSYWqwBkINBhOfQLg/P5HG5lF1urn4" crossorigin="anonymous"></script>
    <script>
        (() => {
            const d = @json($vue['graphique']);
            const calme = matchMedia('(prefers-reduced-motion: reduce)').matches;
            const ligne = (label, data, couleur) => ({ label, data, borderColor: couleur, backgroundColor: couleur, borderWidth: 2, pointRadius: 0, tension: 0.35 });
            new Chart(document.getElementById('courbes'), {
                type: 'line',
                data: {
                    labels: d.dates,
                    datasets: [
                        ligne('Performance', d.performance, '#44606e'),
                        ligne('Accessibilité', d.accessibilite, '#2e7d4f'),
                        ligne('Bonnes pratiques', d.bonnes_pratiques, '#c2770a'),
                        ligne('SEO', d.seo, '#0a0a0a'),
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                    animation: calme ? false : { duration: 1200, easing: 'easeOutQuart' },
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true } } },
                    scales: { y: { suggestedMin: 60, max: 100, ticks: { stepSize: 10 } }, x: { grid: { display: false }, ticks: { maxTicksLimit: 6 } } },
                },
            });
        })();
    </script>
@endif
