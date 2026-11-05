@extends('layouts.app')
@section('title', 'Média | ' . config('church.name'))
@section('description', 'Les dernières prédications de ' . config('church.name'))

@section('content')
@php
    $youtube = \App\Models\Setting::get('youtube_url', config('church.youtube_url'));
    $facebook = \App\Models\Setting::get('facebook_url', config('church.facebook_url'));
    $liveUrl = \App\Models\Setting::get('live_url');
@endphp

<section class="page-head">
    <div class="container">
        <h1>Média</h1>
        <p>Les dernières prédications, à écouter et à revoir où que vous soyez.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        @if ($youtube || $facebook || $liveUrl)
            <div class="actions media-links">
                @if ($liveUrl)<a class="btn btn-gold" href="{{ route('live') }}">Culte en direct</a>@endif
                @if ($youtube)<a class="btn btn-navy" href="{{ $youtube }}" target="_blank" rel="noopener">Notre chaîne YouTube</a>@endif
                @if ($facebook)<a class="btn btn-outline" href="{{ $facebook }}" target="_blank" rel="noopener">Notre page Facebook</a>@endif
            </div>
        @endif

        @if ($sermons->isEmpty())
            <p class="empty">Les prédications seront publiées prochainement.</p>
        @else
            <h2 class="media-title">Les {{ $sermons->count() > 1 ? $sermons->count() . ' dernières prédications' : 'dernière prédication' }}</h2>
            <div class="sermon-grid">
                @foreach ($sermons as $sermon)
                    <article @class(['sermon', 'sermon-lead' => $loop->first])>
                        @if ($embed = $sermon->embedUrl())
                            <div class="embed">
                                <iframe src="{{ $embed }}" title="{{ $sermon->title }}" loading="lazy"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>
                        @endif
                        <div class="sermon-body">
                            <h3>{{ $sermon->title }}</h3>
                            <p class="meta">
                                <span>{{ $sermon->preached_at->translatedFormat('d F Y') }}</span>
                                @if ($sermon->preacher)<span>{{ $sermon->preacher }}</span>@endif
                                @if ($sermon->scripture)<span>{{ $sermon->scripture }}</span>@endif
                            </p>
                            @if ($sermon->description)<p>{{ $sermon->description }}</p>@endif
                            <a class="link" href="{{ $sermon->video_url }}" target="_blank" rel="noopener">Regarder sur {{ $sermon->platformLabel() }}</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
