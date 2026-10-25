@php
    // Jeu du jour : un défi par jour, le même pour tous, tiré de cette liste (rien en base).
    $defis = [
        [
            'page' => "l'accueil d'une boulangerie", 'note' => 78, 'meilleure' => 0,
            'options' => [['Compresser les images', 12], ['Différer le chargement des scripts', 8], ['Optimiser la mise en cache', 6]],
            'indice' => "L'image d'accueil pèse 2,4 Mo et c'est elle qui fixe le LCP.",
            'explication' => "L'image d'accueil est le plus gros élément de l'écran : en WebP compressé, le LCP repasse sous 2,5 s. Les scripts et le cache aident, mais moins.",
        ],
        [
            'page' => 'un blog avec chat et publicités', 'note' => 64, 'meilleure' => 1,
            'options' => [['Compresser les images', 5], ['Différer les scripts tiers', 14], ['Précharger la police', 3]],
            'indice' => 'Cinq scripts tiers bloquent le rendu au chargement.',
            'explication' => "Le chat et les publicités occupent le fil principal. Chargés avec defer ou au premier clic, ils laissent la page s'afficher avant eux.",
        ],
        [
            'page' => 'une boutique en ligne', 'note' => 71, 'meilleure' => 2,
            'options' => [['Minifier le CSS', 3], ["Changer d'hébergeur", 4], ['Réserver la place des images', 9]],
            'indice' => 'Le CLS vaut 0,32 : les vignettes poussent le texte en arrivant.',
            'explication' => 'Des attributs width et height, ou un aspect-ratio, réservent la place avant le chargement : le CLS repasse sous 0,1.',
        ],
        [
            'page' => 'un site vitrine sous WordPress', 'note' => 58, 'meilleure' => 0,
            'options' => [['Activer un cache de pages', 15], ['Passer les images en lazy-loading', 6], ['Supprimer une police', 4]],
            'indice' => 'Le serveur met 1,8 s à envoyer le premier octet.',
            'explication' => 'Chaque visite reconstruit la page en PHP. Un cache de pages sert une version prête : la réponse tombe sous 200 ms.',
        ],
        [
            'page' => 'une application de réservation', 'note' => 69, 'meilleure' => 1,
            'options' => [['Ajouter un CDN', 5], ['Découper le JavaScript', 11], ['Compresser les images', 4]],
            'indice' => 'Un seul fichier JavaScript de 900 Ko s\'exécute avant le premier clic.',
            'explication' => "Ne charger que le code de la page affichée allège le fil principal : l'INP et le temps de blocage baissent nettement.",
        ],
    ];
    $jour = intdiv(now()->startOfDay()->timestamp, 86400);
    $defi = $defis[$jour % count($defis)];
