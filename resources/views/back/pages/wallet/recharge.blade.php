@extends('back.layouts.master')

@section('title', 'Recharger mon solde')

@section('content')
@php
    $currentCurrency = FrontHelper::current_currency();
    $usdRate = \App\Models\Currency::where('code', 'USD')->value('exchange_rate') ?? 600;
@endphp

<div class="content">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-globe2 small me-2"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('wallet.index') }}">Mon Portefeuille</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Recharger</li>
            </ol>
        </nav>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            {{-- Solde actuel --}}
            <div class="card mb-4 border-0 text-white"
                 style="background: linear-gradient(135deg,#1e3a8a,#172554); border-radius:12px;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size:.85rem; opacity:.8; text-transform:uppercase; letter-spacing:1px;">
                            Solde actuel
                        </div>
                        <div style="font-size:2rem; font-weight:700;">
                            {{ FrontHelper::format_currency(auth()->user()->solde) }}
                        </div>

                    </div>
                    <i class="bi bi-wallet2" style="font-size:3.5rem; opacity:.25;"></i>
                </div>
            </div>

            {{-- Formulaire --}}
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>Recharger via PayPal
                    </h5>

                    {{-- Montants rapides --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Montant rapide</label>
                        <div class="d-flex flex-wrap gap-2" id="presets">
                            @foreach ([5000, 10000, 25000, 50000, 100000] as $preset)
                                <button type="button"
                                        class="btn btn-outline-primary btn-sm preset-btn"
                                        data-value="{{ $preset }}">
                                    {{ FrontHelper::format_currency($preset) }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Saisie libre --}}
                    <div class="mb-2">
                        <label class="form-label fw-semibold" for="recharge-amount">
                            Ou saisir un montant
                            <span class="text-muted fw-normal">(en {{ $currentCurrency->symbol }})</span>
                        </label>
                        <div class="input-group">
                            <input
                                type="number"
                                id="recharge-amount"
                                class="form-control form-control-lg"
                                placeholder="Ex : 10 000"
                                min="500"
                                step="100"
                            >
                            <span class="input-group-text fw-bold">{{ $currentCurrency->symbol }}</span>
                        </div>
                    </div>

                    {{-- Aperçu USD --}}
                    <div class="text-muted small mb-4" id="usd-preview" style="min-height:1.3rem;"></div>

                    {{-- Bouton PayPal (rendu dynamiquement) --}}
                    <div id="paypal-wallet-container"></div>

                    {{-- Message si montant non saisi --}}
                    <div id="paypal-placeholder" class="text-center text-muted py-3">
                        <i class="bi bi-arrow-up-circle" style="font-size:2rem;"></i>
                        <p class="mt-2 mb-0" style="font-size:.9rem;">
                            Saisissez un montant pour afficher le bouton de paiement
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
@php
    $mode     = config('services.paypal.mode');
    $clientId = config("services.paypal.{$mode}.client_id");
@endphp

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://www.paypal.com/sdk/js?client-id={{ $clientId }}&currency=USD&intent=capture"></script>

<script>
const USD_RATE  = {{ $usdRate }};
const CURR_CODE = '{{ $currentCurrency->code }}';
const CURR_RATE = {{ $currentCurrency->exchange_rate }};

/* Devise préférée → XOF */
function toXOF(amount) {
    if (CURR_CODE === 'XOF') return amount;
    return amount * CURR_RATE;
}

/* XOF → USD */
function xofToUSD(xof) {
    return Math.round((xof / USD_RATE) * 100) / 100;
}

/* Mettre à jour l'aperçu USD */
function updatePreview(displayAmount) {
    const xof    = toXOF(displayAmount);
    const usd    = xofToUSD(xof);
    const xofFmt = new Intl.NumberFormat('fr-FR').format(Math.round(xof));
    document.getElementById('usd-preview').textContent =
        `≈ ${xofFmt} XOF  →  $${usd.toFixed(2)} USD facturés à PayPal`;
}

/* ── Presets ─────────────────────────────────────────────────────────────── */
document.querySelectorAll('.preset-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const xof = parseFloat(this.dataset.value);
        const displayAmount = CURR_CODE === 'XOF'
            ? xof
            : parseFloat((xof / CURR_RATE).toFixed(2));

        document.getElementById('recharge-amount').value = displayAmount;
        // updatePreview(displayAmount);
        renderPayPalButton(toXOF(displayAmount));
    });
});

