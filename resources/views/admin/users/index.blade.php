@extends('layouts.app')
@section('title', 'Administrateurs | Administration')
@push('head')<meta name="robots" content="noindex"><link rel="stylesheet" href="{{ asset('css/admin.css') }}">@endpush

@section('content')
@include('admin._nav')

<section class="section">
    <div class="container">
        <div class="section-head">
            <h1>Administrateurs</h1>
            <a class="btn btn-gold" href="{{ route('admin.users.create') }}">Ajouter un administrateur</a>
        </div>
        <p>Chaque administrateur a un accès complet à l'administration. N'ajoutez que des personnes de confiance.</p>

        @if (session('ok'))<div class="alert alert-ok" role="status">{{ session('ok') }}</div>@endif
        @error('user')<div class="alert alert-error" role="alert">{{ $message }}</div>@enderror

        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Nom</th><th>E-mail</th><th>Créé le</th><th></th></tr></thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }} @if ($user->id === auth()->id())<span class="badge badge-ok">Vous</span>@endif</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at?->translatedFormat('d M Y') }}</td>
                        <td class="actions-cell">
                            <a class="link" href="{{ route('admin.users.edit', $user) }}">Modifier</a>
                            @if ($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet administrateur ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="link-danger">Supprimer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
