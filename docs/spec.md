# Spec · Radar

## Objectif

Suivre les notes Lighthouse de petits sites dans le temps, comprendre pourquoi une note baisse après un déploiement, et proposer des conseils pour la remonter. Cible principale : freelances et propriétaires de petits sites.

## Besoins

**Comptes**
- Inscription par e-mail et mot de passe. L'adresse est confirmée par un lien signé avant tout suivi de site.
- Mot de passe oublié par lien signé.

**Sites et pages**
- Un site a un nom et une liste de pages (adresses) à suivre.
- Limites par offre : Gratuit 1 site × 1 page ; Solo 3 × 5 ; Agence 15 × 10. Option « Site entier » (+3 €/mois par site) : toutes les pages du `sitemap.xml`, jusqu'à 200, mesurées par roulement hebdomadaire.
- Offre portée par un champ `plan` sur le compte, modifié à la main. Pages d'offres et de paiement affichées, sans encaissement.

**Mesures**
- PageSpeed Insights appelé côté serveur uniquement, avec la clé dans `.env`.
- Mobile et ordinateur, médiane de 3 mesures par jour et par page.
- Mesures dans une file de tâches, une à la fois, délai de 60 s, 3 tentatives ; un échec ne bloque pas les autres.
- Gratuit : une mesure par semaine, mobile. Solo et Agence : une mesure par jour, mobile et ordinateur.
- Planification nocturne.

**Alerte (cœur du produit)**
- Déclenchée si une note baisse d'au moins 5 points par rapport à la médiane des 7 derniers jours, ou si un Core Web Vital passe dans une catégorie moins bonne.
- Une variation de 2 points ne déclenche rien.
- E-mail avec page, appareil, notes avant et après, et les trois conseils aux gains estimés les plus élevés.
- Une alerte au maximum par page et par jour.
- Conseils détaillés pour Solo et Agence ; pour le Gratuit, les trois conseils prioritaires seulement si une note passe sous 50.

**Tableau de bord**
- Vue d'ensemble : une carte par page, quatre notes du jour, écart avec la veille.
- Fiche de page : courbes sur 90 jours, une par note, mobile et ordinateur séparés ; Core Web Vitals avec les seuils Google.
- Graphiques complets et Core Web Vitals réservés à Solo et Agence.

**Test sans compte**
- On colle une adresse, on voit les notes. 3 tests par jour et par IP.

**Badge**
- `GET /badge/{page}.svg`, format shields.io, couleur selon la note (vert ≥ 90, orange ≥ 50, rouge en dessous), mis en cache quelques heures.

**Identité visuelle**
- Direction A « Balayage » : `design/tokens.css` et `design/moodboard.html`. Le radar du héros reflète les notes réelles.

## Hors périmètre

- Stripe et tout encaissement (plus tard).
- Webhook de déploiement Coolify (bonus).
- Données terrain CrUX, comparaison avec un concurrent, rapport PDF (bonus).
- Crawl automatique du site.

## Risques

- **Quota PageSpeed** : 25 000 requêtes par jour sur la clé gratuite. Le test sans compte et l'option « Site entier » doivent rester bornés.
- **Variabilité des mesures** : la médiane et le seuil de 5 points limitent les fausses alertes ; à vérifier sur de vraies mesures.
- **Mesures lentes** (10 à 30 s) : la file et les délais évitent de bloquer l'interface.
- **E-mails** : confirmation et alertes peuvent finir en spam ; tester avec une vraie boîte avant la mise en ligne.
- **Clé API exposée** : jamais côté navigateur, restreinte à l'API et à l'IP du serveur.
