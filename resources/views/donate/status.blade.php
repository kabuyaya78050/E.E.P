@extends('layouts.app')
@section('title', 'Statut du don | Église Évangélique de Peniel')

@section('content')
<section class="page-head">
    <div class="container">
        <h1>Votre don</h1>
        <p>Référence {{ $donation->reference }}</p>
    </div>
</section>

<section class="section">
    <div class="container narrow">
        @if ($donation->status === 'paid')
            <div class="alert alert-ok"><strong>Merci, votre don est confirmé.</strong> Que Dieu vous bénisse.</div>
        @elseif ($donation->status === 'failed')
            <div class="alert alert-error">
                <strong>Le paiement n'a pas pu être lancé.</strong>
                {{ data_get($donation->gateway_response, 'message', 'Veuillez réessayer.') }}
            </div>
        @else
            <div class="alert alert-wait">
                @if (data_get($donation->gateway_response, 'errors.outcome_unknown'))
                    <strong>Résultat du paiement incertain.</strong>
                    Ne relancez pas cette tentative. Contactez le support avec cette référence.
                @elseif (config('services.labyrinthe.mode') === 'sandbox' && data_get($donation->gateway_response, 'success'))
                    <strong>Réponse du sandbox reçue.</strong>
                    Le mode de test ne confirme ni ne prélève un don réel.
                @else
                    <strong>En attente de confirmation.</strong>
                    {{ data_get($donation->gateway_response, 'message', 'La confirmation du paiement est en attente.') }}
                @endif
            </div>
        @endif

        <dl class="summary">
            <dt>Montant</dt><dd>{{ number_format((float) $donation->amount, 2, ',', ' ') }} {{ $donation->currency }}</dd>
            <dt>Nature</dt><dd>{{ $donation->purposeLabel() }}</dd>
            <dt>Téléphone</dt><dd>{{ $donation->phone }}</dd>
        </dl>

        @if ($donation->status === 'pending' && $donation->order_number)
            <form method="POST" action="{{ route('don.verify', $donation) }}">
                @csrf
                <button class="btn btn-gold" type="submit">Vérifier mon paiement</button>
            </form>
        @elseif ($donation->status === 'pending')
            <p class="hint">Aucun numéro de transaction vérifiable n’a été reçu.</p>
        @elseif ($donation->status === 'failed')
            <a class="btn btn-gold" href="{{ route('don.create') }}">Réessayer</a>
        @else
            <a class="btn btn-gold" href="{{ route('home') }}">Retour à l'accueil</a>
        @endif
    </div>
</section>
@endsection
