@extends('layouts.app')

@php
    $heading = $type ? \App\Models\Event::TYPES[$type][1] : 'Actualités et événements';
    $intro = [
        'actualite' => "Les nouvelles de la vie de l'église.",
        'retraite' => 'Des temps à l\'écart pour prier et se ressourcer.',
        'seminaire' => 'Enseignement et formation pour grandir ensemble.',
    ][$type] ?? "Les nouvelles de l'église et les rendez-vous à ne pas manquer.";
@endphp

@section('title', $heading . ' | ' . config('church.name'))

@section('content')
<section class="page-head">
    <div class="container">
        <h1>{{ $heading }}</h1>
        <p>{{ $intro }}</p>
    </div>
</section>

<div class="subnav">
    <nav class="container" aria-label="Rubriques">
        <a href="{{ route('events.index') }}" @class(['active' => ! $type])>Tout</a>
        <a href="{{ route('events.actualites') }}" @class(['active' => $type === 'actualite'])>Actualités</a>
        <a href="{{ route('events.retraites') }}" @class(['active' => $type === 'retraite'])>Retraites</a>
        <a href="{{ route('events.seminaires') }}" @class(['active' => $type === 'seminaire'])>Séminaires</a>
    </nav>
</div>

<section class="section">
    <div class="container">
        @include('events._list', ['events' => $events])

        <div class="pager">
            @if ($events->previousPageUrl())<a class="link" href="{{ $events->previousPageUrl() }}">Page précédente</a>@else<span></span>@endif
            @if ($events->nextPageUrl())<a class="link" href="{{ $events->nextPageUrl() }}">Page suivante</a>@endif
        </div>
    </div>
</section>
@endsection
