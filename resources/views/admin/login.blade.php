@extends('layouts.app')
@section('title', 'Connexion | Administration')
@push('head')<meta name="robots" content="noindex">@endpush

@section('content')
<section class="page-head">
    <div class="container"><h1>Administration</h1><p>Connectez-vous pour gérer le site.</p></div>
</section>

<section class="section">
    <div class="container narrow">
        <form class="form" method="POST" action="{{ route('login') }}">
            @csrf
            @error('email')<div class="alert alert-error" role="alert">{{ $message }}</div>@enderror

            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">

            <label for="password">Mot de passe</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">

            <label class="check"><input type="checkbox" name="remember" value="1"> Rester connecté</label>

            <button class="btn btn-gold btn-block" type="submit">Se connecter</button>
        </form>
    </div>
</section>
@endsection
