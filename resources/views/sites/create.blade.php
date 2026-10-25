<x-app-layout>
    <div class="py-12 max-w-xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">Ajouter un site</h1>

        <form method="POST" action="{{ route('sites.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="nom">Nom</label>
                <input id="nom" name="nom" type="text" value="{{ old('nom') }}" required maxlength="80" class="block w-full">
                @error('nom') <p class="text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="url">Adresse du site</label>
                <input id="url" name="url" type="url" value="{{ old('url') }}" required maxlength="2048" class="block w-full">
                @error('url') <p class="text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label>
                    <input type="checkbox" name="site_entier" value="1" @checked(old('site_entier'))>
                    Site entier (+3 €/mois, toutes les pages du sitemap)
                </label>
                @error('site_entier') <p class="text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
</x-app-layout>
