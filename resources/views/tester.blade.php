<x-guest-layout>
    <div class="max-w-xl mx-auto py-12 space-y-8">
        <h1 class="text-2xl font-bold">Tester un site</h1>

        <form method="POST" action="{{ route('tester.analyser') }}" class="flex gap-2">
            @csrf
            <input name="url" type="url" value="{{ old('url', $url ?? '') }}" required maxlength="2048" placeholder="https://" class="flex-1">
            <button type="submit">Tester</button>
        </form>
        @error('url') <p class="text-red-600">{{ $message }}</p> @enderror

        @if ($resultat)
            <section>
                <p>{{ $url }} · mobile</p>
                <ul>
                    <li>Performance : {{ $resultat['performance'] }}</li>
                    <li>Accessibilité : {{ $resultat['accessibilite'] }}</li>
                    <li>Bonnes pratiques : {{ $resultat['bonnes_pratiques'] }}</li>
                    <li>SEO : {{ $resultat['seo'] }}</li>
                </ul>
            </section>
        @endif
    </div>
</x-guest-layout>
