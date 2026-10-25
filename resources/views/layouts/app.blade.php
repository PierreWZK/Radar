<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Radar</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:800|hanken-grotesk:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="espace-corps">
        <div class="espace">
            <aside class="espace-barre">
                <a class="espace-logo" href="{{ route('dashboard') }}">
                    <x-marque />
                </a>

                <nav class="espace-menu" aria-label="Principale">
                    <a href="{{ route('dashboard') }}" @if (request()->routeIs('dashboard')) aria-current="page" @endif>Tableau de bord</a>
                    <a href="{{ route('sites.index') }}" @if (request()->routeIs('sites.*')) aria-current="page" @endif>Mes sites</a>
                    <a href="{{ route('alertes.index') }}" @if (request()->routeIs('alertes.*')) aria-current="page" @endif>Alertes</a>
                    <a href="{{ route('profile.edit') }}" @if (request()->routeIs('profile.*')) aria-current="page" @endif>Paramètres</a>
                </nav>

                <div class="espace-compte">
                    <p>{{ Auth::user()->name }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="lien-compte">Se déconnecter</button>
                    </form>
                </div>
            </aside>

            <div class="espace-contenu">
                @isset($header)
                    <header class="espace-entete">
                        {{ $header }}
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
