@extends('layouts.app')
@section('title', 'Publications | Administration')
@push('head')<meta name="robots" content="noindex"><link rel="stylesheet" href="{{ asset('css/admin.css') }}">@endpush

@section('content')
@include('admin._nav')

<section class="section">
    <div class="container">
        <div class="section-head">
            <h1>Publications</h1>
            <a class="btn btn-gold" href="{{ route('admin.events.create') }}">Nouvelle publication</a>
        </div>

        @if (session('ok'))<div class="alert alert-ok" role="status">{{ session('ok') }}</div>@endif

        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Titre</th><th>Type</th><th>Date</th><th>Statut</th><th></th></tr></thead>
                <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->typeLabel() }}</td>
                        <td>{{ ($event->starts_at ?? $event->created_at)->translatedFormat('d M Y') }}</td>
                        <td><span class="badge {{ $event->is_published ? 'badge-ok' : 'badge-off' }}">{{ $event->is_published ? 'Publié' : 'Brouillon' }}</span></td>
                        <td class="actions-cell">
                            <a class="link" href="{{ route('admin.events.edit', $event) }}">Modifier</a>
                            <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Supprimer cette publication ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Aucune publication. Créez la première.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">
            @if ($events->previousPageUrl())<a class="link" href="{{ $events->previousPageUrl() }}">Page précédente</a>@else<span></span>@endif
            @if ($events->nextPageUrl())<a class="link" href="{{ $events->nextPageUrl() }}">Page suivante</a>@endif
        </div>
    </div>
</section>
@endsection
