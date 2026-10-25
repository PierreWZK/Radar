<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Radar · surveillez la note PageSpeed de vos pages</title>
        <meta name="description" content="Radar mesure vos pages chaque jour avec PageSpeed et vous prévient dès qu'un déploiement fait chuter la note, avec des conseils pour la remonter.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:800|hanken-grotesk:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased accueil">
        <header class="accueil-heros">
            <nav class="accueil-nav" aria-label="Navigation principale">
                <a class="radar-titre text-2xl accueil-lien" href="{{ url('/') }}">Radar</a>
                <div class="accueil-liens">
                    <a class="accueil-lien accueil-ancre" href="#fonctionnement">Fonctionnement</a>
                    <a class="accueil-lien accueil-ancre" href="#offres">Offres</a>
                    <a class="accueil-lien accueil-ancre" href="#faq">FAQ</a>
                    @auth
                        <a class="accueil-lien" href="{{ route('dashboard') }}">Tableau de bord</a>
                    @else
                        <a class="accueil-lien" href="{{ route('login') }}">Connexion</a>
                        <a class="accueil-lien" href="{{ route('register') }}">Créer un compte</a>
                    @endauth
                </div>
            </nav>

            <div class="accueil-texte">
                <h1 class="radar-titre accueil-h1">Votre note a baissé. Voici pourquoi.</h1>
                <p class="accueil-chapo">Radar mesure vos pages chaque jour et vous prévient dès qu'un déploiement fait chuter la note, avec des conseils pour la&nbsp;remonter.</p>
                <div class="accueil-actions">
                    <a class="radar-bouton" href="{{ route('tester') }}">Tester mon site</a>
                    <span>Gratuit, sans compte, 3 tests par jour.</span>
                </div>
            </div>

            {{-- Zones = seuils Lighthouse (centre 100, anneau 90, anneau 50) ; chaque note est un écho à sa distance du centre --}}
            <figure class="accueil-radar">
                <svg class="radar-ecran" viewBox="0 0 720 720" role="img" aria-label="Exemple de radar : performance 94 en baisse de 3, accessibilité 100, bonnes pratiques 96, SEO 92">
                    <circle cx="360" cy="360" r="360" class="r-loin"/>
                    <circle cx="360" cy="360" r="300" class="r-milieu"/>
                    <circle cx="360" cy="360" r="200" class="r-pres"/>
                    <circle cx="360" cy="360" r="350" class="r-graduation" stroke-width="12" stroke-dasharray="2 28.54"/>
                    <text x="368" y="156" class="zone">90</text>
                    <text x="368" y="56" class="zone">50</text>
                    <g class="balayage">
                        <path d="M360 360 L540 48.2 A360 360 0 0 1 614.6 105.4 Z" class="r-encre" opacity=".03"/>
                        <path d="M360 360 L614.6 105.4 A360 360 0 0 1 671.8 180 Z" class="r-encre" opacity=".06"/>
                        <path d="M360 360 L671.8 180 A360 360 0 0 1 707.7 266.8 Z" class="r-encre" opacity=".1"/>
                        <path d="M360 360 L707.7 266.8 A360 360 0 0 1 720 360 Z" class="r-encre" opacity=".16"/>
                    </g>
                    <line x1="390" y1="308" x2="420" y2="256" class="r-trace" stroke-width="3" stroke-dasharray="4 5"/>
                    <circle cx="390" cy="308" r="7" class="r-trace" fill="none" stroke-width="3"/>
                    <circle class="ping r-alerte" style="animation-delay:5s" cx="420" cy="256" r="12"/>
                    <circle cx="420" cy="256" r="12" class="r-alerte"/>
                    <text x="440" y="250" class="echo baisse">Perf 94 −3</text>
                    <circle class="ping r-encre" style="animation-delay:.33s" cx="398" cy="374" r="8"/>
                    <circle cx="398" cy="374" r="8" class="r-encre"/>
                    <text x="414" y="398" class="echo">Access. 100</text>
                    <circle class="ping r-encre" style="animation-delay:2s" cx="320" cy="429" r="8"/>
                    <circle cx="320" cy="429" r="8" class="r-encre"/>
                    <text x="250" y="462" class="echo">Pratiques 96</text>
                    <circle class="ping r-encre" style="animation-delay:3.33s" cx="210" cy="305" r="8"/>
                    <circle cx="210" cy="305" r="8" class="r-encre"/>
                    <text x="150" y="285" class="echo">SEO 92</text>
                    <circle cx="360" cy="360" r="5" class="r-encre"/>
                </svg>
                <figcaption class="accueil-legende">Exemple : une page mobile, le lendemain d'un déploiement.</figcaption>
            </figure>
        </header>

        <main>
            <section id="fonctionnement" class="accueil-bande accueil-sable accueil-duo">
                <div>
                    <h2 class="radar-titre accueil-h2">Trois étapes, aucune configuration.</h2>
                    <ol class="accueil-etapes">
                        <li><span><b>Ajoutez vos pages.</b> Collez une adresse, ou importez le sitemap de votre site.</span></li>
                        <li><span><b>Radar les mesure.</b> PageSpeed, trois passages dont on garde la médiane, sur mobile et sur&nbsp;ordinateur.</span></li>
                        <li><span><b>Vous êtes prévenu.</b> Un e-mail dès que la note baisse, avec ce qu'il faut corriger en&nbsp;premier.</span></li>
                    </ol>
                </div>
                <article class="radar-carte accueil-alerte">
                    <p class="text-sm">Exemple d'alerte</p>
                    <p class="font-semibold">monsite.fr · mobile</p>
                    <div class="grid grid-cols-4 gap-2 my-5">
                        <div class="radar-note radar-note-baisse"><b>94</b>Perf −3</div>
                        <div class="radar-note"><b>100</b>Access.</div>
                        <div class="radar-note"><b>96</b>Pratiques</div>
                        <div class="radar-note"><b>92</b>SEO</div>
                    </div>
                    <p><b>LCP 2,9&nbsp;s</b> (était 2,1&nbsp;s) depuis hier. Conseil : compresser les images de l'accueil, gain estimé 1,2&nbsp;s.</p>
                </article>
            </section>

            <section id="mesures" class="accueil-bande">
                <h2 class="radar-titre accueil-h2">Ce que Radar surveille.</h2>
                <p class="accueil-intro">Les quatre notes de Lighthouse, de 0 à 100, et les trois signaux que Google utilise pour juger l'expérience d'une&nbsp;page.</p>
                <div class="accueil-grille">
                    <article class="radar-carte"><h3 class="accueil-h3">Performance</h3><p>La vitesse d'affichage. C'est elle qui chute après un déploiement : une image trop lourde, un script de&nbsp;plus.</p></article>
                    <article class="radar-carte"><h3 class="accueil-h3">Accessibilité</h3><p>Contrastes, textes alternatifs, titres : ce qui rend la page utilisable par&nbsp;tous.</p></article>
                    <article class="radar-carte"><h3 class="accueil-h3">Bonnes pratiques</h3><p>HTTPS, erreurs dans la console, bibliothèques obsolètes.</p></article>
                    <article class="radar-carte"><h3 class="accueil-h3">SEO</h3><p>Ce que les moteurs de recherche lisent : titre, description, liens explorables.</p></article>
                </div>

                <table class="accueil-seuils">
                    <caption>Seuils des Core Web Vitals selon Google</caption>
                    <thead>
                        <tr><th scope="col">Signal</th><th scope="col">Bon</th><th scope="col">À améliorer</th><th scope="col">Mauvais</th></tr>
                    </thead>
                    <tbody>
                        <tr><th scope="row">LCP <span>affichage du plus gros élément</span></th><td>≤ 2,5&nbsp;s</td><td>≤ 4&nbsp;s</td><td>&gt; 4&nbsp;s</td></tr>
                        <tr><th scope="row">CLS <span>stabilité de la mise en page</span></th><td>≤ 0,1</td><td>≤ 0,25</td><td>&gt; 0,25</td></tr>
                        <tr><th scope="row">INP <span>réaction au clic</span></th><td>≤ 200&nbsp;ms</td><td>≤ 500&nbsp;ms</td><td>&gt; 500&nbsp;ms</td></tr>
                    </tbody>
                </table>
            </section>

            <section class="accueil-bande accueil-encre">
                <h2 class="radar-titre accueil-h2">Une alerte quand ça compte, pas à chaque variation.</h2>
                <div class="accueil-grille accueil-grille-3">
                    <div><p class="accueil-chiffre">−5</p><p>points de performance sous la médiane des 7 derniers jours : Radar vous écrit.</p></div>
                    <div><p class="accueil-chiffre">3</p><p>mesures à chaque passage. On garde la médiane, pour ne pas crier au loup sur un réseau&nbsp;lent.</p></div>
                    <div><p class="accueil-chiffre">1</p><p>e-mail par page et par jour au maximum. Un LCP, CLS ou INP qui change de catégorie déclenche aussi&nbsp;l'alerte.</p></div>
                </div>
            </section>

            <section class="accueil-bande accueil-sable accueil-duo">
                <div>
                    <h2 class="radar-titre accueil-h2">Affichez votre note.</h2>
                    <p class="accueil-intro">Chaque page suivie a son badge, mis à jour après chaque mesure. Collez-le dans un README ou au pied de votre&nbsp;site.</p>
                </div>
                <div class="radar-carte accueil-alerte">
                    @include('badges.performance', ['texte' => '94', 'couleur' => \App\Services\Badge::VERT])
                    <pre class="accueil-code"><code>&lt;img src="https://radar.projectp.fr/badge/42.svg" alt="Note de performance"&gt;</code></pre>
                </div>
            </section>

            <section id="offres" class="accueil-bande">
                <h2 class="radar-titre accueil-h2">Offres.</h2>
                <p class="accueil-intro">Commencez gratuitement. Le paiement en ligne des offres Solo et Agence arrive&nbsp;bientôt.</p>
                <div class="accueil-grille accueil-grille-3">
                    <article class="radar-carte accueil-offre">
                        <h3 class="accueil-h3">Gratuit</h3>
                        <p class="accueil-prix">0 €</p>
                        <ul>
                            <li>{{ config('offres.gratuit.sites') }} site, {{ config('offres.gratuit.pages_par_site') }} page</li>
                            <li>Une mesure mobile par semaine</li>
                            <li>Alerte par e-mail</li>
                            <li>3 conseils si la note passe sous 50</li>
                        </ul>
                        @auth
                            <a class="radar-bouton" href="{{ route('dashboard') }}">Mon tableau de bord</a>
                        @else
                            <a class="radar-bouton" href="{{ route('register') }}">Créer un compte</a>
                        @endauth
                    </article>
                    <article class="radar-carte accueil-offre accueil-offre-phare">
                        <h3 class="accueil-h3">Solo</h3>
                        <p class="accueil-prix">4 € <span>/ mois</span></p>
                        <ul>
                            <li>{{ config('offres.solo.sites') }} sites, {{ config('offres.solo.pages_par_site') }} pages par site</li>
                            <li>Mesure quotidienne, mobile et ordinateur</li>
                            <li>Courbe sur 90 jours et Core Web Vitals</li>
                            <li>10 conseils à chaque alerte</li>
                        </ul>
                    </article>
                    <article class="radar-carte accueil-offre">
                        <h3 class="accueil-h3">Agence</h3>
                        <p class="accueil-prix">12 € <span>/ mois</span></p>
                        <ul>
                            <li>{{ config('offres.agence.sites') }} sites, {{ config('offres.agence.pages_par_site') }} pages par site</li>
                            <li>Tout ce que contient Solo</li>
                            <li>De quoi suivre les sites de vos clients</li>
                        </ul>
                    </article>
                </div>
                <p class="accueil-option"><b>Option Site entier, +3 € par mois et par site :</b> Radar importe votre sitemap et suit jusqu'à 200 pages, mesurées à tour de rôle chaque&nbsp;semaine.</p>
            </section>

            <section id="faq" class="accueil-bande accueil-sable">
                <h2 class="radar-titre accueil-h2">Questions fréquentes</h2>
                <div class="accueil-faq">
                    <details>
                        <summary>D'où viennent les notes ?</summary>
                        <p>De PageSpeed Insights, l'outil de Google qui fait tourner Lighthouse. Radar l'appelle depuis son serveur : vous n'avez aucune clé à créer.</p>
                    </details>
                    <details>
                        <summary>Pourquoi ma note change d'un jour à l'autre ?</summary>
                        <p>Le réseau et la charge du serveur varient. C'est pour ça que Radar fait trois mesures et compare à la médiane de la semaine plutôt qu'à la veille.</p>
                    </details>
                    <details>
                        <summary>Que contient l'e-mail d'alerte ?</summary>
                        <p>La page concernée, l'ancienne et la nouvelle note, ce qui a changé (LCP, CLS ou INP) et les conseils de Lighthouse classés par gain de temps estimé.</p>
                    </details>
                    <details>
                        <summary>Faut-il installer quelque chose sur mon site ?</summary>
                        <p>Non. Radar visite la page comme un visiteur. Rien à ajouter au code, sauf le badge si vous le voulez.</p>
                    </details>
                    <details>
                        <summary>Puis-je tester sans créer de compte ?</summary>
                        <p>Oui, trois tests par jour depuis la page « Tester mon site ». Le compte sert à suivre vos pages dans le temps et à recevoir les alertes.</p>
                    </details>
                </div>
            </section>

            <section class="accueil-bande accueil-final">
                <h2 class="radar-titre accueil-h2">Quelle est la note de votre accueil ?</h2>
                <a class="radar-bouton" href="{{ route('tester') }}">Tester mon site</a>
            </section>
        </main>

        <footer class="accueil-pied">
            <span>Radar · Jour 13 du défi <a href="https://projectp.fr/">30 jours, 30 projets</a></span>
            <span>Fait avec Laravel · <a href="https://github.com/PierreWZK/Radar" target="_blank" rel="noopener">Code sur GitHub ↗</a></span>
        </footer>
    </body>
</html>
