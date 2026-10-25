<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Exemple de tableau de bord · Radar</title>
        <meta name="description" content="Un exemple de tableau de bord Radar, avec des sites fictifs.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:800|hanken-grotesk:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="espace-corps">
        <header class="exemple-entete">
            <a class="espace-logo" href="{{ url('/') }}">
                <x-marque />
            </a>
            <div class="exemple-compte">
                <a class="lien-nav" href="{{ route('login') }}">Se connecter</a>
                <a class="bouton-encre" href="{{ route('register') }}">Créer un compte <span aria-hidden="true">→</span></a>
            </div>
        </header>

        <main class="espace-contenu">
            <p class="bandeau-exemple">Données d'exemple : ces sites sont fictifs, rien n'est mesuré ici.</p>

            <header class="espace-entete">
                <h1 class="radar-titre titre-page">Vos pages, d'un coup d'œil</h1>
            </header>

            <div class="cartes">
                @foreach ($cartes as $carte)
                    @include('dashboard._carte', ['carte' => $carte, 'lien' => false])
                @endforeach
            </div>

            <div class="exemple-appel">
                <p>Ce tableau se remplit avec vos vraies pages dès la première mesure.</p>
                <a class="bouton-magenta" href="{{ route('register') }}">Créer un compte gratuit <span aria-hidden="true">→</span></a>
            </div>
        </main>
    </body>
</html>
