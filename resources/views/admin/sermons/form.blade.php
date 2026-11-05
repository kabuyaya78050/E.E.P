@extends('layouts.app')
@section('title', ($sermon->exists ? 'Modifier la prédication' : 'Nouvelle prédication') . ' | Administration')
@push('head')<meta name="robots" content="noindex"><link rel="stylesheet" href="{{ asset('css/admin.css') }}">@endpush

@section('content')
@include('admin._nav')

<section class="section">
    <div class="container narrow">
        <h1>{{ $sermon->exists ? 'Modifier la prédication' : 'Nouvelle prédication' }}</h1>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <strong>Le formulaire contient des erreurs.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form class="form" method="POST"
              action="{{ $sermon->exists ? route('admin.sermons.update', $sermon) : route('admin.sermons.store') }}">
            @csrf
            @if ($sermon->exists) @method('PUT') @endif

            <label for="title">Titre</label>
            <input id="title" name="title" type="text" value="{{ old('title', $sermon->title) }}" required>

            <label for="video_url">Lien de la vidéo (YouTube ou Facebook)</label>
            <input id="video_url" name="video_url" type="url" value="{{ old('video_url', $sermon->video_url) }}"
                   placeholder="https://www.youtube.com/watch?v=..." required>
            <p class="hint">Collez simplement l'adresse de la vidéo : elle s'intègre automatiquement sur le site.</p>

            <div class="row">
                <div>
                    <label for="preached_at">Date</label>
                    <input id="preached_at" name="preached_at" type="date"
                           value="{{ old('preached_at', optional($sermon->preached_at)->format('Y-m-d')) }}" required>
                </div>
                <div>
                    <label for="preacher">Prédicateur</label>
                    <input id="preacher" name="preacher" type="text" value="{{ old('preacher', $sermon->preacher) }}">
                </div>
            </div>

            <label for="scripture">Texte biblique (facultatif)</label>
            <input id="scripture" name="scripture" type="text" value="{{ old('scripture', $sermon->scripture) }}">

            <label for="description">Résumé (facultatif)</label>
            <textarea id="description" name="description" rows="4" maxlength="2000">{{ old('description', $sermon->description) }}</textarea>

            <label class="check">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $sermon->is_published))>
                Publier sur le site
            </label>

            <button class="btn btn-gold btn-block" type="submit">{{ $sermon->exists ? 'Enregistrer' : 'Ajouter' }}</button>
        </form>
    </div>
</section>
@endsection
