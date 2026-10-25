<x-guest-layout :titre="'Confirmer le mot de passe · Radar'" :indexer="false">
    <h1 class="radar-titre titre-page">Confirmez votre mot de passe</h1>
    <p class="intro">Zone sécurisée : saisissez votre mot de passe pour continuer.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="formulaire">
        @csrf

        <div class="champ-groupe">
            <label for="password">Mot de passe</label>
            <div class="champ-mdp">
                <input id="password" class="champ" type="password" name="password" required autocomplete="current-password">
                <button type="button" class="voir-mdp" aria-controls="password">Afficher</button>
            </div>
            @error('password') <p class="texte-erreur">{{ $message }}</p> @enderror
        </div>

        <div class="auth-actions">
            <button type="submit" class="bouton-encre">Confirmer <span aria-hidden="true">→</span></button>
        </div>
    </form>
</x-guest-layout>
