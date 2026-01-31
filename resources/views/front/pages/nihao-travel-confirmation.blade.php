@extends('front.layouts.master')

@section('title', 'Confirmation d\'inscription - Nihao Travel')

@section('styles')
<style>
    .confirmation-container {
        max-width: 800px;
        margin: 3rem auto;
        text-align: center;
    }

    .confirmation-icon {
        font-size: 4rem;
        color: #28a745;
        margin-bottom: 1.5rem;
    }

    .confirmation-box {
        background: white;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border-top: 4px solid #b78d65;
    }

    .confirmation-details {
        text-align: left;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px dashed #dee2e6;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #495057;
    }

    .detail-value {
        color: #212529;
    }

    .action-buttons {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn-confirmation {
        padding: 0.75rem 2rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #b78d65;
        color: white;
        border: 2px solid #b78d65;
    }

    .btn-primary:hover {
        background: #9a7150;
        border-color: #9a7150;
    }

    .btn-outline {
        background: transparent;
        color: #b78d65;
        border: 2px solid #b78d65;
    }

    .btn-outline:hover {
        background: rgba(183, 141, 101, 0.1);
    }
</style>
@endsection

@section('content')

<div class="confirmation-container">
    <div class="confirmation-icon">
        <i class="ri-checkbox-circle-fill"></i>
    </div>

    <div class="confirmation-box">
        <h1>Inscription confirmée !</h1>
        <p class="lead">Merci pour votre confiance, {{ $travel->first_name }}.</p>

        <div class="confirmation-details">
            <h5 style="margin-bottom: 1rem; color: #b78d65;">Détails de votre inscription</h5>

            <div class="detail-row">
                <span class="detail-label">Référence :</span>
                <span class="detail-value">{{ $travel->code }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Nom complet :</span>
                <span class="detail-value">{{ $travel->first_name }} {{ $travel->last_name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Édition choisie :</span>
                <span class="detail-value">{{ $travel->canton_edition }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Montant payé :</span>
                <span class="detail-value">{{ FrontHelper::format_currency($travel->amount_xof) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Date d'inscription :</span>
                <span class="detail-value">{{ $travel->created_at->format('d/m/Y à H:i') }}</span>
            </div>


        </div>

        {{-- <div class="alert alert-info" style="text-align: left;">
            <i class="ri-information-line"></i>
            <strong>Important :</strong> Notre équipe vous contactera dans les <strong>24 heures ouvrables</strong>
            pour planifier la suite de votre dossier. Un email de confirmation a été envoyé à <strong>{{ $travel->email }}</strong>.
        </div> --}}

        <div class="action-buttons">
            <a href="{{ route('index') }}" class="btn-confirmation btn-primary">
                Retour à l'accueil
            </a>
            <a href="{{ route('nihao.travel') }}" class="btn-confirmation btn-outline">
                Retour à Nihao Travel
            </a>
        </div>
    </div>
</div>

@endsection
