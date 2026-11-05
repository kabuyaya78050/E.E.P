@extends('layouts.app')
@section('title', 'Vision | ' . config('church.name'))

@section('content')
<section class="page-head">
    <div class="container">
        <h1>Notre vision</h1>
        <p>Sept points qui orientent la marche de l'église.</p>
    </div>
</section>
@include('pages._tabs')

<section class="section">
    <div class="container narrow">
        <p class="lead">{{ config('church.vision.intro') }}</p>

        <ol class="vision-list">
            @foreach (config('church.vision.points') as $point)
                <li>
                    <h2>{{ $point['title'] }}</h2>
                    @if (! empty($point['text']))<p>{{ $point['text'] }}</p>@endif
                    @if (! empty($point['refs']))<p class="refs">{{ $point['refs'] }}</p>@endif
                </li>
            @endforeach
        </ol>

        <p><a class="link" href="{{ route('mission') }}">Découvrir notre mission et notre devise</a></p>
    </div>
</section>
@endsection
