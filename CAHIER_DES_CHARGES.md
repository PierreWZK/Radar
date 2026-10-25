# Jour 13/30 — Radar, la santé de vos sites semaine après semaine

> Stack : **Laravel** (files, tâches planifiées, notifications), **API PageSpeed Insights**, **Chart.js** · hébergement **Coolify**, sous-domaine `radar.projectp.fr`.
> Durée visée : **3 h** pour le MVP, les bonus ensuite.

Ce document est notre contrat : on le suit étape par étape, et toute décision qui s'en écarte est d'abord notée ici. Il s'ajuste au début de la journée.

## 1. L'idée

Lighthouse donne une note **à un instant donné**, puis on oublie. **Radar** suit vos sites **dans le temps** : performance, accessibilité, bonnes pratiques et SEO, mesurés chaque jour, avec un graphique d'évolution, les **Core Web Vitals** et une **alerte quand une note baisse** après un déploiement.

> **sursaut.projectp.fr** · mobile · mesure du 21 octobre
> ⚡ Performance **94** (−3) · ♿ Accessibilité **100** · ✅ Bonnes pratiques **96** · 🔎 SEO **92**
> ⚠️ **LCP 2,9 s** (était 2,1 s) depuis le déploiement d'hier : une image de 640 Ko a été ajoutée sur l'accueil.

## 2. MVP — ce qui doit fonctionner à la fin

1. Ajouter des adresses à suivre (une ou plusieurs pages par site).
2. Mesure quotidienne **mobile** et **ordinateur** via PageSpeed Insights, dans une file de tâches.
3. Tableau de bord : les quatre notes, les Core Web Vitals (LCP, CLS, INP) et leur évolution sur 90 jours.
4. Alerte en cas de baisse, avec les **trois conseils** les plus rentables tirés du rapport.
5. Badge de note (« Performance 94 ») à coller dans un README, mis à jour tout seul.
6. En ligne sur Coolify, avec un README à jour et un aperçu de lien (Open Graph).

### Périmètre volontairement limité

- Données de laboratoire (Lighthouse) ; les données terrain (CrUX) en bonus, car les petits sites n'en ont pas toujours.
- Pas de crawl automatique de tout le site : on choisit les pages.

## 3. Cahier des charges fonctionnel

### Sites et pages

- Un site = un nom + une liste d'adresses (pages) à suivre.
- Ajout, modification, suppression ; une mesure peut être relancée à la main depuis la fiche de la page.

### Mesures

- Chaque jour, pour chaque page : une mesure **mobile** et une **ordinateur**.
- Une mesure enregistre : les quatre notes (0-100), LCP, CLS, INP (ou TBT quand INP manque en laboratoire), la date et la version de Lighthouse.
- **Lissage** : la note retenue pour la journée est la **médiane de 3 mesures**.

### Tableau de bord

- Vue d'ensemble : une carte par page avec ses quatre notes du jour et l'écart avec la veille (vert, rouge, gris).
- Fiche d'une page : courbes sur 90 jours (une par note, mobile et ordinateur séparés), Core Web Vitals avec les seuils de Google (bon / à améliorer / mauvais).
- Les déploiements sont marqués sur le graphique (bonus webhook).

### Alertes

- Une alerte part quand une note baisse **d'au moins 5 points** par rapport à la médiane des 7 derniers jours, ou quand un Core Web Vital passe dans une moins bonne catégorie.
- Une variation de 2 points ne déclenche rien.
- L'alerte contient : la page, l'appareil, les notes avant / après et les trois conseils (audits Lighthouse avec le plus gros gain estimé).
- Canal : e-mail (notifications Laravel) ; une seule alerte par page et par jour.

### Badge

- `GET /badge/{page}.svg` : un SVG au format shields.io, couleur selon la note (vert ≥ 90, orange ≥ 50, rouge en dessous), mis en cache quelques heures.

## 4. Cahier des charges technique

