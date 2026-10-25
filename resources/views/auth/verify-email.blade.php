<x-guest-layout :titre="'Confirmez votre e-mail · Radar'" :indexer="false">
    <h1 class="radar-titre titre-page">Confirmez votre adresse</h1>
    <p class="intro">Merci pour votre inscription ! Cliquez sur le lien que nous venons de vous envoyer. Pas reçu ? Nous vous en renvoyons un.</p>

    @if (session('status') == 'verification-link-sent')
        <p class="bandeau-exemple">Un nouveau lien de confirmation vient d'être envoyé à l'adresse indiquée lors de l'inscription.</p>
    @endif

    <div class="auth-actions">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="bouton-encre">Renvoyer l'e-mail <span aria-hidden="true">→</span></button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="lien-texte">Se déconnecter</button>
        </form>
    </div>
</x-guest-layout>
