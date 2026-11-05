@extends('layouts.app')
@section('title', 'Inscription administrateur | Administration')
@push('head')<meta name="robots" content="noindex">@endpush

@section('content')
<section class="page-head">
    <div class="container"><h1>Créer le premier compte administrateur</h1><p>Configurez le compte qui gérera le site.</p></div>
</section>

<section class="section">
    <div class="container narrow">
        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <strong>Le formulaire contient des erreurs.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form class="form" method="POST" action="{{ route('admin.register.store') }}">
            @csrf

            <label for="name">Nom</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="120" autocomplete="name">

            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="150" autocomplete="username">

            <label for="password">Mot de passe</label>
            <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password">

            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password">

            <button class="btn btn-gold btn-block" type="submit">Créer le compte</button>
        </form>
    </div>
</section>
@endsection