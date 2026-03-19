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
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="order-summary">
            <h5 class="mb-3">Récapitulatif des frais</h5>

            <div class="summary-item">
                <span>Frais de livraison :</span>
                <span id="shipping-fee-display"
                      data-amount-xof="{{ $commande->shipping_fee }}">
                    {{ FrontHelper::format_currency($commande->shipping_fee) }}
                </span>
            </div>

            @if($commande->delivery_method == 'tinda_awa' && $commande->estimated_delivery)
            <div class="summary-item">
                <span>Livraison estimée :</span>
                <span>{{ \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y') }}</span>
            </div>
            @endif

            <div class="summary-item total-amount">
                <span>Total à payer :</span>
                <span id="total-amount-display"
                      data-amount-xof="{{ $commande->shipping_fee }}">
                    {{ FrontHelper::format_currency($commande->shipping_fee) }}
                </span>
            </div>

            <div class="mt-3 text-center" style="font-size: 0.9rem; color: #777;">
                <span id="usd-equivalent"></span>
            </div>
        </div>

        <div class="info-box">
            <i class="ri-information-line"></i>
            <strong>Important :</strong> Ce paiement concerne uniquement les frais de livraison.
            Les articles de votre commande ont déjà été payés.
        </div>

        <!-- Choix du mode de paiement -->
        <div class="payment-methods">
            <div class="payment-method selected" id="paypalMethod"
                 onclick="selectShippingPayment('paypal')">
                <i class="ri-paypal-line"></i>
                <div>PayPal</div>
                <small style="color: #777;">Carte bancaire</small>
            </div>
            <div class="payment-method" id="elongoMethod"
                 onclick="selectShippingPayment('elongopay')">
                <i class="ri-wallet-3-line" style="color: #b78d65;"></i>
                <div style="font-weight:600; color:#b78d65;">ElongoPay</div>
                <small style="color: #777;">Portefeuille digital</small>
            </div>
        </div>

        <!-- Zone PayPal -->
        <div id="paypal-section">
            <div id="paypal-button-container"></div>
        </div>

        <!-- Zone ElongoPay -->
        <div id="elongopay-section" style="display:none; margin-top:1rem;">
            <button onclick="openShippingElongoPayWindow()"
                style="width:100%; padding:1rem;
                       background:linear-gradient(135deg,#007AFF,#8A2BE2);
                       color:white; border:none; border-radius:8px;
                       font-size:1rem; font-weight:700; cursor:pointer;">
                <i class="ri-wallet-3-line"></i> Payer avec ElongoPay
            </button>
        </div>

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
    $mode     = config('services.paypal.mode');
    $clientId = config("services.paypal.{$mode}.client_id");
    $currentCurrency = FrontHelper::current_currency();
@endphp
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/currency.js') }}"></script>
<script src="https://www.paypal.com/sdk/js?client-id={{ $clientId }}&currency=USD&intent=capture"></script>

<script>
    const CURRENCY_CONFIG = {
        currentCode:    '{{ $currentCurrency->code }}',
        currentSymbol:  '{{ $currentCurrency->symbol }}',
        currentRate:    {{ $currentCurrency->exchange_rate }},
        shippingFeeXOF: {{ $commande->shipping_fee }},
        rates: {
            'XOF': 1,
            'EUR': {{ \App\Models\Currency::where('code', 'EUR')->value('exchange_rate') ?? 655.957 }},
            'USD': {{ \App\Models\Currency::where('code', 'USD')->value('exchange_rate') ?? 600 }},
            'GBP': {{ \App\Models\Currency::where('code', 'GBP')->value('exchange_rate') ?? 750 }}
        }
    };

    const COMMANDE_ID = {{ $commande->id }};
    const ELONGOPAY_BASE = '{{ config("services.elongopay.base_url", "http://localhost:8001") }}';

    // ─── UTILITAIRES ──────────────────────────────────────────────────────────

    function convertToUSD(amountXOF) {
        return parseFloat((amountXOF / CURRENCY_CONFIG.rates.USD).toFixed(2));
    }

    function formatAmount(amountInXOF) {
        if (typeof CurrencyHelper !== 'undefined') {
            return CurrencyHelper.format(amountInXOF);
        }
        const converted = amountInXOF / CURRENCY_CONFIG.currentRate;
        const decimals  = CURRENCY_CONFIG.currentCode === 'XOF' ? 0 : 2;
        return converted.toLocaleString('fr-FR', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }) + ' ' + CURRENCY_CONFIG.currentSymbol;
    }

    function updatePriceDisplay() {
        const amountUSD = convertToUSD(CURRENCY_CONFIG.shippingFeeXOF);
        document.querySelectorAll('[data-amount-xof]').forEach(el => {
            el.textContent = formatAmount(parseFloat(el.dataset.amountXof));
        });
        document.getElementById('usd-equivalent').textContent =
            `≈ $${amountUSD.toFixed(2)} USD`;
    }

    // ─── SÉLECTION MODE PAIEMENT ──────────────────────────────────────────────

    let selectedShippingPayment = 'paypal';

    function selectShippingPayment(method) {
        selectedShippingPayment = method;
        document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));

        if (method === 'paypal') {
            document.getElementById('paypalMethod').classList.add('selected');
            document.getElementById('paypal-section').style.display = 'block';
            document.getElementById('elongopay-section').style.display = 'none';
        } else {
            document.getElementById('elongoMethod').classList.add('selected');
            document.getElementById('paypal-section').style.display = 'none';
            document.getElementById('elongopay-section').style.display = 'block';
        }
    }

    // ─── PAYPAL ───────────────────────────────────────────────────────────────

    function renderPayPalButton() {
        const shippingFeeXOF = CURRENCY_CONFIG.shippingFeeXOF;
        const amountUSD      = convertToUSD(shippingFeeXOF);

        paypal.Buttons({
            style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'paypal' },

            createOrder: function() {
                return fetch("{{ route('shipping.paypal.create') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        commande_id: COMMANDE_ID,
                        amount:      amountUSD,
                        currency:    CURRENCY_CONFIG.currentCode,
                        amountInXOF: shippingFeeXOF
                    })
                }).then(r => r.json()).then(data => {
                    if (data.success && data.orderID) return data.orderID;
                    throw new Error(data.error || 'Erreur création commande PayPal');
                }).catch(err => {
                    Swal.fire({ title: 'Erreur', text: err.message, icon: 'error', confirmButtonColor: '#b78d65' });
                });
            },

            onApprove: function(data) {
                return fetch("{{ route('shipping.paypal.capture') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        orderID:     data.orderID,
                        currency:    CURRENCY_CONFIG.currentCode,
                        amountInXOF: shippingFeeXOF
                    })
                }).then(r => r.json()).then(details => {
                    if (details.success) {
                        showShippingSuccess();
                    } else {
                        Swal.fire({ title: 'Erreur', text: details.error || 'Paiement non traité', icon: 'error', confirmButtonColor: '#b78d65' });
                    }
                }).catch(() => {
                    Swal.fire({ title: 'Erreur', text: 'Erreur lors du traitement', icon: 'error', confirmButtonColor: '#b78d65' });
                });
            },

            onError: function(err) {
                console.error('Erreur PayPal:', err);
                Swal.fire({ title: 'Erreur', text: 'Erreur PayPal. Réessayez.', icon: 'error', confirmButtonColor: '#b78d65' });
            },

            onCancel: function() {
                Swal.fire({ title: 'Annulé', text: 'Paiement annulé.', icon: 'info', confirmButtonColor: '#b78d65' });
            }
        }).render('#paypal-button-container');
    }

    // ─── ELONGOPAY WINDOW ─────────────────────────────────────────────────────

    let shippingElongoWindow = null;

    function openShippingElongoPayWindow() {
        const amount = CURRENCY_CONFIG.shippingFeeXOF;
        const origin = encodeURIComponent(window.location.origin);
        // On passe le commande_id en paramètre pour le retrouver au capture
        const ref    = 'SHIP-' + COMMANDE_ID + '-' + Date.now();
        const url    = `${ELONGOPAY_BASE}/payment?amount=${amount}&origin=${origin}&ref=${ref}`;

        const w    = 500, h = 680;
        const left = (screen.width  / 2) - (w / 2);
        const top  = (screen.height / 2) - (h / 2);

        shippingElongoWindow = window.open(
            url,
            'elongopay_shipping',
            `width=${w},height=${h},left=${left},top=${top},resizable=no,scrollbars=yes,toolbar=no,menubar=no,location=no`
        );

        window.addEventListener('message', handleShippingElongoMessage);
    }

    function handleShippingElongoMessage(event) {
        if (!event.data || event.data.type !== 'ELONGOPAY_SUCCESS') return;

        window.removeEventListener('message', handleShippingElongoMessage);

        if (shippingElongoWindow && !shippingElongoWindow.closed) {
            shippingElongoWindow.close();
        }

        const { transaction_id, amount_xof } = event.data;

        Swal.fire({
            title: 'Traitement en cours...',
            text: 'Enregistrement du paiement',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch("{{ route('shipping.elongopay.capture') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                transaction_id: transaction_id,
                amount_xof:     amount_xof,
                commande_id:    COMMANDE_ID,
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showShippingSuccess();
            } else {
                Swal.fire({
                    title: 'Erreur',
                    text: data.error || 'Erreur lors de l\'enregistrement',
                    icon: 'error',
                    confirmButtonColor: '#b78d65'
                });
            }
        })
        .catch(() => {
            Swal.fire({ title: 'Erreur', text: 'Erreur réseau', icon: 'error', confirmButtonColor: '#b78d65' });
        });
    }

    // ─── SUCCÈS COMMUN ────────────────────────────────────────────────────────

    function showShippingSuccess() {
        Swal.fire({
            title: 'Paiement réussi !',
            html: `
                <div style="text-align:center;">
                    <i class="ri-checkbox-circle-fill" style="font-size:4rem; color:#4CAF50;"></i>
                    <p style="margin-top:1rem;">
                        Vos frais de livraison ont été payés avec succès.
                    </p>
                </div>
            `,
            icon: 'success',
            confirmButtonText: 'Voir ma commande',
            confirmButtonColor: '#b78d65',
            allowOutsideClick: false
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('shipping.payment.success') }}";
            }
        });
    }

    // ─── INIT ─────────────────────────────────────────────────────────────────

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof CurrencyHelper !== 'undefined') {
            CurrencyHelper.load().then(() => {
                updatePriceDisplay();
                renderPayPalButton();
            });
        } else {
            updatePriceDisplay();
            renderPayPalButton();
        }
    });
</script>
@endsection