- **API PageSpeed Insights** appelée **uniquement côté serveur**, avec une clé gratuite stockée dans `.env` ; elle n'apparaît jamais dans le navigateur ni dans les réponses.
- **File de tâches** Laravel (driver base de données) : une mesure par tâche, **une à la fois**, délai d'attente de 60 s, **3 tentatives** avec attente croissante ; une mesure qui échoue ne bloque pas les autres.
- **Planificateur** Laravel : lance les mesures du jour chaque nuit.
- Calcul de la médiane, des écarts et du déclenchement des alertes dans des classes dédiées, **testées** (PHPUnit), avec des rapports PageSpeed d'exemple en fixtures.
- Graphiques en Chart.js.
- Mise en ligne sur Coolify à chaque push sur la branche principale, avec un worker pour la file et le planificateur.

## 5. Critères de réussite

- [ ] Une image lourde ajoutée sur une page fait baisser la note et déclenche une alerte.
- [ ] Une variation de 2 points due au hasard ne déclenche pas d'alerte.
- [ ] Une mesure qui échoue est retentée, sans bloquer les autres.
- [ ] La clé de l'API n'apparaît jamais côté navigateur.
- [ ] Le badge d'un README affiche la dernière note.

## 6. Bonus facultatifs

- Mesure déclenchée 2 minutes après chaque déploiement Coolify (ou GitHub), via un webhook signé, et marquée sur le graphique.
- Données terrain (Chrome UX Report) quand elles existent.
- Comparaison avec un concurrent.
- Rapport mensuel par e-mail de tous les sites du défi.
- Se combine avec **Vigie** (disponibilité des sites).

## 7. Définition du « terminé »

Le MVP fonctionne, les critères de la section 5 sont cochés, le site est en ligne sur `radar.projectp.fr`, le README a son lien et sa capture, et la carte du projet passe à « terminé » sur la vitrine.

## 8. À me demander avant de coder

À poser **avant d'écrire le code**, et attendre ma réponse :

1. **Le jeu quotidien.** Chaque projet a un jeu du jour pour faire revenir les visiteurs : le même tirage pour tout le monde (d'après la date), une tentative par jour, une série 🔥 et un résultat à partager. Me proposer deux ou trois idées adaptées au projet, puis attendre mon choix.
2. **Le look.** Me demander une référence visuelle sur laquelle se baser (un site, une marque, une ambiance, une capture). Le site doit être original et agréable à regarder, **pas « basique »** : une vraie identité (typographie, palette, mise en page, détails, animations), pas un gabarit générique. Si je n'ai pas d'idée, me proposer deux directions et attendre mon choix.
3. **Les comptes.** Outil privé pour mes sites seulement, ou ouvert à tous avec un compte par utilisateur ?

## 9. Aperçu du lien (à faire sans me le redemander)

- Image Open Graph **1200 × 630** stylisée aux couleurs du projet : nom, accroche, technos et capture, générée avec `scripts/og/build-og.py` (ajouter le projet à la liste).
- Sur chaque page publique : `og:title`, `og:description`, `og:url`, `og:image` (adresse absolue), `og:image:width`, `og:image:height`, `og:image:alt` et `twitter:card` = `summary_large_image`.
- Vérifier en ligne que l'image répond en 200 et que les balises sont servies.

## 10. Indexation (à faire sans me le redemander)

- `robots.txt` : pages privées et routes techniques (API, JSON, admin, badges) en `Disallow`, lien vers le sitemap.
- `sitemap.xml` : les pages publiques uniquement.
- Balise `canonical` sur chaque page publique ; `noindex` sur les pages privées.
- Données structurées JSON-LD sur l'accueil : `WebApplication`, avec `isPartOf` ProjectP (https://projectp.fr/).
- Lien « 30 jours, 30 projets » vers https://projectp.fr/ dans le pied de page.
- Vérifier en ligne, puis me rappeler de soumettre `https://radar.projectp.fr/sitemap.xml` dans Google Search Console.

## 11. Vitrine 30days30projects (avec ma validation)

Une fois le projet en ligne, **me demander mon accord**, puis seulement :

- `projects.js` : l'entrée du jour 13 passe à `status: 'done'`, avec `folder: 'day-13-site-health'`, `name: 'Radar'`, `title`, `stack`, `url`, `repo`, `image` et `description`.
- `preview-days-images/day13_radar.png` : la capture de l'accueil, en 1600 × 900.
- `README.md` : la ligne du jour 13 dans le tableau des 30 projets.
- Commit et push du dépôt `30days30projects`.
