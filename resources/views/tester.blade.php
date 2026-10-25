<x-guest-layout :titre="'Tester un site · Radar'" :description="'Testez gratuitement les notes PageSpeed d’une page, sans compte.'" :chemin="'/tester'">
    <h1 class="radar-titre titre-page">Tester un site</h1>
    <p class="intro">Collez une adresse : Radar mesure la page avec PageSpeed, sans compte. Trois tests par jour et par adresse IP.</p>

    <form method="POST" action="{{ route('tester.analyser') }}" class="formulaire-ligne">
        @csrf
        <label for="url" class="sr-only">Adresse de la page</label>
        <input id="url" class="champ" name="url" type="url" value="{{ old('url', $url ?? '') }}" required maxlength="2048" placeholder="https://">
        <button type="submit" class="bouton-encre">Tester <span aria-hidden="true">→</span></button>
    </form>
    @error('url') <p class="texte-erreur">{{ $message }}</p> @enderror

    @if ($resultat)
        <section class="carte-page resultat-test" aria-labelledby="resultat-titre">
            <h2 id="resultat-titre" class="carte-libelle">{{ $url }} · mobile</h2>
            <div class="carte-notes">
                <div class="radar-note"><b>{{ $resultat['performance'] }}</b>Perf</div>
                <div class="radar-note"><b>{{ $resultat['accessibilite'] }}</b>Access.</div>
                <div class="radar-note"><b>{{ $resultat['bonnes_pratiques'] }}</b>Pratiques</div>
                <div class="radar-note"><b>{{ $resultat['seo'] }}</b>SEO</div>
            </div>
            <p class="intro">Pour suivre cette page dans le temps et recevoir une alerte en cas de baisse, <a class="lien-texte" href="{{ route('register') }}">créez un compte</a>.</p>
        </section>
    @endif
</x-guest-layout>
