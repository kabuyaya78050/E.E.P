@extends('layouts.app')

@section('content')
@php $hero = file_exists(public_path('images/FB_IMG_1787571425693.jpg')); @endphp

<section class="hero" @if($hero) style="background-image: url('{{ asset('images/FB_IMG_1787571425693.jpg') }}')" @endif>
    <div class="hero-shade">
        <div class="container hero-inner">
            <div class="hero-text">
                <h1>Bienvenue à l'Église Évangélique de Peniel</h1>
                <p class="hero-lead">Une famille qui cherche Dieu, qui sert avec humilité et qui avance avec courage.</p>
                <div class="actions">
                    <a class="btn btn-gold" href="{{ route('about') }}">Découvrir l'église</a>
                    <a class="btn btn-light" href="{{ route('events.index') }}">Voir les événements</a>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="devise-band" aria-label="Notre devise">
    <div class="container devise-grid">
        <div><h2>Humilité</h2><p>Se tenir devant Dieu et devant les autres sans orgueil.</p></div>
        <div><h2>Crainte de Dieu</h2><p>Respecter sa Parole et marcher dans son obéissance.</p></div>
        <div><h2>Courage</h2><p>Tenir ferme dans la foi, même quand le chemin est difficile.</p></div>
        <div><h2>Travail</h2><p>Servir avec constance, fidélité et excellence.</p></div>
    </div>
</section>

<section class="section section-alt" id="horaires" aria-labelledby="horaires-titre">
    <div class="container">
        <div class="section-head">
            <h2 id="horaires-titre">Horaires des cultes</h2>
        </div>
        <div class="days">
            @foreach (config('church.services') as $d)
                @php $photo = !empty($d['image']) && file_exists(public_path($d['image'])); @endphp
                <article class="day-card">
                    <div class="day-photo" @if($photo) style="background-image: url('{{ asset($d['image']) }}')" @endif>
                        <h3>{{ $d['day'] }}</h3>
                    </div>
                    <ul class="slots">
                        @foreach ($d['slots'] as $slot)
                            <li>
                                <span class="slot-time">{{ $slot['time'] }}</span>
                                <span class="slot-title">{{ $slot['title'] }}</span>
                                @if (!empty($slot['note']))<span class="slot-note">{{ $slot['note'] }}</span>@endif
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
        <p class="schedule-link"><a class="link" href="{{ route('location') }}">Voir comment nous trouver</a></p>
    </div>
</section>

<section class="section">
    <div class="container split">
        <div>
            <h2>Notre histoire en bref</h2>
            <p>Le 20 février 2000, à Ngiri-Ngiri, un culte commencé avec 8 fidèles à ciel ouvert s'achevait avec 154 fidèles, sous une pluie battante reçue comme l'approbation divine. Ainsi naissait l'église Peniel, fruit de la foi, de l'obéissance et de l'espérance en l'Éternel.</p>
            <a class="link" href="{{ route('history') }}">Lire toute notre histoire</a>
        </div>
        <div class="photo-frame">
            @if (file_exists(public_path('images/Screenshot_20261031_133232_Samsung Browser.jpg')))
                <img src="{{ asset('images/Screenshot_20261031_133232_Samsung Browser.jpg') }}" alt="L'église à ses débuts">
            @else
                <span>Photo de l'histoire</span>
            @endif
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <h2>Dernières publications</h2>
            <a class="link" href="{{ route('events.index') }}">Tout voir</a>
        </div>
        @include('events._list', ['events' => $latest])
    </div>
</section>

<section class="cta-band">
    <div class="container cta-inner">
        <div>
            <h2>Soutenir l'œuvre</h2>
            <p>Votre don aide l'église à servir la communauté et à faire avancer la mission.</p>
        </div>
        <a class="btn btn-gold" href="{{ route('don.create') }}">Faire un don</a>
    </div>
</section>
@endsection
