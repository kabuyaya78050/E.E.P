@extends('layouts.app')
@section('title', ($event->exists ? 'Modifier' : 'Nouvelle publication') . ' | Administration')
@push('head')<meta name="robots" content="noindex"><link rel="stylesheet" href="{{ asset('css/admin.css') }}">@endpush

@section('content')
@include('admin._nav')

<section class="section">
    <div class="container narrow">
        <h1>{{ $event->exists ? 'Modifier la publication' : 'Nouvelle publication' }}</h1>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <strong>Le formulaire contient des erreurs.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form class="form" method="POST" enctype="multipart/form-data"
              action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}">
            @csrf
            @if ($event->exists) @method('PUT') @endif

            <label for="title">Titre</label>
            <input id="title" name="title" type="text" value="{{ old('title', $event->title) }}" required>

            <div class="row">
                <div>
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        @foreach (\App\Models\Event::TYPES as $key => $t)
                            <option value="{{ $key }}" @selected(old('type', $event->type) === $key)>{{ $t[0] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="starts_at">Date et heure de début</label>
                    <input id="starts_at" name="starts_at" type="datetime-local"
                           value="{{ old('starts_at', optional($event->starts_at)->format('Y-m-d\TH:i')) }}">
                </div>
            </div>

            <label for="location">Lieu de l'événement</label>
            <input id="location" name="location" type="text" value="{{ old('location', $event->location) }}">

            <div class="row">
                <div>
                    <label for="departure_time">Heure de départ (facultatif)</label>
                    <input id="departure_time" name="departure_time" type="time" value="{{ old('departure_time', $event->departure_time) }}">
                </div>
                <div>
                    <label for="departure_place">Lieu de départ (facultatif)</label>
                    <input id="departure_place" name="departure_place" type="text" value="{{ old('departure_place', $event->departure_place) }}" placeholder="Ex. devant l'église">
                </div>
            </div>

            <label for="excerpt">Résumé (300 caractères max.)</label>
            <input id="excerpt" name="excerpt" type="text" maxlength="300" value="{{ old('excerpt', $event->excerpt) }}">

            <label for="body">Description</label>
            <textarea id="body" name="body" rows="10" required>{{ old('body', $event->body) }}</textarea>

            <label for="image">Photo (JPG, PNG ou WebP, 3 Mo max.)</label>
            @if ($event->image)<img class="thumb" src="{{ asset($event->image) }}" alt="">@endif
            <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">

            <label class="check">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $event->is_published))>
                Publier sur le site
            </label>

            <button class="btn btn-gold btn-block" type="submit">{{ $event->exists ? 'Enregistrer les modifications' : 'Publier' }}</button>
        </form>
    </div>
</section>
@endsection
