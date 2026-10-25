@props(['titre', 'description', 'chemin' => '/', 'indexer' => true, 'jsonld' => false])

@php
    $base = rtrim(config('app.url'), '/');
    $url = $base.$chemin;
    $image = $base.'/images/og-radar.png';
@endphp

<title>{{ $titre }}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $url }}">
@unless ($indexer)
    <meta name="robots" content="noindex, nofollow">
@endunless

<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<meta property="og:type" content="website">
<meta property="og:site_name" content="Radar">
<meta property="og:locale" content="fr_FR">
<meta property="og:title" content="{{ $titre }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Radar : la santé de vos sites, semaine après semaine.">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $titre }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

@if ($jsonld)
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebApplication',
            'name' => 'Radar',
            'url' => $base.'/',
            'description' => $description,
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'offers' => [
                ['@type' => 'Offer', 'name' => 'Gratuit', 'price' => '0', 'priceCurrency' => 'EUR'],
                ['@type' => 'Offer', 'name' => 'Solo', 'price' => '4', 'priceCurrency' => 'EUR'],
                ['@type' => 'Offer', 'name' => 'Agence', 'price' => '12', 'priceCurrency' => 'EUR'],
            ],
            'isPartOf' => ['@type' => 'WebSite', 'name' => '30 jours, 30 projets', 'url' => 'https://projectp.fr/'],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
    </script>
@endif