/* ── Saisie libre ────────────────────────────────────────────────────────── */
let debounceTimer = null;

document.getElementById('recharge-amount').addEventListener('input', function () {
    const val = parseFloat(this.value);
    document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));

    if (!val || val <= 0) {
        document.getElementById('usd-preview').textContent = '';
        document.getElementById('paypal-wallet-container').innerHTML = '';
        document.getElementById('paypal-placeholder').style.display = 'block';
        return;
    }

    // updatePreview(val);

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => renderPayPalButton(toXOF(val)), 600);
});

/* ── Rendu bouton PayPal ─────────────────────────────────────────────────── */
function renderPayPalButton(amountXOF) {
    const container = document.getElementById('paypal-wallet-container');
    container.innerHTML = '';
    document.getElementById('paypal-placeholder').style.display = 'none';

    if (!amountXOF || amountXOF < 100) {
        document.getElementById('paypal-placeholder').style.display = 'block';
        return;
    }

    const amountUSD = xofToUSD(amountXOF);
    if (amountUSD < 0.01) return;

    paypal.Buttons({
        style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'pay' },

        createOrder: function () {
            return fetch("{{ route('wallet.paypal.create-order') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ amount_xof: amountXOF })
            })
            .then(r => r.json())
            .then(d => {
                if (d.error) throw new Error(d.error);
                return d.orderID;
            })
            .catch(err => {
                Swal.fire({ title: 'Erreur', text: err.message, icon: 'error', confirmButtonColor: '#0d6efd' });
            });
        },

        onApprove: function (data) {
            Swal.fire({
                title: 'Traitement en cours…',
                text: 'Crédit de votre solde en cours, veuillez patienter.',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            return fetch("{{ route('wallet.paypal.capture-order') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orderID: data.orderID })
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    const amountInPreferredCurrency = CURR_CODE === 'XOF'
                        ? Math.round(d.amount_xof)
                        : parseFloat((d.amount_xof / CURR_RATE).toFixed(2));

                    const decimals = CURR_CODE === 'XOF' ? 0 : 2;
                    const formattedAmount = new Intl.NumberFormat('fr-FR', {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals
                    }).format(amountInPreferredCurrency);

                    Swal.fire({
                        title: 'Recharge réussie !',
                        html: `
                            <div class="text-center">
                                <i class="bi bi-check-circle-fill text-success" style="font-size:3.5rem;"></i>
                                <p class="mt-3">Votre solde a été crédité de</p>
                                <p style="font-size:1.4rem; font-weight:700; color:#198754;">
                                    ${formattedAmount} {{ $currentCurrency->symbol }}
                                </p>
                                
                            </div>
                        `,
                        showConfirmButton: true,
                        confirmButtonText: 'Voir mon historique',
                        confirmButtonColor: '#0d6efd',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = "{{ route('wallet.index') }}";
                    });
                } else {
                    Swal.fire({ title: 'Erreur', text: d.error || 'Paiement non abouti.', icon: 'error', confirmButtonColor: '#0d6efd' });
                }
            })
            .catch(() => {
                Swal.fire({ title: 'Erreur réseau', text: 'Veuillez réessayer.', icon: 'error', confirmButtonColor: '#0d6efd' });
            });
        },

        onError: function (err) {
            console.error('PayPal error', err);
            Swal.fire({ title: 'Erreur PayPal', text: 'Une erreur est survenue, veuillez réessayer.', icon: 'error', confirmButtonColor: '#0d6efd' });
        },

        onCancel: function () {
            Swal.fire({ title: 'Annulé', text: 'Vous avez annulé la recharge.', icon: 'info', confirmButtonColor: '#0d6efd' });
        }

    }).render('#paypal-wallet-container');
}
</script>
@endsection
