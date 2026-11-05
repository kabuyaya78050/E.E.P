@extends('layouts.app')
@section('title', 'Dons | Administration')
@push('head')<meta name="robots" content="noindex"><link rel="stylesheet" href="{{ asset('css/admin.css') }}">@endpush

@section('content')
@include('admin._nav')

<section class="section">
    <div class="container">
        <h1>Dons</h1>

        <p>
            Total confirmé :
            @forelse ($totals as $currency => $total)
                <strong>{{ number_format((float) $total, 2, ',', ' ') }} {{ $currency }}</strong>@if (! $loop->last), @endif
            @empty
                aucun don confirmé pour le moment.
            @endforelse
        </p>

        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Date</th><th>Donateur</th><th>Téléphone</th><th>Nature</th><th>Montant</th><th>Statut</th><th>Référence</th></tr></thead>
                <tbody>
                @forelse ($donations as $d)
                    <tr>
                        <td>{{ $d->created_at->translatedFormat('d M Y H:i') }}</td>
                        <td>{{ $d->name }}</td>
                        <td>{{ $d->phone }}</td>
                        <td>{{ $d->purposeLabel() }}</td>
                        <td>{{ number_format((float) $d->amount, 2, ',', ' ') }} {{ $d->currency }}</td>
                        <td><span class="badge badge-{{ $d->status }}">{{ ['paid' => 'Confirmé', 'pending' => 'En attente', 'failed' => 'Échoué'][$d->status] ?? $d->status }}</span></td>
                        <td>{{ $d->reference }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7">Aucun don enregistré.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">
            @if ($donations->previousPageUrl())<a class="link" href="{{ $donations->previousPageUrl() }}">Page précédente</a>@else<span></span>@endif
            @if ($donations->nextPageUrl())<a class="link" href="{{ $donations->nextPageUrl() }}">Page suivante</a>@endif
        </div>
    </div>
</section>
@endsection
