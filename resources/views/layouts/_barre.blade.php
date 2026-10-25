@php
    $exemple = $exemple ?? false;
    $liens = [
        ['dashboard', 'dashboard', 'Tableau de bord', '<path d="M3 11 L12 4 L21 11 V20 H14 V14 H10 V20 H3 Z"/>'],
        ['sites.index', 'sites.*', 'Mes sites', '<rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/>'],
        ['alertes.index', 'alertes.*', 'Alertes', '<path d="M6 16 V11 A6 6 0 0 1 18 11 V16 L20 18 H4 Z"/><path d="M10 21 A2 2 0 0 0 14 21"/>'],
        ['profile.edit', 'profile.*', 'Paramètres', '<circle cx="12" cy="12" r="3"/><path d="M12 2 V5 M12 19 V22 M2 12 H5 M19 12 H22 M4.9 4.9 L7 7 M17 17 L19.1 19.1 M4.9 19.1 L7 17 M17 7 L19.1 4.9"/>'],
    ];
@endphp

<aside class="espace-barre">
    <a class="espace-logo" href="{{ $exemple ? url('/') : route('dashboard') }}">
        <x-marque />
    </a>

    <nav class="espace-menu" aria-label="Principale">
        @foreach ($liens as [$route, $motif, $libelle, $icone])
            @php($actif = $exemple ? $route === 'dashboard' : request()->routeIs($motif))
            @if ($exemple)
                <span @class(['espace-lien', 'actif' => $actif]) @if ($actif) aria-current="page" @endif>
                    <svg class="icone" viewBox="0 0 24 24" aria-hidden="true">{!! $icone !!}</svg>{{ $libelle }}
                </span>
            @else
                <a href="{{ route($route) }}" @class(['espace-lien', 'actif' => $actif]) @if ($actif) aria-current="page" @endif>
                    <svg class="icone" viewBox="0 0 24 24" aria-hidden="true">{!! $icone !!}</svg>{{ $libelle }}
                </a>
            @endif
        @endforeach
    </nav>

    <div class="espace-compte">
        @if ($exemple)
            <a class="bouton-magenta" href="{{ route('register') }}">Créer un compte</a>
        @else
            <p>{{ Auth::user()->name }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="lien-compte">Se déconnecter</button>
            </form>
        @endif
    </div>
</aside>
