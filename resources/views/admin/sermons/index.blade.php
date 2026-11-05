@extends('layouts.app')
@section('title', 'Prédications | Administration')
@push('head')<meta name="robots" content="noindex"><link rel="stylesheet" href="{{ asset('css/admin.css') }}">@endpush

@section('content')
@include('admin._nav')

<section class="section">
    <div class="container">
        <div class="section-head">
            <h1>Prédications</h1>
            <a class="btn btn-gold" href="{{ route('admin.sermons.create') }}">Ajouter une prédication</a>
        </div>
        <p>Le site affiche automatiquement les 5 plus récentes sur la page Média.</p>

        @if (session('ok'))<div class="alert alert-ok" role="status">{{ session('ok') }}</div>@endif

        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Titre</th><th>Date</th><th>Prédicateur</th><th>Lien</th><th>Statut</th><th></th></tr></thead>
                <tbody>
                @forelse ($sermons as $sermon)
                    <tr>
                        <td>{{ $sermon->title }}</td>
                        <td>{{ $sermon->preached_at->translatedFormat('d M Y') }}</td>
                        <td>{{ $sermon->preacher }}</td>
                        <td>{{ $sermon->platformLabel() }}</td>
                        <td><span class="badge {{ $sermon->is_published ? 'badge-ok' : 'badge-off' }}">{{ $sermon->is_published ? 'Publié' : 'Brouillon' }}</span></td>
                        <td class="actions-cell">
                            <a class="link" href="{{ route('admin.sermons.edit', $sermon) }}">Modifier</a>
                            <form method="POST" action="{{ route('admin.sermons.destroy', $sermon) }}" onsubmit="return confirm('Supprimer cette prédication ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="link-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Aucune prédication. Ajoutez la première.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pager">
            @if ($sermons->previousPageUrl())<a class="link" href="{{ $sermons->previousPageUrl() }}">Page précédente</a>@else<span></span>@endif
            @if ($sermons->nextPageUrl())<a class="link" href="{{ $sermons->nextPageUrl() }}">Page suivante</a>@endif
        </div>
    </div>
</section>
@endsection
