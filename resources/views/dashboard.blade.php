<x-app-layout>
    <h1 class="sr-only">Tableau de bord</h1>

    @if ($vue)
        @include('dashboard._vue', ['vue' => $vue])
    @else
        <section class="tdb2-carte tdb2-vide">
            <h2 class="radar-titre titre-page">Bienvenue sur Radar.</h2>
            <p>Aucune page suivie pour le moment. Ajoutez un site : Radar le mesure et vous prévient dès que la note baisse.</p>
            <a class="bouton-encre" href="{{ route('sites.create') }}">Ajouter un site <span aria-hidden="true">→</span></a>
        </section>
    @endif
</x-app-layout>
