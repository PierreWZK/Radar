<x-guest-layout :titre="'Créer un compte · Radar'" :indexer="false">
    <h1 class="radar-titre titre-page">Créer un compte</h1>
    <p class="intro">Un e-mail de confirmation vous sera envoyé avant tout suivi de site.</p>

    <form method="POST" action="{{ route('register') }}" class="formulaire">
        @csrf

        <div class="champ-groupe">
            <label for="name">Nom</label>
            <input id="name" class="champ" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name') <p class="texte-erreur">{{ $message }}</p> @enderror
        </div>

        <div class="champ-groupe">
            <label for="email">Adresse e-mail</label>
            <input id="email" class="champ" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email') <p class="texte-erreur">{{ $message }}</p> @enderror
        </div>

        <div class="champ-groupe">
            <label for="password">Mot de passe</label>
            <div class="champ-mdp">
                <input id="password" class="champ" type="password" name="password" required autocomplete="new-password">
                <button type="button" class="voir-mdp" aria-controls="password">Afficher</button>
            </div>
            @error('password') <p class="texte-erreur">{{ $message }}</p> @enderror
        </div>

        <div class="champ-groupe">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <div class="champ-mdp">
                <input id="password_confirmation" class="champ" type="password" name="password_confirmation" required autocomplete="new-password">
                <button type="button" class="voir-mdp" aria-controls="password_confirmation">Afficher</button>
            </div>
            @error('password_confirmation') <p class="texte-erreur">{{ $message }}</p> @enderror
        </div>

        <div class="auth-actions">
            <button type="submit" class="bouton-encre">Créer mon compte <span aria-hidden="true">→</span></button>
            <a class="lien-texte" href="{{ route('login') }}">J'ai déjà un compte</a>
        </div>
    </form>
</x-guest-layout>
