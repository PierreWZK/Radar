<x-app-layout>
    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
        <h1 class="text-2xl font-bold">{{ $site->nom }}</h1>
        <p>{{ $site->url }}@if ($site->site_entier) · Site entier @endif</p>

        @if (session('statut'))
            <p>{{ session('statut') }}</p>
        @endif

        <section>
            <h2 class="font-semibold mb-3">Pages suivies ({{ $site->pages->count() }})</h2>
            <ul class="space-y-2">
                @foreach ($site->pages as $page)
                    <li class="flex gap-4">
                        <span>{{ $page->url }}</span>
                        <form method="POST" action="{{ route('pages.destroy', $page) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Retirer</button>
                        </form>
                    </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('sites.pages.store', $site) }}" class="mt-4 flex gap-2">
                @csrf
                <input name="url" type="url" required maxlength="2048" placeholder="https://" class="flex-1">
                <button type="submit">Ajouter une page</button>
            </form>
            @error('url') <p class="text-red-600">{{ $message }}</p> @enderror
        </section>

        @if ($site->site_entier)
            <form method="POST" action="{{ route('sites.sitemap', $site) }}">
                @csrf
                <button type="submit">Importer le sitemap</button>
            </form>
            @error('sitemap') <p class="text-red-600">{{ $message }}</p> @enderror
        @endif

        <form method="POST" action="{{ route('sites.destroy', $site) }}">
            @csrf
            @method('DELETE')
            <button type="submit">Supprimer le site</button>
        </form>
    </div>
</x-app-layout>
