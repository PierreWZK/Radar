<x-app-layout>
    <x-slot name="header">
        <p class="surtitre">Alertes</p>
        <h1 class="radar-titre titre-page">Baisses détectées</h1>
    </x-slot>

    <div class="alertes">
        @forelse ($alertes as $alerte)
            <article class="carte-page">
                <p class="carte-site">{{ $alerte->page->site->nom }} · {{ $alerte->created_at->format('d/m/Y H:i') }}</p>
                <a class="carte-url" href="{{ route('pages.show', $alerte->page) }}">{{ $alerte->page->url }}</a>
                <p class="mt-4"><span class="radar-baisse">{{ $alerte->performance_avant }} → {{ $alerte->performance_apres }}</span> {{ $alerte->raison }}</p>

                @if (! empty($alerte->conseils))
                    <ol class="conseils">
                        @foreach ($alerte->conseils as $conseil)
                            <li><span>{{ $conseil['titre'] }}</span><b>−{{ round($conseil['gain_ms'] / 1000, 1) }} s</b></li>
                        @endforeach
                    </ol>
                @endif
            </article>
        @empty
            <p>Aucune alerte pour le moment.</p>
        @endforelse
    </div>
</x-app-layout>
