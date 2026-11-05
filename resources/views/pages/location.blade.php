@extends('layouts.app')
@section('title', 'Nous trouver | ' . config('church.name'))

@section('content')
@php
    $c = fn ($key) => \App\Models\Setting::get($key, config('church.' . $key));
    $mapQuery = \App\Models\Setting::get('map_query') ?: \App\Models\Setting::get('address');
    $phone = $c('phone');
    $phoneDigits = preg_replace('/[^\d+]/', '', (string) $phone);
    $email = $c('email');
@endphp

<section class="page-head">
    <div class="container">
        <h1>Nous trouver</h1>
        <p>Venez nous rejoindre : vous êtes les bienvenus.</p>
    </div>
</section>

<section class="section">
    <div class="container split location">
        <div>
            <h2>Adresse</h2>
            <p>{{ $c('address') }}</p>

            <h2>Contact</h2>
            <p>
                @if (strlen($phoneDigits) >= 6)<a class="link" href="tel:{{ $phoneDigits }}">{{ $phone }}</a>@else{{ $phone }}@endif
            </p>
            <p>
                @if (filter_var($email, FILTER_VALIDATE_EMAIL))<a class="link" href="mailto:{{ $email }}">{{ $email }}</a>@else{{ $email }}@endif
            </p>

            <div class="actions">
                @if ($mapQuery)
                    <a class="btn btn-gold" href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($mapQuery) }}" target="_blank" rel="noopener">Obtenir l'itinéraire</a>
                @endif
                <a class="btn btn-navy" href="{{ route('home') }}#horaires">Horaires des cultes</a>
            </div>
        </div>

        <div class="map-frame">
            @if ($mapQuery)
                <iframe title="Carte : emplacement de l'église"
                        src="https://maps.google.com/maps?q={{ urlencode($mapQuery) }}&z=16&output=embed"
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            @else
                <p>La carte s'affichera dès que l'adresse sera renseignée dans l'administration (Réglages).</p>
            @endif
        </div>
    </div>
</section>
@endsection
