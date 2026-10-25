<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <x-seo :titre="$attributes->get('titre', 'Radar')" :description="$attributes->get('description', 'Radar mesure vos pages avec PageSpeed et vous alerte dès qu’une note baisse.')" :chemin="$attributes->get('chemin', '/')" :indexer="$attributes->get('indexer', true)" />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:800|hanken-grotesk:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="espace-corps">
        <div class="auth">
            <a class="espace-logo auth-logo" href="{{ url('/') }}">
                <x-marque />
            </a>

            <main class="auth-carte">
                {{ $slot }}
            </main>

            <p class="auth-pied">Radar · Jour 13 du défi <a href="https://projectp.fr/">30 jours, 30 projets</a></p>
        </div>

        <script>
            document.querySelectorAll('.voir-mdp').forEach((bouton) => {
                bouton.addEventListener('click', () => {
                    const champ = document.getElementById(bouton.getAttribute('aria-controls'));
                    const masque = champ.type === 'password';
                    champ.type = masque ? 'text' : 'password';
                    bouton.textContent = masque ? 'Masquer' : 'Afficher';
                });
            });
        </script>
    </body>
</html>
