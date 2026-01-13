@extends('front.layouts.master')

@section('title', 'Paiement réussi')

@section('styles')
<style>
    .success-container {
        max-width: 600px;
        margin: 3rem auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
        text-align: center;
    }

    .success-header {
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        padding: 2rem;
    }

    .success-header i {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    .success-content {
        padding: 2rem;
    }

    .details-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        text-align: left;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px dashed #ddd;
    }

    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }

        .success-container {
            margin: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="success-container">
    <div class="success-header">
        <i class="ri-checkbox-circle-fill"></i>
        <h2>Paiement réussi !</h2>
        <p  style="color: white">Vos frais de livraison ont été payés avec succès</p>
    </div>

    <div class="success-content">
        <div class="details-card">
            <h5 class="mb-3">Détails du paiement</h5>

            <div class="detail-item">
                <span>Commande :</span>
                <strong>#{{ $commande->code }}</strong>
            </div>

            <div class="detail-item">
                <span>Type de livraison :</span>
                <strong>
                    @if($commande->delivery_method == 'tinda_awa')
                        Tinda Awa
                    @else
                        Cargo
                    @endif
                </strong>
            </div>

            <div class="detail-item">
                <span>Frais de livraison payés :</span>
                <strong>{{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF</strong>
            </div>

            <div class="detail-item">
                <span>Date du paiement :</span>
                <strong>{{ now()->format('d/m/Y H:i') }}</strong>
            </div>
        </div>

        <div class="alert alert-info">
            <i class="ri-information-line me-2"></i>
            @if($commande->delivery_method == 'tinda_awa')
                Votre commande sera maintenant expédiée via Tinda Awa.
                @if($commande->estimated_delivery)
                    Livraison estimée : <strong>{{ \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y') }}</strong>
                @endif
            @else
                Votre commande est maintenant prête à être récupérée par votre cargo.
            @endif
        </div>

        <div class="action-buttons">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="ri-dashboard-line me-2"></i>Tableau de bord
            </a>

            <a href="{{ route('user.orders.show', $commande) }}" class="btn btn-outline-primary">
                <i class="ri-truck-line me-2"></i>Suivre ma commande
            </a>


        </div>
    </div>
</div>
@endsection
