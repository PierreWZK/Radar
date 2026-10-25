<x-guest-layout :titre="'Mot de passe oublié · Radar'" :indexer="false">
    <h1 class="radar-titre titre-page">Mot de passe oublié</h1>
    <p class="intro">Indiquez votre adresse : nous vous enverrons un lien pour en choisir un nouveau.</p>

    @if (session('status'))
        <p class="bandeau-exemple">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="formulaire">
        @csrf

        <div class="champ-groupe">
            <label for="email">Adresse e-mail</label>
            <input id="email" class="champ" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <p class="texte-erreur">{{ $message }}</p> @enderror
        </div>

        <div class="auth-actions">
            <button type="submit" class="bouton-encre">Envoyer le lien <span aria-hidden="true">→</span></button>
        </div>
    </form>
</x-guest-layout>
