<x-guest-layout :titre="'Nouveau mot de passe · Radar'" :indexer="false">
    <h1 class="radar-titre titre-page">Nouveau mot de passe</h1>

    <form method="POST" action="{{ route('password.store') }}" class="formulaire">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="champ-groupe">
            <label for="email">Adresse e-mail</label>
            <input id="email" class="champ" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email') <p class="texte-erreur">{{ $message }}</p> @enderror
        </div>

        <div class="champ-groupe">
            <label for="password">Nouveau mot de passe</label>
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
            <button type="submit" class="bouton-encre">Enregistrer le mot de passe <span aria-hidden="true">→</span></button>
        </div>
    </form>
</x-guest-layout>
