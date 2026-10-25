<x-guest-layout :titre="'Connexion · Radar'" :indexer="false">
    <h1 class="radar-titre titre-page">Connexion</h1>

    @if (session('status'))
        <p class="bandeau-exemple">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}" class="formulaire">
        @csrf

        <div class="champ-groupe">
            <label for="email">Adresse e-mail</label>
            <input id="email" class="champ" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email') <p class="texte-erreur">{{ $message }}</p> @enderror
        </div>

        <div class="champ-groupe">
            <label for="password">Mot de passe</label>
            <div class="champ-mdp">
                <input id="password" class="champ" type="password" name="password" required autocomplete="current-password">
                <button type="button" class="voir-mdp" aria-controls="password">Afficher</button>
            </div>
            @error('password') <p class="texte-erreur">{{ $message }}</p> @enderror
        </div>

        <label class="case">
            <input id="remember_me" type="checkbox" name="remember">
            Se souvenir de moi
        </label>

        <div class="auth-actions">
            <button type="submit" class="bouton-encre">Se connecter <span aria-hidden="true">→</span></button>
            <a class="lien-texte" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
        </div>
    </form>

    <p class="auth-autre">Pas encore de compte ? <a class="lien-texte" href="{{ route('register') }}">Créer un compte</a></p>
</x-guest-layout>
