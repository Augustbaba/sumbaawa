@extends('back.layouts.master')

@section('title', 'Recharger un portefeuille')

@section('content')
<div class="content">

    {{-- Breadcrumb --}}
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-globe2 small me-2"></i>Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active">Recharger un portefeuille</li>
            </ol>
        </nav>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            {{-- Alertes --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Carte solde de l'admin --}}
            <div class="card mb-4 border-0 text-white"
                 style="background: linear-gradient(135deg, #1e3a8a, #172554); border-radius: 14px;">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <div style="font-size: .8rem; opacity: .8; text-transform: uppercase; letter-spacing: 1px;">
                            Opération
                        </div>
                        <div style="font-size: 1.4rem; font-weight: 700; margin-top: 4px;">
                            Recharge Manuelle
                        </div>
                        <div style="font-size: .82rem; opacity: .7; margin-top: 2px;">
                            Créditer le solde d'un utilisateur
                        </div>
                    </div>
                    <i class="bi bi-safe2" style="font-size: 3rem; opacity: .25;"></i>
                </div>
            </div>

            {{-- Formulaire --}}
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>
                        Recharger le portefeuille
                    </h5>

                    <form action="{{ route('admin.wallet.recharge.store') }}"
                          method="POST"
                          id="recharge-form">
                        @csrf

                        {{-- Champ email avec recherche auto --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                Email de l'utilisateur <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="utilisateur@exemple.com"
                                    value="{{ old('email') }}"
                                    autocomplete="off"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Zone d'affichage du user trouvé --}}
                            <div id="user-preview" class="mt-2" style="display: none;"></div>
                            <div id="user-not-found"
                                 class="mt-2 text-danger small"
                                 style="display: none;">
                                <i class="bi bi-x-circle me-1"></i>
                                Aucun utilisateur trouvé avec cet email.
                            </div>
                        </div>

                        {{-- Champ montant --}}
                        <div class="mb-4">
                            <label for="amount" class="form-label fw-semibold">
                                Montant à créditer (XOF) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-currency-exchange"></i>
                                </span>
                                <input
                                    type="number"
                                    id="amount"
                                    name="amount"
                                    class="form-control form-control-lg @error('amount') is-invalid @enderror"
                                    placeholder="Ex : 10000"
                                    value="{{ old('amount') }}"
                                    min="1"
                                    step="1"
                                    required
                                >
                                <span class="input-group-text fw-bold">XOF</span>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text" id="amount-preview"></div>
                        </div>

                        {{-- Note info --}}
                        {{-- <div class="alert alert-light border mb-4" style="font-size: .82rem;">
                            <i class="bi bi-info-circle text-primary me-1"></i>
                            Le montant sera crédité <strong>en XOF</strong> directement sur le
                            solde de l'utilisateur. La transaction sera enregistrée avec votre
                            identifiant administrateur.
                        </div> --}}

                        {{-- Bouton submit --}}
                        <div class="d-grid">
                            <button
                                type="submit"
                                class="btn btn-primary btn-lg"
                                id="submit-btn"
                                disabled
                            >
                                <i class="bi bi-wallet2 me-2"></i>
                                Valider la recharge
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ── État global ────────────────────────────────────────────────────────────
    let userFound  = false;
    let searchTimer = null;

    const emailInput     = document.getElementById('email');
    const amountInput    = document.getElementById('amount');
    const userPreview    = document.getElementById('user-preview');
    const userNotFound   = document.getElementById('user-not-found');
    const amountPreview  = document.getElementById('amount-preview');
    const submitBtn      = document.getElementById('submit-btn');

    // ── Recherche utilisateur (debounce 400ms) ─────────────────────────────────

    emailInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        const email = this.value.trim();

        // Reset
        userPreview.style.display  = 'none';
        userNotFound.style.display = 'none';
        userFound = false;
        updateSubmitBtn();

        if (email.length < 3 || !email.includes('@')) return;

        searchTimer = setTimeout(() => searchUser(email), 400);
    });

    function searchUser(email) {
        fetch(`{{ route('admin.wallet.search-user') }}?email=${encodeURIComponent(email)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.found) {
                showUserPreview(data.user);
                userFound = true;
            } else {
                userPreview.style.display  = 'none';
                userNotFound.style.display = 'block';
                userFound = false;
            }
            updateSubmitBtn();
        })
        .catch(() => {
            userPreview.style.display  = 'none';
            userNotFound.style.display = 'none';
            userFound = false;
            updateSubmitBtn();
        });
    }

    function showUserPreview(user) {
        userNotFound.style.display = 'none';
        userPreview.style.display  = 'block';
        userPreview.innerHTML = `
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 border"
                 style="background: rgba(30,58,138,.04); border-color: rgba(30,58,138,.2) !important;">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                     style="width:44px; height:44px; background: linear-gradient(135deg,#1e3a8a,#172554); flex-shrink:0; font-size:1.1rem;">
                    ${user.initials}
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-semibold text-truncate" style="color:#1e3a8a;">${user.name}</div>
                    <div class="text-muted small text-truncate">${user.email}</div>
                </div>
                <div class="text-end flex-shrink-0">
                    <div class="text-muted small">Solde actuel</div>
                    <div class="fw-bold" style="color:#1e3a8a;">${user.solde}</div>
                </div>
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
            </div>
        `;
    }

    // ── Prévisualisation montant ───────────────────────────────────────────────

    amountInput.addEventListener('input', function () {
        const val = parseInt(this.value, 10);

        if (val && val > 0) {
            const formatted = new Intl.NumberFormat('fr-FR').format(val);
            amountPreview.innerHTML = `
                <span class="text-muted">
                    ≈ <strong>${formatted} XOF</strong> seront crédités sur le portefeuille
                </span>
            `;
        } else {
            amountPreview.innerHTML = '';
        }

        updateSubmitBtn();
    });

    // ── Activer/désactiver le bouton submit ───────────────────────────────────

    function updateSubmitBtn() {
        const amount  = parseInt(amountInput.value, 10);
        const isValid = userFound && amount > 0;
        submitBtn.disabled = !isValid;
    }

    // ── Confirmation avant submit ──────────────────────────────────────────────

    document.getElementById('recharge-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const amount    = parseInt(amountInput.value, 10);
        const email     = emailInput.value.trim();
        const formatted = new Intl.NumberFormat('fr-FR').format(amount);

        if (!confirm(
            `Confirmer la recharge ?\n\n` +
            `Utilisateur : ${email}\n` +
            `Montant : ${formatted} XOF\n\n` +
            `Cette action est irréversible.`
        )) return;

        // Désactiver le bouton pendant l'envoi
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Traitement…';

        this.submit();
    });
</script>

<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
        border: none;
    }
</style>
@endsection
