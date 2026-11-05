@extends('layouts.app')
@section('title', 'Mission et devise | ' . config('church.name'))

@section('content')
<section class="page-head">
    <div class="container">
        <h1>Mission et devise</h1>
        <p>Notre appel et les valeurs qui guident notre marche.</p>
    </div>
</section>
@include('pages._tabs')

<section class="section">
    <div class="container narrow prose">
        <h2>Notre mission</h2>
        <p>Prophetique et de Delivrance mondiale</p>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <h2>Notre devise</h2>
        <p class="lead">Humilité, Crainte de Dieu, Courage et Travail.</p>
        <div class="devise-grid devise-grid-flat">
            <div><h3>Humilité</h3><p>1 PRIERRE 5:5-7</p></div>
            <div><h3>Crainte de Dieu</h3><p>HEBREUX 10:1-30</p></div>
            <div><h3>Courage</h3><p>JOSUE 1:9</p></div>
            <div><h3>Travail</h3><p>2 THESSALONICIENS 3:8-10</p></div>
        </div>
    </div>
</section>
@endsection
