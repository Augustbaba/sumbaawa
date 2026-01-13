@extends('front.layouts.master')

@section('title', 'Paiement des frais de livraison')

@section('styles')
<style>
    .payment-container {
        max-width: 600px;
        margin: 3rem auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .payment-header {
        background: linear-gradient(135deg, #b78d65, #9a7150);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .payment-content {
        padding: 2rem;
    }

    .order-summary {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px dashed #ddd;
    }

    .summary-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .total-amount {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px solid #b78d65;
        font-size: 1.2rem;
        font-weight: bold;
        color: #b78d65;
    }

    .payment-methods {
        display: flex;
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .payment-method {
        flex: 1;
        text-align: center;
        padding: 1rem;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .payment-method.selected {
        border-color: #b78d65;
        background-color: rgba(183, 141, 101, 0.05);
    }

    .payment-method i {
        font-size: 2rem;
        color: #b78d65;
        margin-bottom: 0.5rem;
    }

    #paypal-button-container {
        margin: 2rem auto;
        text-align: center;
    }

    .info-box {
        background-color: #fff9e6;
        border-left: 4px solid #ffc107;
        padding: 1rem;
        margin: 1.5rem 0;
        border-radius: 4px;
    }

    .info-box i {
        color: #ffc107;
        margin-right: 0.5rem;
    }

    @media (max-width: 768px) {
        .payment-methods {
            flex-direction: column;
        }

        .payment-container {
            margin: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="payment-container">
    <div class="payment-header">
        <h2><i class="ri-truck-line me-2"></i>Paiement des frais de livraison</h2>
        <p class="mb-0" style="color: white">Commande #{{ $commande->code }}</p>
    </div>

    <div class="payment-content">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="order-summary">
            <h5 class="mb-3">Récapitulatif des frais</h5>

            <div class="summary-item">
                <span>Frais de livraison :</span>
                <span>{{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF</span>
            </div>

            @if($commande->delivery_method == 'tinda_awa' && $commande->estimated_delivery)
            <div class="summary-item">
                <span>Livraison estimée :</span>
                <span>{{ \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y') }}</span>
            </div>
            @endif

            <div class="summary-item total-amount">
                <span>Total à payer :</span>
                <span>{{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF</span>
            </div>
        </div>

        <div class="info-box">
            <i class="ri-information-line"></i>
            <strong>Important :</strong> Ce paiement concerne uniquement les frais de livraison.
            Les articles de votre commande ont déjà été payés.
        </div>

        <div class="payment-methods">
            <div class="payment-method selected" id="paypalMethod">
                <i class="ri-paypal-line"></i>
                <div>PayPal</div>
                <small style="color: var(--text-muted);">Carte bancaire, PayPal</small>
            </div>
        </div>

        <div id="paypal-button-container"></div>

        <div class="mt-4 text-center">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-2"></i>Retour au tableau de bord
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@php
    $mode = config('services.paypal.mode');
    $clientId = config("services.paypal.{$mode}.client_id");
    $amountInUSD = round($commande->shipping_fee / 600, 2); // Conversion XOF en USD
@endphp
<script src="https://www.paypal.com/sdk/js?client-id={{ $clientId }}&currency=USD&intent=capture"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        renderPayPalButton();
    });

    function renderPayPalButton() {
        const amountUSD = {{ $amountInUSD }};

        paypal.Buttons({
            style: {
                layout: 'vertical',
                color:  'gold',
                shape:  'rect',
                label:  'paypal'
            },

            createOrder: function(data, actions) {
                return fetch("{{ route('shipping.paypal.create') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        commande_id: {{ $commande->id }},
                        amount: amountUSD
                    })
                }).then(function(res) {
                    return res.json();
                }).then(function(data) {
                    if (data.orderID) {
                        return data.orderID;
                    } else {
                        throw new Error(data.error || 'Erreur lors de la création du paiement');
                    }
                });
            },

            onApprove: function(data, actions) {
                return fetch("{{ route('shipping.paypal.capture') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        orderID: data.orderID
                    })
                }).then(function(res) {
                    return res.json();
                }).then(function(details) {
                    if (details.success) {
                        // Rediriger vers la page de succès
                        window.location.href = "{{ route('shipping.payment.success') }}";
                    } else {
                        Swal.fire({
                            title: 'Erreur de paiement',
                            text: details.error || 'Le paiement n\'a pas pu être traité.',
                            icon: 'error',
                            confirmButtonColor: '#b78d65'
                        });
                    }
                });
            },

            onError: function(err) {
                Swal.fire({
                    title: 'Erreur',
                    text: 'Une erreur est survenue lors du traitement du paiement. Veuillez réessayer.',
                    icon: 'error',
                    confirmButtonColor: '#b78d65'
                });
            },

            onCancel: function(data) {
                Swal.fire({
                    title: 'Paiement annulé',
                    text: 'Vous avez annulé le processus de paiement.',
                    icon: 'info',
                    confirmButtonColor: '#b78d65'
                });
            }
        }).render('#paypal-button-container');
    }
</script>
@endsection
