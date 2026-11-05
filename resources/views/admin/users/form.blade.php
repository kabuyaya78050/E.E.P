@extends('layouts.app')
@section('title', ($user->exists ? 'Modifier l\'administrateur' : 'Nouvel administrateur') . ' | Administration')
@push('head')<meta name="robots" content="noindex"><link rel="stylesheet" href="{{ asset('css/admin.css') }}">@endpush

@section('content')
@include('admin._nav')

<section class="section">
    <div class="container narrow">
        <h1>{{ $user->exists ? "Modifier l'administrateur" : 'Nouvel administrateur' }}</h1>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <strong>Le formulaire contient des erreurs.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form class="form" method="POST"
              action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
            @csrf
            @if ($user->exists) @method('PUT') @endif

            <label for="name">Nom</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="off">

            <label for="email">E-mail (identifiant de connexion)</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="off">

            <div class="row">
                <div>
                    <label for="password">{{ $user->exists ? 'Nouveau mot de passe (facultatif)' : 'Mot de passe' }}</label>
                    <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" @if (! $user->exists) required @endif>
                </div>
                <div>
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password">
                </div>
            </div>
            <p class="hint">8 caractères minimum.</p>

            <button class="btn btn-gold btn-block" type="submit">{{ $user->exists ? 'Enregistrer' : 'Créer l\'administrateur' }}</button>
        </form>
    </div>
</section>
@endsection
