<x-mail::message>
# {{ $alerte->raison }}

**Page** : {{ $alerte->page->url }}
**Appareil** : {{ $alerte->strategie === 'mobile' ? 'mobile' : 'ordinateur' }}
**Performance** : {{ $alerte->performance_avant }} → {{ $alerte->performance_apres }}

@if (count($alerte->conseils))
## Conseils pour la remonter

@foreach ($alerte->conseils as $conseil)
- {{ $conseil['titre'] }} (gain estimé : {{ round($conseil['gain_ms'] / 1000, 1) }} s)
@endforeach
@endif

Radar · 30 jours, 30 projets
</x-mail::message>
