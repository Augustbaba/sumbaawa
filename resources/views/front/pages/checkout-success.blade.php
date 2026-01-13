@extends('front.layouts.master')
@section('title', 'Commande confirmée')

@section('content')
<div class="container" style="max-width: 800px; margin: 4rem auto; text-align: center;">
    <div style="background: white; padding: 3rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
        @if($order->payment_status == 'paid')
            <i class="ri-checkbox-circle-fill" style="font-size: 5rem; color: #4CAF50;"></i>
            <h1 style="margin: 1.5rem 0; color: #1a1a1a;">Merci pour votre commande !</h1>
        @else
            <i class="ri-time-line" style="font-size: 5rem; color: #FF9800;"></i>
            <h1 style="margin: 1.5rem 0; color: #1a1a1a;">Commande en attente de paiement</h1>
        @endif

        <div style="background: #f8f8f8; padding: 1.5rem; border-radius: 8px; margin: 2rem 0;">
            <h3 style="color: #b78d65; margin-bottom: 1rem;">Résumé de votre commande</h3>

            <div style="text-align: left; display: inline-block;">
                <p><strong>Numéro de commande:</strong> {{ $order->code }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Total:</strong> {{ number_format($order->total_amount, 0, '.', ' ') }} XOF</p>
                <p><strong>Méthode de paiement:</strong> {{ $order->payment_method }}</p>
                <p><strong>Statut:</strong>
                    @if($order->payment_status == 'paid')
                        <span style="color: #4CAF50;">Payé</span>
                    @else
                        <span style="color: #FF9800;">En attente</span>
                    @endif
                </p>

                @if($order->address)
                <p><strong>Adresse de livraison:</strong> {{ $order->address }}</p>
                @endif
            </div>
        </div>

        <!-- Détails des produits -->
        <div style="margin: 2rem 0; text-align: left;">
            <h3 style="color: #b78d65; margin-bottom: 1rem;">Articles commandés</h3>
            <div style="background: white; border: 1px solid #eee; border-radius: 8px; padding: 1rem;">
                @foreach($order->commandesProduits as $item)
                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f5f5f5;">
                    <div>
                        <strong>{{ $item->produit->name ?? 'Produit' }}</strong>
                        <div style="font-size: 0.9rem; color: #777;">
                            Quantité: {{ $item->quantity }}
                            @if($item->description_produit)
                            <br>{{ $item->description_produit }}
                            @endif
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div>{{ number_format($item->unit_price, 0, '.', ' ') }} XOF</div>
                        <div style="font-weight: bold; color: #b78d65;">
                            {{ number_format($item->total_price, 0, '.', ' ') }} XOF
                        </div>
                    </div>
                </div>
                @endforeach

                <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-top: 2px solid #eee; margin-top: 1rem;">
                    <div style="font-weight: bold;">Total</div>
                    <div style="font-weight: bold; color: #b78d65; font-size: 1.2rem;">
                        {{ number_format($order->total_amount, 0, '.', ' ') }} XOF
                    </div>
                </div>
            </div>
        </div>

        <p style="font-size: 1.1rem; margin: 2rem 0;">
            @if($order->payment_status === 'paid')
                Un email de confirmation vous a été envoyé avec les détails de votre commande.
            @else
                Votre commande est enregistrée. Veuillez finaliser le paiement pour la confirmer.
            @endif
        </p>

        <div style="margin-top: 2rem;">
            <a href="{{ route('index') }}" class="btn" style="
                background: #b78d65;
                color: white;
                padding: 0.8rem 2rem;
                border-radius: 6px;
                text-decoration: none;
                display: inline-block;
                margin: 0 0.5rem;
                transition: all 0.3s;
            ">Retour à l'accueil</a>

            <a href="{{ route('dashboard') }}" class="btn" style="
                background: #2a2a2a;
                color: white;
                padding: 0.8rem 2rem;
                border-radius: 6px;
                text-decoration: none;
                display: inline-block;
                margin: 0 0.5rem;
                transition: all 0.3s;
            ">Voir mes commandes</a>
        </div>
    </div>
</div>
@endsection
