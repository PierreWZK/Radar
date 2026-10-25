<x-app-layout>
    <div class="radar-page">
        <h1 class="radar-titre text-4xl mb-8">Vos pages</h1>

        @if ($cartes->isEmpty())
            <p>Aucune page suivie. <a class="underline" href="{{ route('sites.create') }}">Ajoutez un site</a> pour commencer.</p>
        @endif

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($cartes as $carte)
                @php
                    $actuelle = $carte['actuelle'];
                    $ecart = $actuelle && $carte['veille'] ? $actuelle->performance - $carte['veille']->performance : null;
                @endphp
                <article class="radar-carte">
                    <p class="font-semibold">{{ $carte['page']->site->nom }}</p>
                    <a class="text-sm break-all underline" href="{{ route('pages.show', $carte['page']) }}">{{ $carte['page']->url }}</a>

                    @if ($actuelle)
                        <div class="grid grid-cols-4 gap-2 mt-4">
                            <div class="radar-note"><b>{{ $actuelle->performance }}</b>Perf</div>
                            <div class="radar-note"><b>{{ $actuelle->accessibilite }}</b>Access.</div>
                            <div class="radar-note"><b>{{ $actuelle->bonnes_pratiques }}</b>Pratiques</div>
                            <div class="radar-note"><b>{{ $actuelle->seo }}</b>SEO</div>
                        </div>
                        @if ($ecart !== null && $ecart < 0)
                            <p class="radar-baisse mt-3">Performance {{ $ecart }} depuis la veille</p>
                        @elseif ($ecart !== null)
                            <p class="mt-3">Performance {{ $ecart >= 0 ? '+' : '' }}{{ $ecart }} depuis la veille</p>
                        @endif
                    @else
                        <p class="mt-4">Première mesure en attente.</p>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</x-app-layout>
