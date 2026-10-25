@props(['note', 'petit' => false])

@php
    $classe = $note >= 90 ? 'arc-bon' : ($note >= 50 ? 'arc-moyen' : 'arc-mauvais');
@endphp

<div {{ $attributes->class(['anneau', 'anneau-petit' => $petit]) }}>
    <svg viewBox="0 0 100 100" aria-hidden="true">
        <circle cx="50" cy="50" r="40" class="anneau-piste"/>
        <circle cx="50" cy="50" r="40" class="anneau-arc {{ $classe }}" pathLength="100" style="--note: {{ $note }}"/>
    </svg>
    <span class="anneau-chiffre">{{ $note }}</span>
</div>
