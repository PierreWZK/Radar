<?php

// Limites par offre. Le plan est porté par users.plan, modifié à la main en attendant Stripe.
return [
    'gratuit' => ['sites' => 1, 'pages_par_site' => 1, 'site_entier' => false, 'graphiques' => false],
    'solo' => ['sites' => 3, 'pages_par_site' => 5, 'site_entier' => true, 'graphiques' => true],
    'agence' => ['sites' => 15, 'pages_par_site' => 10, 'site_entier' => true, 'graphiques' => true],
];
