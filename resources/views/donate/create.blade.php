@extends('layouts.app')
@section('title', 'Faire un don | Église Évangélique de Peniel')

@section('content')
<section class="page-head">
    <div class="container">
        <h1>Faire un don</h1>
        <p>Votre générosité soutient la vie et la mission de l'église.</p>
    </div>
</section>

<section class="section">
    <div class="container narrow">
        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <strong>Le formulaire contient des erreurs.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        @if (config('services.labyrinthe.mode') === 'sandbox')
            <div class="alert alert-wait" role="status">
                Mode de test Labyrinthe actif : aucun paiement réel ne sera prélevé.
            </div>
        @else
            <div class="alert alert-error" role="alert">
                Le paiement en production n’est pas activé tant que Labyrinthe n’a pas confirmé les paramètres officiels de son API live.
            </div>
        @endif

        <form class="form" method="POST" action="{{ route('don.store') }}" novalidate>
            @csrf
            <input type="hidden" name="submission_key" value="{{ $submissionKey }}">

            <label for="name">Nom complet</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name">

            <div class="row">
                <div>
                    <label for="phone">Téléphone Mobile Money</label>
                    <input id="phone" name="phone" type="tel" inputmode="numeric" value="{{ old('phone') }}" placeholder="243812345678" required autocomplete="tel">
                </div>
                <div>
                    <label for="email">E-mail (facultatif)</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email">
                </div>
            </div>

            <label for="purpose">Nature du don</label>
            <select id="purpose" name="purpose" required>
                @foreach (\App\Models\Donation::PURPOSES as $key => $label)
                    <option value="{{ $key }}" @selected(old('purpose', 'offrande') === $key)>{{ $label }}</option>
                @endforeach
            </select>

            <div class="row">
                <div>
                    <label for="amount">Montant</label>
                    <input id="amount" name="amount" type="number" min="1" step="0.01" value="{{ old('amount') }}" required>
                </div>
                <div>
                    <label for="currency">Devise</label>
                    <select id="currency" name="currency" required>
                        <option value="USD" @selected(old('currency', 'USD') === 'USD')>USD</option>
                        <option value="CDF" @selected(old('currency') === 'CDF')>CDF</option>
                    </select>
                </div>
            </div>

            <button class="btn btn-gold btn-block" type="submit" @disabled(config('services.labyrinthe.mode') !== 'sandbox')>
                {{ config('services.labyrinthe.mode') === 'sandbox' ? 'Tester la demande Labyrinthe' : 'Paiement désactivé' }}
            </button>
            @if (config('services.labyrinthe.mode') === 'sandbox')
                <p class="hint">Le sandbox vérifie la connexion API; il ne simule pas le débit auprès des opérateurs Mobile Money.</p>
            @endif
        </form>
    </div>
</section>
@endsection
