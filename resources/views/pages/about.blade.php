@extends('layouts.app')
@section('title', 'À propos | ' . config('church.name'))

@section('content')
<section class="page-head">
    <div class="container">
        <h1>À propos</h1>
        <p>Qui nous sommes, ce que nous croyons et où Dieu nous conduit.</p>
    </div>
</section>
@include('pages._tabs')

<section class="section">
    <div class="container narrow prose">
        <p class="lead">Eglise Evangelique de Peniel est une Ecole d'edification,d'enseignement.</p>

        <ul class="entry-list">
            <li>
                <h2><a href="{{ route('vision') }}">Vision</a></h2>
                <p>Ce que Dieu a placé dans le cœur de l'église pour l'avenir.</p>
            </li>
            <li>
                <h2><a href="{{ route('mission') }}">Mission et devise</a></h2>
                <p>Notre appel, et les quatre valeurs qui guident notre marche.</p>
            </li>
            <li>
                <h2><a href="{{ route('history') }}">Historique de l'église</a></h2>
                <p>Les grands moments de notre histoire et la présentation du pasteur.</p>
            </li>
        </ul>
    </div>
</section>
@endsection
