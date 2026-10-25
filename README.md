# Radar · La santé de votre site, semaine après semaine

> Jour 13 du défi **30 jours, 30 projets** · Laravel · API PageSpeed Insights · Chart.js · ⏳ **À venir**

Lighthouse donne une note **à un instant donné**, puis on oublie. **Radar** suit vos sites **dans le temps** : performance, accessibilité, bonnes pratiques et SEO, mesurés chaque jour, avec un graphique d'évolution, les **Core Web Vitals** et une **alerte quand une note baisse** après un déploiement.

📄 Les règles détaillées sont dans le [cahier des charges](CAHIER_DES_CHARGES.md).

## Le but

Comprendre **pourquoi une note a baissé** après un déploiement, et savoir **quoi faire** pour la remonter, avec des astuces courtes et classées par gain.

## Pour qui

Le site est ouvert à tous, mais pensé pour les **freelances** et les **propriétaires de petits sites** : pas d'équipe performance, peu de temps, besoin d'une alerte claire plutôt que d'un rapport de 80 lignes.

## Fonctionnalités

1. **Alerte** (le cœur du produit) : une vraie baisse (et pas le bruit de 2 points) déclenche un e-mail avec les notes avant / après et les trois conseils les plus rentables.
2. **Graphique** d'évolution des quatre notes et des Core Web Vitals (LCP, CLS, INP), avec les déploiements marqués.
3. Mesures automatiques via PageSpeed Insights, dans une file de tâches (médiane de 3 mesures).
4. Test ponctuel **sans compte** : on colle une adresse, on voit ses notes.
5. Badge de note à coller dans un README, mis à jour tout seul.
6. **Comptes** : inscription libre, l'adresse e-mail se confirme par un lien signé avant tout suivi de site ; mot de passe oublié.

## Offres

| | Gratuit | Solo · 4 €/mois | Agence · 12 €/mois |
|---|:---:|:---:|:---:|
| Test sans compte | 3 par jour | illimité | illimité |
| Sites suivis | 1 | 3 | 15 |
| Pages par site | 1 | 5 | 10 |
| Fréquence | hebdo, mobile | quotidienne, mobile + ordinateur | quotidienne, mobile + ordinateur |
| Historique | 30 jours | 1 an | 1 an |
| Alerte de baisse | ✅ | ✅ | ✅ |
| Astuces | les 3 prioritaires si une note < 50 | détaillées (perf, SEO, accessibilité) | détaillées |
| Graphiques complets + Core Web Vitals | — | ✅ | ✅ |
| Mesure après déploiement (webhook) | — | ✅ | ✅ |
| Rapport mensuel PDF pour les clients | — | — | ✅ |
| Comparaison avec un concurrent | — | — | ✅ |

**Option « Site entier »** (+3 €/mois par site, Solo et Agence) : toutes les pages du `sitemap.xml` (jusqu'à 200), mesurées par roulement une fois par semaine ; les pages choisies gardent leur mesure quotidienne.

Le paiement n'est pas encore branché : les pages d'offres et de paiement existent, Stripe arrivera plus tard. En attendant, une offre s'active à la main.

## Lancer en local

```bash
composer install
cp .env.example .env         # renseigner PAGESPEED_API_KEY
php artisan key:generate
php artisan migrate
php artisan serve            # http://localhost:8000
php artisan queue:work       # dans un second terminal
php artisan schedule:work    # dans un troisième, pour les mesures planifiées
php artisan test             # les tests
```

## Mise en ligne

Prévue sur Coolify : **https://radar.projectp.fr**.

## Stack

Laravel (files, tâches planifiées, notifications) · API PageSpeed Insights · Chart.js.

## Avant de déclarer le projet terminé

> Rappel pour Claude : à faire sur chaque projet, sans que je le redemande (détail dans les sections 9 et 10 du cahier des charges).

- **Indexation** : `robots.txt`, `sitemap.xml`, balise canonical, données structurées JSON-LD (`WebApplication` rattachée à ProjectP) et lien « 30 jours, 30 projets » vers https://projectp.fr/ dans le pied de page. Vérifier en ligne, puis me rappeler de soumettre `https://radar.projectp.fr/sitemap.xml` dans Google Search Console.
- **Aperçu du lien** : image Open Graph stylisée 1200 × 630 aux couleurs du projet et balises `og:*` / `twitter:card`, vérifiées en ligne.
- **Vitrine** : la mise à jour de `30days30projects` (README et `projects.js`) se fait **après ma validation**.
