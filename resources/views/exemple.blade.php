<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <x-seo titre="Exemple de tableau de bord · Radar" description="Un exemple de tableau de bord Radar, avec des données fictives." chemin="/exemple" />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:800|hanken-grotesk:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="espace-corps espace-sombre">
        <div class="espace">
            @include('layouts._barre', ['exemple' => true])

            <div class="espace-contenu">
                <main>
                    <h1 class="sr-only">Exemple de tableau de bord</h1>
                    <p class="bandeau-exemple">Données d'exemple : ce site est fictif, rien n'est mesuré ici. <a class="underline" href="{{ route('register') }}">Créer un compte</a> pour suivre vos vraies pages.</p>

                    @include('dashboard._vue', ['vue' => $vue])
                </main>
            </div>
        </div>
    </body>
</html>
