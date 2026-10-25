<x-app-layout>
    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">Mes sites</h1>

        <a href="{{ route('sites.create') }}" class="underline">Ajouter un site</a>

        <ul class="mt-6 space-y-3">
            @forelse ($sites as $site)
                <li><a class="underline" href="{{ route('sites.show', $site) }}">{{ $site->nom }}</a> · {{ $site->url }}</li>
            @empty
                <li>Aucun site pour le moment.</li>
            @endforelse
        </ul>
    </div>
</x-app-layout>
