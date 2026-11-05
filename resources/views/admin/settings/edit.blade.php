@extends('layouts.app')
@section('title', 'Réglages | Administration')
@push('head')<meta name="robots" content="noindex"><link rel="stylesheet" href="{{ asset('css/admin.css') }}">@endpush

@section('content')
@include('admin._nav')

<section class="section">
    <div class="container narrow">
        <h1>Réglages</h1>
        <p>Ces informations apparaissent dans le pied de page, la page « Nous trouver » et la page Média.</p>

        @if (session('ok'))<div class="alert alert-ok" role="status">{{ session('ok') }}</div>@endif
        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <strong>Le formulaire contient des erreurs.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form class="form" method="POST" action="{{ route('admin.settings.update') }}">
            @csrf @method('PUT')

            <h2>Culte en direct</h2>
            <label for="live_url">Lien du direct (YouTube ou Facebook)</label>
            <input id="live_url" name="live_url" type="url" value="{{ old('live_url', $values['live_url']) }}" placeholder="https://www.youtube.com/watch?v=...">
            <p class="hint">Collez le lien de la diffusion avant le culte. Avec une chaîne YouTube, vous pouvez coller le lien de la chaîne sous la forme youtube.com/channel/UC... : le direct en cours s'affiche automatiquement.</p>
            <label class="check">
                <input type="checkbox" name="live_active" value="1" @checked(old('live_active', $values['live_active']))>
                Direct en cours (affiche une bannière « En direct » sur tout le site)
            </label>

            <h2>Contact</h2>
            <label for="address">Adresse de l'église</label>
            <input id="address" name="address" type="text" value="{{ old('address', $values['address']) }}">

            <div class="row">
                <div>
                    <label for="phone">Téléphone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $values['phone']) }}">
                </div>
                <div>
                    <label for="whatsapp">WhatsApp (facultatif)</label>
                    <input id="whatsapp" name="whatsapp" type="text" value="{{ old('whatsapp', $values['whatsapp']) }}" placeholder="243812345678">
                </div>
            </div>

            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email', $values['email']) }}">

            <h2>Carte</h2>
            <label for="map_query">Emplacement sur la carte</label>
            <input id="map_query" name="map_query" type="text" value="{{ old('map_query', $values['map_query']) }}"
                   placeholder="Adresse complète, ou coordonnées : -4.3250, 15.3000">
            <p class="hint">Laissez vide pour utiliser l'adresse ci-dessus. Les coordonnées GPS donnent le point le plus précis (clic droit sur Google Maps, puis copier les coordonnées).</p>

            <h2>Réseaux sociaux (icônes du pied de page)</h2>
            <label for="facebook_url">Page Facebook</label>
            <input id="facebook_url" name="facebook_url" type="url" value="{{ old('facebook_url', $values['facebook_url']) }}" placeholder="https://www.facebook.com/votrepage">

            <label for="tiktok_url">Compte TikTok</label>
            <input id="tiktok_url" name="tiktok_url" type="url" value="{{ old('tiktok_url', $values['tiktok_url']) }}" placeholder="https://www.tiktok.com/@votrecompte">

            <label for="instagram_url">Compte Instagram</label>
            <input id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url', $values['instagram_url']) }}" placeholder="https://www.instagram.com/votrecompte">

            <label for="youtube_url">Chaîne YouTube</label>
            <input id="youtube_url" name="youtube_url" type="url" value="{{ old('youtube_url', $values['youtube_url']) }}" placeholder="https://www.youtube.com/@votrechaine">

            <button class="btn btn-gold btn-block" type="submit">Enregistrer</button>
        </form>
    </div>
</section>
@endsection
