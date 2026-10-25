<x-app-layout>
    <x-slot name="header">
        <p class="surtitre">{{ $site->url }}@if ($site->site_entier) · Site entier @endif</p>
        <h1 class="radar-titre titre-page">{{ $site->nom }}</h1>
    </x-slot>

    @if (session('statut'))
        <p class="bandeau-exemple">{{ session('statut') }}</p>
    @endif

    <section class="carte-page">
        <h2 class="carte-libelle">Pages suivies ({{ $site->pages->count() }})</h2>

        @if ($site->pages->isEmpty())
            <p class="tdb2-discret">Aucune page pour l'instant. Ajoutez l'adresse d'une page : Radar la mesure dès la prochaine tournée.</p>
        @endif

        <ul class="pages-liste">
            @foreach ($site->pages as $page)
                <li>
                    <span class="carte-url">{{ $page->url }}</span>
                    @if ($site->site_entier)
                        <form method="POST" action="{{ route('pages.update', $page) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="quotidienne" value="0">
                            <label class="case">
                                <input type="checkbox" name="quotidienne" value="1" @checked($page->quotidienne)>
                                Mesure chaque jour
                            </label>
                            <button type="submit" class="lien-compte">Enregistrer</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('pages.destroy', $page) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="lien-compte">Retirer</button>
                    </form>
                </li>
            @endforeach
        </ul>

        <form method="POST" action="{{ route('sites.pages.store', $site) }}" class="formulaire-ligne">
            @csrf
            <label for="url-page" class="sr-only">Adresse de la page</label>
            <input id="url-page" name="url" type="url" required maxlength="2048" placeholder="https://" class="champ">
            <button type="submit" class="bouton-encre">Ajouter une page</button>
        </form>
        @error('url') <p class="texte-erreur">{{ $message }}</p> @enderror
    </section>

    @if ($site->site_entier)
        <section class="carte-page">
            <form method="POST" action="{{ route('sites.sitemap', $site) }}">
                @csrf
                <button type="submit" class="bouton-encre">Importer le sitemap</button>
            </form>
            @error('sitemap') <p class="texte-erreur">{{ $message }}</p> @enderror
        </section>
    @endif

    <form method="POST" action="{{ route('sites.destroy', $site) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="lien-compte texte-erreur">Supprimer le site</button>
    </form>
</x-app-layout>