@endphp
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Radar · la santé de vos sites, semaine après semaine</title>
        <meta name="description" content="Radar mesure vos pages avec PageSpeed et vous prévient dès qu'une note baisse après un déploiement, avec des conseils concrets pour la remonter.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:800|hanken-grotesk:400,500,600,700|caveat:500&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased accueil">
        <header class="heros">
            <nav class="heros-nav" aria-label="Navigation principale">
                <a class="logo" href="{{ url('/') }}">
                    <svg class="logo-marque" viewBox="0 0 40 40" aria-hidden="true">
                        <circle cx="20" cy="20" r="19" class="lm-fond"/>
                        <circle cx="20" cy="20" r="12" class="lm-anneau"/>
                        <circle cx="20" cy="20" r="6" class="lm-anneau"/>
                        <path d="M20 20 L20 1 A19 19 0 0 1 36.5 10.5 Z" class="lm-balayage"/>
                        <circle cx="27" cy="12" r="2.6" class="lm-echo"/>
                    </svg>
                    Radar
                </a>
                <div class="heros-ancres">
                    <a href="#fonctionnalites">Fonctionnalités</a>
                    <a href="#tarifs">Tarifs</a>
                    <a href="{{ route('exemple') }}">Exemples</a>
                    <a href="#faq">FAQ</a>
                </div>
                <div class="heros-compte">
                    @auth
                        <a class="bouton-encre" href="{{ route('dashboard') }}">Tableau de bord <span aria-hidden="true">→</span></a>
                    @else
                        <a class="lien-nav" href="{{ route('login') }}">Se connecter</a>
                        <a class="bouton-encre" href="{{ route('register') }}">Créer un compte <span aria-hidden="true">→</span></a>
                    @endauth
                </div>
            </nav>

            <svg class="heros-sol" viewBox="0 0 1440 90" preserveAspectRatio="none" aria-hidden="true"><path d="M0 60 C 240 30, 520 70, 820 50 C 1060 34, 1260 20, 1440 30 L1440 90 L0 90 Z" class="d-dune"/></svg>

            <div class="heros-grille">
                <div class="heros-texte">
                    <h1 class="heros-titre">
                        La santé de vos sites,
                        <span class="heros-titre-accent">semaine après semaine.
                            <svg class="soulignement" viewBox="0 0 400 24" preserveAspectRatio="none" aria-hidden="true"><path d="M4 18 C 90 6, 200 4, 396 10 M30 22 C 120 14, 220 12, 300 14"/></svg>
                        </span>
                    </h1>
                    <p class="heros-chapo">Radar surveille vos sites et vous alerte dès qu'une note baisse. Performance, accessibilité, bonnes pratiques et SEO : tout est mesuré et expliqué, sans&nbsp;jargon.</p>
                    <ul class="heros-atouts">
                        <li>Mesures quotidiennes</li>
                        <li>Alertes après un déploiement</li>
                        <li>Conseils concrets</li>
                    </ul>
                    <div class="heros-actions">
                        <a class="bouton-encre bouton-grand" href="{{ route('tester') }}">Tester gratuitement <span aria-hidden="true">→</span></a>
                        <a class="lien-lecture" href="{{ route('exemple') }}">Voir un exemple
                            <svg viewBox="0 0 16 16" aria-hidden="true"><circle cx="8" cy="8" r="8"/><path d="M6.5 5 L11 8 L6.5 11 Z" fill="#fff"/></svg>
                        </a>
                    </div>
                </div>

                <figure class="scene" id="scene" role="img" aria-label="Exemple de tableau de bord Radar : la note Performance passe de 92 à 84 après un déploiement, une alerte apparaît avec deux conseils.">
                    {{-- Décor dessiné : soleil, orbite du radar, dunes, montagnes, phare --}}
                    <svg class="scene-decor" viewBox="0 0 760 540" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
                        <circle cx="420" cy="230" r="160" class="d-soleil"/>
                        <path d="M30 250 C 110 40, 520 -30, 720 130" class="d-orbite"/>
                        <circle cx="250" cy="74" r="13" class="d-alerte"/>
                        <path d="M395 92 l10 -14 M410 96 l14 -10" class="d-traits"/>
                        <path d="M0 540 C 120 520, 260 456, 430 440 C 560 428, 660 396, 760 372 L760 540 Z" class="d-dune"/>
                        <path d="M120 540 C 260 500, 400 498, 560 482 C 650 474, 710 462, 760 452 L760 540 Z" class="d-dune-claire"/>
                        <path d="M30 540 L70 440 L92 460 L140 352 L230 520 Z" class="d-encre"/>
                        <path d="M140 352 L160 528 L230 520 Z" class="d-encre-face"/>
                        <path d="M560 470 L652 318 L690 368 L760 270 L760 470 Z" class="d-encre"/>
                        <path d="M652 318 L640 470 L560 470 Z" class="d-encre-face"/>
                        <path d="M600 448 L760 384 L760 402 L622 456 Z" class="d-alerte"/>
                        <g class="d-phare">
                            <path d="M702 300 L709 138 L729 138 L736 300 Z" class="d-blanc"/>
                            <path d="M706 210 L733 210 L734 236 L705 236 Z M708 160 L730 160 L731 184 L707 184 Z M704 262 L735 262 L736 288 L703 288 Z" class="d-alerte"/>
                            <rect x="702" y="128" width="34" height="10" rx="2" class="d-encre"/>
                            <rect x="709" y="106" width="20" height="22" class="d-encre"/>
                            <rect x="713" y="110" width="12" height="14" class="d-vitre"/>
                            <path d="M705 106 L719 90 L733 106 Z" class="d-encre"/>
                            <path d="M688 92 l-12 -8 M690 112 l-16 0 M748 92 l12 -8 M748 112 l16 0" class="d-rayons"/>
                        </g>
                    </svg>

                    <div class="tdb" aria-hidden="true">
                        <div class="tdb-barre">
                            <svg class="tdb-logo" viewBox="0 0 40 40"><circle cx="20" cy="20" r="19" class="lm-fond-clair"/><path d="M20 20 L20 1 A19 19 0 0 1 36.5 10.5 Z" class="lm-balayage"/><circle cx="27" cy="12" r="2.6" class="lm-echo"/></svg>
                            <span class="tdb-menu actif">Tableau de bord</span>
                            <span class="tdb-menu">Mes sites</span>
                            <span class="tdb-menu">Alertes</span>
                            <span class="tdb-menu">Paramètres</span>
                        </div>
                        <div class="tdb-panneau">
                            <div class="tdb-haut">
                                <span class="tdb-champ">votresite.fr</span>
                                <span class="tdb-champ tdb-date">Aujourd'hui</span>
                            </div>
                            <div class="tdb-carte tdb-notes">
                                <div class="anneau" data-note="84">
                                    <svg viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" class="anneau-piste"/><circle cx="50" cy="50" r="40" class="anneau-arc" pathLength="100" style="--note: 84"/></svg>
                                    <span class="anneau-chiffre">84</span>
                                    <span class="anneau-balayage"></span>
                                </div>
                                <ul class="tdb-lignes">
                                    <li class="tdb-ligne ligne-perf"><i class="pastille p-alerte">P</i>Performance<b class="valeur-perf">84</b><em class="ecart-perf baisse">−8</em></li>
                                    <li class="tdb-ligne"><i class="pastille p-bon">A</i>Accessibilité<b>96</b><em>+1</em></li>
                                    <li class="tdb-ligne"><i class="pastille p-moyen">B</i>Bonnes pratiques<b>94</b><em>+3</em></li>
                                    <li class="tdb-ligne"><i class="pastille p-calme">S</i>SEO<b>90</b><em>+1</em></li>
                                </ul>
                            </div>
                            <div class="tdb-bas">
                                <div class="tdb-carte tdb-courbes">
                                    <p class="tdb-titre">Évolution des notes <span>(90 jours)</span></p>
                                    <svg viewBox="0 0 200 70" preserveAspectRatio="none">
                                        <path d="M0 18 H200 M0 38 H200 M0 58 H200" class="courbe-grille"/>
                                        <polyline class="courbe c-bon" pathLength="1" points="0,22 20,20 40,23 60,19 80,21 100,18 120,20 140,17 160,19 180,18 200,17"/>
                                        <polyline class="courbe c-moyen" pathLength="1" points="0,28 20,30 40,27 60,29 80,26 100,28 120,25 140,27 160,26 180,24 200,25"/>
                                        <polyline class="courbe c-calme" pathLength="1" points="0,34 20,33 40,35 60,32 80,34 100,31 120,33 140,32 160,30 180,31 200,30"/>
                                        <polyline class="courbe c-alerte" pathLength="1" points="0,26 20,24 40,27 60,23 80,25 100,22 120,24 140,21 160,23 180,22 200,40"/>
                                    </svg>
                                </div>
                                <div class="tdb-carte tdb-vitaux">
                                    <p class="tdb-titre">Core Web Vitals</p>
                                    <p class="vital vital-lcp"><i class="point p-moyen"></i>LCP<b class="valeur-lcp">2,9 s</b></p>
                                    <p class="vital"><i class="point p-bon"></i>CLS<b>0,05</b></p>
                                    <p class="vital"><i class="point p-bon"></i>INP<b>120 ms</b></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alerte-carte" aria-hidden="true">
                        <p class="alerte-titre"><svg viewBox="0 0 20 20"><path d="M10 2 L19 18 H1 Z"/><path d="M10 8 V12 M10 14.5 V15" class="alerte-signe"/></svg>Alerte détectée !</p>
                        <p>La note Performance a baissé de 8 points après votre déploiement.</p>
                        <ol class="alerte-conseils">
                            <li>Compresser l'image d'accueil <b>−1,2 s</b></li>
                            <li>Différer le script de chat <b>−0,4 s</b></li>
                        </ol>
                    </div>

                    <div class="mini-carte" aria-hidden="true">
                        <p>Performance hier</p>
                        <div class="anneau anneau-petit">
                            <svg viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" class="anneau-piste"/><circle cx="50" cy="50" r="40" class="anneau-arc arc-bon" pathLength="100" style="--note: 92"/></svg>
                            <span class="anneau-chiffre">92</span>
                        </div>
                    </div>
                </figure>
            </div>
        </header>

        <main>
            <section id="fonctionnalites" class="bande bande-papier">
                <h2 class="sr-only">Fonctionnalités</h2>
                <div class="atouts">
                    <article class="atout">
                        <span class="atout-icone"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13 2 L4 14 H11 L10 22 L20 9 H13 Z" class="icone-plein"/></svg></span>
                        <h3>Des mesures fiables</h3>
                        <p>Données réelles de PageSpeed Insights, avec une médiane de 3 mesures pour plus de précision.</p>
                    </article>
                    <article class="atout">
                        <span class="atout-icone"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20 H20 M7 16 V11 M11 16 V8 M15 16 V12 M19 16 V5" class="icone-trait"/><path d="M5 9 L10 5 L14 8 L20 3" class="icone-trait"/></svg></span>
                        <h3>Une vue claire</h3>
                        <p>Suivez l'évolution de vos notes et de vos Core Web Vitals sur 90&nbsp;jours.</p>
                    </article>
                    <article class="atout">
                        <span class="atout-icone"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 16 V11 A6 6 0 0 1 18 11 V16 L20 18 H4 Z" class="icone-plein"/><path d="M10 20 A2 2 0 0 0 14 20" class="icone-trait"/></svg></span>
                        <h3>Des alertes utiles</h3>
                        <p>Une baisse de note ? Vous recevez un e-mail avec les conseils les plus rentables, classés par gain de&nbsp;temps.</p>
                    </article>
                    <article class="atout">
                        <span class="atout-icone"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2 L20 5 V11 C20 16 16.5 20 12 22 C7.5 20 4 16 4 11 V5 Z" class="icone-plein"/></svg></span>
                        <h3>Pour tous les projets</h3>
                        <p>D'une seule page au site entier (jusqu'à 200 pages) : Radar suit le freelance comme la petite&nbsp;agence.</p>
                    </article>
                </div>
            </section>

            <section id="defi" class="bande bande-sable-clair defi">
                <div class="defi-texte">
                    <p class="surtitre">Le jeu du jour</p>
                    <h2 class="titre-section">Quelle est la meilleure amélioration ?</h2>
                    <p>Trois optimisations possibles, un seul meilleur choix. Testez votre flair et apprenez ce qui fait vraiment monter une&nbsp;note.</p>
                    <a class="bouton-magenta" href="#defi-question" data-jouer>Jouer maintenant <span aria-hidden="true">→</span></a>
                </div>

                <div class="defi-carte" data-defi='@json($defi)'>
                    <div class="defi-question">
                        <p class="pilule-magenta">Défi du jour</p>
                        <h3 id="defi-question">Comment améliorer ce site ?</h3>
                        <p class="defi-contexte">Vous gérez {{ $defi['page'] }}. Indice : {{ $defi['indice'] }}</p>
                        <fieldset class="defi-options">
                            <legend class="sr-only">Choisissez l'option qui fait le plus monter la note Performance</legend>
                            @foreach ($defi['options'] as $i => [$libelle, $gain])
                                <label class="defi-option">
                                    <input type="radio" name="defi" value="{{ $i }}">
                                    <span>{{ $libelle }}</span>
                                    <em class="defi-gain" hidden>+{{ $gain }}</em>
                                </label>
                            @endforeach
                        </fieldset>
                        <p class="defi-resultat" aria-live="polite"></p>
                        <details class="defi-sans-js">
                            <summary>Voir la réponse</summary>
                            <p>{{ $defi['options'][$defi['meilleure']][0] }} (+{{ $defi['options'][$defi['meilleure']][1] }}). {{ $defi['explication'] }}</p>
                        </details>
                    </div>
                    <div class="defi-apercu" aria-hidden="true">
                        <div class="navigateur">
                            <span class="navigateur-points"><i></i><i></i><i></i></span>
                            <svg viewBox="0 0 120 70" class="navigateur-image"><rect width="120" height="70" class="d-ciel"/><circle cx="88" cy="22" r="10" class="d-soleil"/><path d="M0 70 L34 30 L52 48 L74 22 L120 70 Z" class="d-encre"/><path d="M74 22 L66 70 L120 70 Z" class="d-encre-face"/></svg>
                            <span class="navigateur-ligne"></span>
                            <span class="navigateur-ligne courte"></span>
                        </div>
                        <div class="defi-note">
                            <p>Performance</p>
                            <div class="anneau anneau-petit">
                                <svg viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" class="anneau-piste"/><circle cx="50" cy="50" r="40" class="anneau-arc arc-moyen" pathLength="100" style="--note: {{ $defi['note'] }}"/></svg>
                                <span class="anneau-chiffre">{{ $defi['note'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="defi-illustration" aria-hidden="true">
                    <p class="manuscrit">Un petit effort aujourd'hui, de meilleures notes demain&nbsp;!</p>
                    <svg viewBox="0 0 320 260">
                        <path d="M70 70 C 40 90, 30 120, 60 140" class="d-pointille"/>
                        <circle cx="210" cy="150" r="70" class="d-soleil"/>
                        <path d="M60 260 L150 150 L180 180 L230 110 L320 230 L320 260 Z" class="d-encre"/>
                        <path d="M230 110 L214 260 L320 260 L320 230 Z" class="d-encre-face"/>
                        <path d="M120 260 L320 200 L320 214 L140 260 Z" class="d-alerte"/>
                        <path d="M231 110 V76" class="d-mat"/>
                        <path d="M232 77 L258 85 L232 93 Z" class="d-alerte"/>
                        <circle cx="216" cy="98" r="5" class="d-encre"/>
                        <path d="M216 104 L218 120 M218 120 L210 132 M218 120 L226 130 M217 110 L229 104 M217 110 L206 114" class="d-silhouette"/>
                    </svg>
                </div>
            </section>

            <section id="comment" class="bande bande-ciel duo">
                <div>
                    <h2 class="titre-section">Trois étapes, aucune configuration.</h2>
                    <ol class="etapes">
                        <li><span><b>Ajoutez vos pages.</b> Collez une adresse, ou importez le sitemap de votre site.</span></li>
                        <li><span><b>Radar les mesure.</b> PageSpeed, trois passages dont on garde la médiane, sur mobile et sur&nbsp;ordinateur.</span></li>
                        <li><span><b>Vous êtes prévenu.</b> Un e-mail dès que la note baisse, avec ce qu'il faut corriger en&nbsp;premier.</span></li>
                    </ol>
                </div>
                <article class="carte-blanche">
                    <p class="carte-surtitre">Exemple d'alerte · monsite.fr · mobile</p>
                    <div class="grid grid-cols-4 gap-2 my-5">
                        <div class="radar-note radar-note-baisse"><b>84</b>Perf −8</div>
                        <div class="radar-note"><b>96</b>Access.</div>
                        <div class="radar-note"><b>94</b>Pratiques</div>
                        <div class="radar-note"><b>90</b>SEO</div>
                    </div>
                    <p><b>LCP 2,9&nbsp;s</b> (était 2,1&nbsp;s) depuis hier. Conseil : compresser l'image d'accueil, gain estimé 1,2&nbsp;s.</p>
                </article>
            </section>

            <section class="bande bande-papier">
                <h2 class="titre-section">Les signaux que Google regarde.</h2>
                <p class="intro">Les quatre notes de Lighthouse, de 0 à 100, et les trois Core Web Vitals, avec les seuils officiels.</p>
                <div class="tableau-defile">
                    <table class="seuils">
                        <caption class="sr-only">Seuils des Core Web Vitals selon Google</caption>
                        <thead>
                            <tr><th scope="col">Signal</th><th scope="col">Bon</th><th scope="col">À améliorer</th><th scope="col">Mauvais</th></tr>
                        </thead>
                        <tbody>
                            <tr><th scope="row">LCP <span>affichage du plus gros élément</span></th><td>≤ 2,5&nbsp;s</td><td>≤ 4&nbsp;s</td><td>&gt; 4&nbsp;s</td></tr>
                            <tr><th scope="row">CLS <span>stabilité de la mise en page</span></th><td>≤ 0,1</td><td>≤ 0,25</td><td>&gt; 0,25</td></tr>
                            <tr><th scope="row">INP <span>réaction au clic</span></th><td>≤ 200&nbsp;ms</td><td>≤ 500&nbsp;ms</td><td>&gt; 500&nbsp;ms</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="bande bande-encre">
                <h2 class="titre-section">Une alerte quand ça compte, pas à chaque variation.</h2>
                <div class="chiffres">
                    <div><p class="chiffre">−5</p><p>points de performance sous la médiane des 7 derniers jours : Radar vous écrit.</p></div>
                    <div><p class="chiffre">3</p><p>mesures à chaque passage. On garde la médiane, pour ne pas crier au loup sur un réseau&nbsp;lent.</p></div>
                    <div><p class="chiffre">1</p><p>e-mail par page et par jour au maximum. Un LCP, CLS ou INP qui change de catégorie déclenche aussi&nbsp;l'alerte.</p></div>
                </div>
            </section>

            <section class="bande bande-sable-clair duo">
                <div>
                    <h2 class="titre-section">Affichez votre note.</h2>
                    <p class="intro">Chaque page suivie a son badge, mis à jour après chaque mesure. Collez-le dans un README ou au pied de votre&nbsp;site.</p>
                </div>
                <div class="carte-blanche">
                    @include('badges.performance', ['texte' => '94', 'couleur' => \App\Services\Badge::VERT])
                    <pre class="code"><code>&lt;img src="https://radar.projectp.fr/badge/42.svg" alt="Note de performance"&gt;</code></pre>
                </div>
            </section>

            <section id="tarifs" class="bande bande-ciel">
                <h2 class="titre-section">Commencez gratuitement.</h2>
                <p class="intro">Le paiement en ligne des offres Solo et Agence arrive&nbsp;bientôt.</p>
                <div class="offres">
                    <article class="offre">
                        <h3>Gratuit</h3>
                        <p class="prix">0 €</p>
                        <ul>
                            <li>{{ config('offres.gratuit.sites') }} site, {{ config('offres.gratuit.pages_par_site') }} page</li>
                            <li>Une mesure mobile par semaine</li>
                            <li>Alerte par e-mail</li>
                            <li>3 conseils si la note passe sous 50</li>
                        </ul>
                        @auth
                            <a class="bouton-encre" href="{{ route('dashboard') }}">Mon tableau de bord</a>
                        @else
                            <a class="bouton-encre" href="{{ route('register') }}">Créer un compte</a>
                        @endauth
                    </article>
                    <article class="offre offre-phare">
                        <p class="pilule-magenta">Le plus choisi</p>
                        <h3>Solo</h3>
                        <p class="prix">4 € <span>/ mois</span></p>
                        <ul>
                            <li>{{ config('offres.solo.sites') }} sites, {{ config('offres.solo.pages_par_site') }} pages par site</li>
                            <li>Mesure quotidienne, mobile et ordinateur</li>
                            <li>Courbe sur 90 jours et Core Web Vitals</li>
                            <li>10 conseils à chaque alerte</li>
                        </ul>
                    </article>
                    <article class="offre">
                        <h3>Agence</h3>
                        <p class="prix">12 € <span>/ mois</span></p>
                        <ul>
                            <li>{{ config('offres.agence.sites') }} sites, {{ config('offres.agence.pages_par_site') }} pages par site</li>
                            <li>Tout ce que contient Solo</li>
                            <li>De quoi suivre les sites de vos clients</li>
                        </ul>
                    </article>
                </div>
                <p class="option"><b>Option Site entier, +3 € par mois et par site :</b> Radar importe votre sitemap et suit jusqu'à 200 pages, mesurées à tour de rôle chaque&nbsp;semaine.</p>
            </section>

            <section id="faq" class="bande bande-papier">
                <h2 class="titre-section">Questions fréquentes</h2>
                <div class="faq">
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

            <section class="bande bande-sable final">
                <h2 class="titre-section">Quelle est la note de votre accueil ?</h2>
                <a class="bouton-encre bouton-grand" href="{{ route('tester') }}">Tester gratuitement <span aria-hidden="true">→</span></a>
            </section>
        </main>

        <footer class="pied">
            <span>Radar · Jour 13 du défi <a href="https://projectp.fr/">30 jours, 30 projets</a></span>
            <span>Fait avec Laravel · <a href="https://github.com/PierreWZK/Radar" target="_blank" rel="noopener">Code sur GitHub ↗</a></span>
        </footer>

        <script>
            (() => {
                const calme = matchMedia('(prefers-reduced-motion: reduce)').matches;

                // Héros : sans JavaScript ou en mouvement réduit, l'état final (note 84, alerte ouverte) reste affiché.
                const scene = document.getElementById('scene');
                if (scene && !calme) {
                    const etape = (classe, delai) => setTimeout(() => scene.classList.add(classe), delai);
                    const lcp = scene.querySelector('.valeur-lcp');
                    lcp.textContent = '2,1 s';
                    setTimeout(() => { lcp.textContent = '2,9 s'; }, 2600);
                    scene.classList.add('rejoue');
                    requestAnimationFrame(() => requestAnimationFrame(() => scene.classList.add('e1')));
                    etape('e2', 2600);
                    etape('e3', 3200);
                    etape('e4', 4000);
                    // La note descend de 92 à 84 en même temps que l'anneau.
                    setTimeout(() => {
                        const chiffre = scene.querySelector('.tdb .anneau-chiffre');
                        const valeur = scene.querySelector('.valeur-perf');
                        let note = 92;
                        const pas = setInterval(() => {
                            note -= 1;
                            chiffre.textContent = valeur.textContent = note;
                            if (note <= 84) clearInterval(pas);
                        }, 70);
                    }, 2600);
                }

                // Jeu du jour
                const carte = document.querySelector('.defi-carte');
                if (!carte) return;
                const defi = JSON.parse(carte.dataset.defi);
                const resultat = carte.querySelector('.defi-resultat');
                const anneau = carte.querySelector('.defi-note .anneau-arc');
                const chiffre = carte.querySelector('.defi-note .anneau-chiffre');
                const cle = 'radar-defi-' + new Date().toISOString().slice(0, 10);
                carte.querySelector('.defi-sans-js').hidden = true;

                document.querySelector('[data-jouer]').addEventListener('click', (e) => {
                    e.preventDefault();
                    carte.scrollIntoView({ behavior: calme ? 'auto' : 'smooth', block: 'center' });
                    carte.querySelector('input').focus({ preventScroll: true });
                });

                function repondre(choix) {
                    const [libelle, gain] = defi.options[defi.meilleure];
                    carte.querySelectorAll('.defi-option').forEach((option, i) => {
                        option.querySelector('input').disabled = true;
                        option.querySelector('.defi-gain').hidden = false;
                        option.classList.toggle('juste', i === defi.meilleure);
                        option.classList.toggle('faux', i === choix && i !== defi.meilleure);
                    });
                    carte.querySelectorAll('input')[choix].checked = true;
                    const note = defi.note + defi.options[choix][1];
                    anneau.style.setProperty('--note', note);
                    anneau.classList.toggle('arc-bon', note >= 90);
                    chiffre.textContent = note;
                    resultat.textContent = (choix === defi.meilleure ? 'Bien vu ! ' : `Pas tout à fait : la meilleure option était « ${libelle} » (+${gain}). `) + defi.explication;
                }

                carte.querySelector('.defi-options').addEventListener('change', (e) => {
                    const choix = Number(e.target.value);
                    repondre(choix);
                    try { localStorage.setItem(cle, choix); } catch {}
                });

                try {
                    const deja = localStorage.getItem(cle);
                    if (deja !== null) repondre(Number(deja));
                } catch {}
            })();
        </script>
    </body>
</html>
