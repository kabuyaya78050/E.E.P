@extends('layouts.app')
@section('title', $event->title . ' | ' . config('church.name'))
@section('description', $event->excerpt ?? $event->title)

@section('content')
@php
    $departure = collect([
        $event->departure_time ? str_replace(':', 'h', $event->departure_time) : null,
        $event->departure_place,
    ])->filter();
@endphp

<section class="page-head">
    <div class="container">
        <h1>{{ $event->title }}</h1>
        @if ($event->excerpt)<p>{{ $event->excerpt }}</p>@endif
    </div>
</section>

<section class="section">
    <div class="container narrow prose">
        @if ($event->image)
            <img class="event-img" src="{{ asset($event->image) }}" alt="{{ $event->title }}">
        @endif

        <dl class="facts">
            <div><dt>Rubrique</dt><dd>{{ $event->typeLabel() }}</dd></div>
            @if ($event->starts_at)
                <div><dt>Date</dt><dd>{{ $event->starts_at->translatedFormat('l d F Y') }}</dd></div>
                <div><dt>Heure</dt><dd>{{ $event->starts_at->translatedFormat('H\hi') }}</dd></div>
            @else
                <div><dt>Publié le</dt><dd>{{ $event->created_at->translatedFormat('d F Y') }}</dd></div>
            @endif
            @if ($event->location)<div><dt>Lieu</dt><dd>{{ $event->location }}</dd></div>@endif
            @if ($departure->isNotEmpty())
                <div><dt>Départ</dt><dd>{{ $departure->implode(', ') }}</dd></div>
            @endif
        </dl>

        {!! nl2br(e($event->body)) !!}
        <p><a class="link" href="{{ route('events.index') }}">Retour aux événements</a></p>
    </div>
</section>
@endsection
