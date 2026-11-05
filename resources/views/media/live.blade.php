@extends('layouts.app')
@section('title', 'Culte en direct | ' . config('church.name'))

@section('content')
@php
    $shareUrl = route('live');
    $shareText = 'Rejoignez le culte en direct : ' . config('church.name');
@endphp

<section class="page-head">
    <div class="container">
        <h1>Culte en direct</h1>
        <p>Suivez le culte où que vous soyez et partagez-le avec vos proches.</p>
    </div>
</section>

<section class="section">
    <div class="live-wrap">
        @if ($embed)
            <p @class(['live-status', 'is-live' => $active])>
                <span class="dot" aria-hidden="true"></span>
                {{ $active ? 'En direct maintenant' : "Le direct n'est pas en cours pour le moment." }}
            </p>

            <div class="embed">
                <iframe src="{{ $embed }}" title="Culte en direct" loading="lazy"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>

            <div class="share" data-share-url="{{ $shareUrl }}" data-share-title="{{ $shareText }}">
                <h2>Partager le culte</h2>
                <div class="actions">
                    <button type="button" class="btn btn-gold" data-share hidden>Partager</button>
                    <a class="btn btn-navy" target="_blank" rel="noopener"
                       href="https://wa.me/?text={{ urlencode($shareText . ' ' . $shareUrl) }}">WhatsApp</a>
                    <a class="btn btn-navy" target="_blank" rel="noopener"
                       href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}">Facebook</a>
                    <button type="button" class="btn btn-outline" data-copy="{{ $shareUrl }}">Copier le lien</button>
                </div>
            </div>

            <p><a class="link" href="{{ $url }}" target="_blank" rel="noopener">Ouvrir le direct sur {{ \App\Support\VideoEmbed::platform($url) === 'youtube' ? 'YouTube' : 'Facebook' }}</a></p>
        @else
            <p class="empty">Le lien du direct sera ajouté prochainement. En attendant, retrouvez les dernières prédications.</p>
            <a class="btn btn-gold" href="{{ route('media') }}">Voir les prédications</a>
        @endif

        <p class="schedule-link"><a class="link" href="{{ route('home') }}#horaires">Voir les horaires des cultes</a></p>
    </div>
</section>
@endsection
