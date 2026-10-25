<x-app-layout>
    <x-slot name="header">
        <p class="surtitre">Sites</p>
        <h1 class="radar-titre titre-page">Ajouter un site</h1>
    </x-slot>

    <form method="POST" action="{{ route('sites.store') }}" class="carte-page formulaire">
        @csrf

        <div class="champ-groupe">
            <label for="nom">Nom</label>
            <input id="nom" name="nom" type="text" value="{{ old('nom') }}" required maxlength="80" class="champ">
            @error('nom') <p class="texte-erreur">{{ $message }}</p> @enderror
        </div>

        <div class="champ-groupe">
            <label for="url">Adresse du site</label>
            <input id="url" name="url" type="url" value="{{ old('url') }}" required maxlength="2048" class="champ">
            @error('url') <p class="texte-erreur">{{ $message }}</p> @enderror
        </div>

        <label class="case">
            <input type="checkbox" name="site_entier" value="1" @checked(old('site_entier'))>
            Site entier (+3 €/mois, toutes les pages du sitemap)
        </label>
        @error('site_entier') <p class="texte-erreur">{{ $message }}</p> @enderror

        <button type="submit" class="bouton-encre">Enregistrer</button>
    </form>
</x-app-layout>
