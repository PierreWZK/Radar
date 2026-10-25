<x-app-layout>
    <x-slot name="header">
        <p class="surtitre">Sites</p>
        <h1 class="radar-titre titre-page">Mes sites</h1>
    </x-slot>

    <a class="bouton-encre" href="{{ route('sites.create') }}">Ajouter un site <span aria-hidden="true">→</span></a>

    <div class="cartes mt-8">
        @forelse ($sites as $site)
            <a class="carte-page carte-lien" href="{{ route('sites.show', $site) }}">
                <p class="carte-url">{{ $site->nom }}</p>
                <p class="carte-site">{{ $site->url }}@if ($site->site_entier) · Site entier @endif</p>
            </a>
        @empty
            <p>Aucun site pour le moment.</p>
        @endforelse
    </div>
</x-app-layout>
