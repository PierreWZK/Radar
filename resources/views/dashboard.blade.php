<x-app-layout>
    <x-slot name="header">
        <p class="surtitre">Tableau de bord</p>
        <h1 class="radar-titre titre-page">Vos pages</h1>
    </x-slot>

    @if ($cartes->isEmpty())
        <p>Aucune page suivie. <a class="underline" href="{{ route('sites.create') }}">Ajoutez un site</a> pour commencer.</p>
    @endif

    <div class="cartes">
        @foreach ($cartes as $carte)
            @include('dashboard._carte', ['carte' => $carte, 'lien' => true])
        @endforeach
    </div>
</x-app-layout>
