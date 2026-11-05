<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('church.name'))</title>
    <meta name="description" content="@yield('description', config('church.name') . ' : humilité, crainte de Dieu, courage et travail.')">
    <script>document.documentElement.classList.add('js')</script>
    <link rel="stylesheet" href="{{ asset('css/peniel.css') }}">
    @stack('head')
</head>
<body>
<a class="skip" href="#contenu">Aller au contenu</a>

<header class="site-header">
    <div class="bar">
        <a href="{{ route('home') }}" class="brand">
            @if (file_exists(public_path('images/rkm.png')))
                <img class="logo" src="{{ asset('images/rkm.png') }}" alt="Logo de {{ config('church.name') }}">
            @else
                {{-- Emplacement réservé : déposez le logo dans public/images/logo.png --}}
                <span class="logo logo-placeholder" aria-hidden="true">Logo</span>
            @endif
            <span class="brand-text"><strong>Église Évangélique</strong><span>de Peniel</span></span>
        </a>

        <button class="nav-toggle" type="button" aria-controls="nav" aria-expanded="false">Menu</button>

        <nav class="nav" id="nav" aria-label="Navigation principale">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Accueil</a>

            <div class="nav-item">
                <a href="{{ route('about') }}" @class(['active' => request()->routeIs('about', 'vision', 'mission', 'history')])>À propos</a>
                <ul class="sub">
                    <li class="sub-parent"><a href="{{ route('about') }}">Présentation</a></li>
                    <li><a href="{{ route('vision') }}">Vision</a></li>
                    <li><a href="{{ route('mission') }}">Mission et devise</a></li>
                    <li><a href="{{ route('history') }}">Historique de l'église</a></li>
                </ul>
            </div>

            <div class="nav-item">
                <a href="{{ route('events.index') }}" @class(['active' => request()->routeIs('events.*')])>Événements</a>
                <ul class="sub">
                    <li class="sub-parent"><a href="{{ route('events.index') }}">Tous les événements</a></li>
                    <li><a href="{{ route('events.actualites') }}">Actualités</a></li>
                    <li><a href="{{ route('events.retraites') }}">Retraites</a></li>
                    <li><a href="{{ route('events.seminaires') }}">Séminaires</a></li>
                </ul>
            </div>

            <div class="nav-item">
                <a href="{{ route('media') }}" @class(['active' => request()->routeIs('media', 'live')])>Média</a>
                <ul class="sub">
                    <li><a href="{{ route('media') }}">Prédications</a></li>
                    <li><a href="{{ route('live') }}">Culte en direct</a></li>
                </ul>
            </div>
            <a href="{{ route('location') }}" @class(['active' => request()->routeIs('location')])>Nous trouver</a>
            <a href="{{ route('don.create') }}" class="nav-cta">Faire un don</a>
        </nav>
    </div>
</header>

@if (\App\Models\Setting::get('live_active') && \App\Models\Setting::get('live_url') && ! request()->routeIs('live'))
    <a class="live-bar" href="{{ route('live') }}"><span class="dot" aria-hidden="true"></span> Culte en direct : rejoignez-nous maintenant</a>
@endif

<main id="contenu">
    @yield('content')
</main>

@php
    $c = fn ($key) => \App\Models\Setting::get($key, config('church.' . $key));
    $phone = $c('phone');
    $phoneDigits = preg_replace('/[^\d+]/', '', (string) $phone);
    $whatsapp = preg_replace('/\D/', '', (string) $c('whatsapp'));
    $email = $c('email');
@endphp
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="footer-logo">
                @if (file_exists(public_path('images/rkm.png')))
                    <img class="logo logo-lg" src="{{ asset('images/rkm.png') }}" alt="Logo de {{ config('church.name') }}">
                @else
                    <span class="logo logo-lg logo-placeholder" aria-hidden="true">Logo</span>
                @endif
                <strong>{{ config('church.name') }}</strong>
            </a>
            <p>Humilité, Crainte de Dieu, Courage et Travail.</p>
            @include('partials.social', [
                'facebook' => $c('facebook_url'),
                'tiktok' => $c('tiktok_url'),
                'instagram' => $c('instagram_url'),
                'youtube' => $c('youtube_url'),
            ])
        </div>
        <div>
            <h2>Navigation</h2>
            <a href="{{ route('about') }}">À propos</a>
            <a href="{{ route('history') }}">Notre histoire</a>
            <a href="{{ route('events.index') }}">Événements</a>
            <a href="{{ route('media') }}">Prédications</a>
            <a href="{{ route('live') }}">Culte en direct</a>
            <a href="{{ route('home') }}#horaires">Horaires des cultes</a>
            <a href="{{ route('don.create') }}">Faire un don</a>
        </div>
        <div>
            <h2>Contact</h2>
            <p><a href="{{ route('location') }}">{{ $c('address') }}</a></p>
            <p>
                @if (strlen($phoneDigits) >= 6)<a href="tel:{{ $phoneDigits }}">{{ $phone }}</a>@else{{ $phone }}@endif
            </p>
            @if (strlen($whatsapp) >= 6)<p><a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener">WhatsApp</a></p>@endif
            <p>
                @if (filter_var($email, FILTER_VALIDATE_EMAIL))<a href="mailto:{{ $email }}">{{ $email }}</a>@else{{ $email }}@endif
            </p>
        </div>
    </div>
    <p class="copy">Conçu par Kabuyaya Tech</p>
</footer>

<script src="{{ asset('js/peniel.js') }}" defer></script>
</body>
</html>
