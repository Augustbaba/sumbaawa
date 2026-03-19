@extends('front.layouts.master')

@section('title', 'Nihao Travel – Voyage d\'affaires vers la Chine')

@section('styles')
<style>
    :root {
        --primary: #b78d65;
        --primary-dark: #9a7150;
        --text-dark: #1a1a1a;
        --text-muted: #6b7280;
        --bg-light: #f9fafb;
    }

    .nihao-hero {
        background: linear-gradient(135deg, #fff, #fdf6ee);
        padding: 60px 0;
    }

    .nihao-hero h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .nihao-hero p {
        font-size: 1.05rem;
        color: var(--text-muted);
        max-width: 700px;
        margin: 15px auto 0;
    }

    .nihao-section {
        padding: 60px 0;
    }

    .nihao-card {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        height: 100%;
    }

    .nihao-card h5 {
        font-weight: 600;
        margin-bottom: 10px;
    }

    .nihao-card p {
        font-size: 0.95rem;
        color: var(--text-muted);
    }

    .cta-box {
        background: var(--bg-light);
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        border: 1px solid #eee;
    }

    .cta-box h3 {
        font-weight: 700;
        margin-bottom: 10px;
    }

    .cta-box p {
        color: var(--text-muted);
        margin-bottom: 25px;
    }

    .price-box {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 20px;
    }

    .nihao-form {
        max-width: 600px;
        margin: 0 auto;
        text-align: left;
    }

    .nihao-form .form-control {
        margin-bottom: 15px;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
    }

    .paypal-container {
        margin-top: 30px;
        text-align: center;
    }

    .form-note {
        background-color: #fff9e6;
        border-left: 4px solid #ffc107;
        padding: 12px 15px;
        margin: 20px 0;
        border-radius: 4px;
        font-size: 0.9rem;
        color: var(--text-muted);
        text-align: left;
    }

    .payment-summary {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin: 25px 0;
        border: 1px solid #eee;
        text-align: left;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed #ddd;
    }

    .summary-row:last-child {
        border-bottom: none;
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--primary);
    }

    .alert {
        padding: 15px;
        border-radius: 6px;
        margin: 20px 0;
    }

    .alert-success {
        background-color: #d1e7dd;
        border: 1px solid #badbcc;
        color: #0f5132;
    }

    .alert-error {
        background-color: #f8d7da;
        border: 1px solid #f5c2c7;
        color: #842029;
    }
</style>
@endsection

@section('content')

{{-- HERO --}}
<section class="nihao-hero text-center">
    <div class="container">
        <h1>Voyagez en Chine pour la Foire de Canton</h1>
        <p>
            Avec <strong>Nihao Travel</strong>, filiale du <strong>Groupe Sumbaawa</strong>,
            bénéficiez d'un accompagnement complet pour votre voyage d'affaires vers la Chine :
            visa, hébergement, assistance et orientation stratégique.
        </p>
    </div>
</section>

{{-- FOIRE DE CANTON --}}
<section class="nihao-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="nihao-card">
                    <h5>La Foire de Canton</h5>
                    <p>
                        La Foire d'Import-Export de Canton est le plus grand salon commercial au monde.
                        Elle rassemble des milliers de fabricants et d'acheteurs internationaux
                        dans les domaines de l'électronique, de l'automobile, du mobilier,
                        de l'industrie et bien plus encore. Un événement incontournable pour
                        développer votre réseau et trouver des fournisseurs de qualité.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="nihao-card">
                    <h5>Opportunités business</h5>
                    <p>
                        Trouvez des fournisseurs fiables, négociez directement avec les usines,
                        découvrez les dernières innovations et développez des partenariats durables
                        pour votre activité. C'est l'occasion idéale pour élargir votre catalogue
                        produits et améliorer votre compétitivité sur le marché.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- POURQUOI NIHAO --}}
<section class="nihao-section bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="nihao-card">
                    <h5>Visa & formalités</h5>
                    <p>Assistance complète pour l'obtention du visa et toutes les démarches administratives nécessaires pour votre entrée en Chine.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="nihao-card">
                    <h5>Hébergement & logistique</h5>
                    <p>Réservation d'hôtels adaptés à vos besoins et budget, transferts aéroport, et organisation complète de votre séjour.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="nihao-card">
                    <h5>Accompagnement terrain</h5>
                    <p>Orientation sur place, conseils stratégiques, et assistance durant toute la durée de la foire pour maximiser votre expérience.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA + PAIEMENT --}}
<section class="nihao-section">
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="cta-box">
            <h3>Intéressé par la Foire de Canton ?</h3>
            <p>
                Inscrivez-vous dès maintenant et laissez Nihao Travel s'occuper de tout.
                Notre forfait d'accompagnement complet vous garantit un voyage sans stress.
            </p>

            <div class="price-box" id="price-display">
                {{ FrontHelper::format_currency(3500000) }}
            </div>

            {{-- FORMULAIRE --}}
            <form id="nihaoRegistrationForm" class="nihao-form">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Prénom *" id="firstName" name="first_name" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Nom *" id="lastName" name="last_name" required>
                    </div>
                </div>
                <input type="email" class="form-control" placeholder="Adresse email *" id="email" name="email" required>
                <input type="tel" class="form-control" placeholder="Téléphone *" id="phone" name="phone" required>
                <input type="text" class="form-control" placeholder="Entreprise (optionnel)" id="company" name="company">

                <div class="form-group">
                    <label for="canton_edition" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark);">Édition de la Foire de Canton *</label>
                    <select id="canton_edition" name="canton_edition" class="form-control" required>
                        <option value="">Sélectionnez une édition</option>
                        @php
                            // Obtenir la date actuelle
                            $now = now();
                            $currentYear = $now->year;
                            $currentMonth = $now->month;

                            // Dates des sessions (approximatives)
                            // Session d'Avril : généralement du 15 avril au 5 mai
                            $avrilStart = \Carbon\Carbon::create($currentYear, 4, 15);
                            // Session d'Octobre : généralement du 15 octobre au 4 novembre
                            $octobreStart = \Carbon\Carbon::create($currentYear, 10, 15);

                            // Déterminer quelles sessions afficher
                            $showAvrilThisYear = false;
                            $showOctobreThisYear = false;
                            $showAvrilNextYear = false;
                            $showOctobreNextYear = false;

                            // Si on est dans les 7 jours avant Avril, ou après Octobre de l'année précédente
                            if ($now->lt($avrilStart->copy()->subWeek())) {
                                // Avant la session d'Avril (plus d'une semaine avant)
                                $showAvrilThisYear = true;
                                $showOctobreThisYear = true;
                            }
                            elseif ($now->between($avrilStart->copy()->subWeek(), $avrilStart->copy()->addDays(15))) {
                                // Dans la période d'Avril ou juste après
                                $showOctobreThisYear = true;
                            }
                            elseif ($now->between($avrilStart->copy()->addDays(16), $octobreStart->copy()->subWeek())) {
                                // Entre Avril et Octobre (plus d'une semaine après Avril)
                                $showOctobreThisYear = true;
                            }
                            elseif ($now->gt($octobreStart->copy()->subWeek())) {
                                // Proche ou après Octobre
                                if ($now->lt($octobreStart->copy()->addDays(15))) {
                                    // Pendant la session d'Octobre ou juste après
                                    $showAvrilNextYear = true;
                                } else {
                                    // Après Octobre
                                    $showAvrilNextYear = true;
                                    $showOctobreNextYear = true;
                                }
                            }

                            // Version alternative plus simple
                            // Si on est avant le 15 Avril, on montre Avril de cette année
                            // Si on est après le 15 Avril mais avant le 15 Octobre, on montre Octobre de cette année
                            // Si on est après le 15 Octobre, on montre Avril de l'année prochaine

                            // Réinitialiser pour logique simplifiée
                            $showAvrilThisYear = false;
                            $showOctobreThisYear = false;
                            $showAvrilNextYear = false;
                            $showOctobreNextYear = false;

                            if ($currentMonth < 4 || ($currentMonth == 4 && $now->day < 15)) {
                                // Avant Avril
                                $showAvrilThisYear = true;
                                $showOctobreThisYear = true;
                            }
                            elseif ($currentMonth < 10 || ($currentMonth == 10 && $now->day < 15)) {
                                // Entre Avril et Octobre
                                $showOctobreThisYear = true;
                                $showAvrilNextYear = true;
                            }
                            else {
                                // Après Octobre
                                $showAvrilNextYear = true;
                                $showOctobreNextYear = true;
                            }

                        @endphp

                        {{-- Options dynamiques --}}
                        @if($showAvrilThisYear)
                            <option value="avril_{{ $currentYear }}">Avril {{ $currentYear }} (Phase 1, 2 & 3)</option>
                        @endif

                        @if($showOctobreThisYear)
                            <option value="octobre_{{ $currentYear }}">Octobre {{ $currentYear }} (Phase 1, 2 & 3)</option>
                        @endif

                        @if($showAvrilNextYear)
                            <option value="avril_{{ $currentYear + 1 }}">Avril {{ $currentYear + 1 }} (Phase 1, 2 & 3)</option>
                        @endif

                        @if($showOctobreNextYear)
                            <option value="octobre_{{ $currentYear + 2 }}">Octobre {{ $currentYear + 1 }} (Phase 1, 2 & 3)</option>
                        @endif

                        {{-- Fallback au cas où aucune option ne serait générée --}}
                        @if(!$showAvrilThisYear && !$showOctobreThisYear && !$showAvrilNextYear && !$showOctobreNextYear)
                            <option value="avril_{{ $currentYear }}">Avril {{ $currentYear }} (Phase 1, 2 & 3)</option>
                            <option value="octobre_{{ $currentYear }}">Octobre {{ $currentYear }} (Phase 1, 2 & 3)</option>
                        @endif
                    </select>
                </div>

                <textarea class="form-control" placeholder="Informations complémentaires (dates préférées, besoins spécifiques...)" id="additional_info" name="additional_info" rows="3"></textarea>

                <div class="form-note">
                    <i class="ri-information-line"></i>
                    Après soumission du formulaire, vous serez redirigé vers le paiement sécurisé PayPal.
                    Une fois le paiement confirmé, notre équipe vous contactera dans les 24 heures.
                </div>

                <!-- Résumé du paiement -->
                <div class="payment-summary">
                    <h5 style="margin-bottom: 15px; color: var(--text-dark);">Récapitulatif du paiement</h5>

                    <div class="summary-row">
                        <span>Forfait d'accompagnement complet :</span>
                        <span id="package-price" data-xof="3500000">{{ FrontHelper::format_currency(3500000) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Total à payer :</span>
                        <span id="total-price" data-total-xof="3500000">{{ FrontHelper::format_currency(3500000) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Équivalent en USD :</span>
                        <span id="total-usd"></span>
                    </div>
                </div>
            </form>

            {{-- ZONE PAIEMENT : PayPal + ElongoPay --}}
            <div class="payment-methods-selector" style="display:flex; gap:1rem; margin:20px 0;">
                <div class="payment-opt selected" id="opt-paypal"
                    onclick="selectNihaoPayment('paypal')"
                    style="flex:1; text-align:center; padding:1rem; border:2px solid #b78d65;
                            border-radius:8px; cursor:pointer; background:rgba(183,141,101,0.05);">
                    <i class="ri-paypal-line" style="font-size:2rem; color:#b78d65;"></i>
                    <div style="font-weight:600;">PayPal</div>
                    <small style="color:#777;">Carte bancaire</small>
                </div>
                <div class="payment-opt" id="opt-elongopay"
                    onclick="selectNihaoPayment('elongopay')"
                    style="flex:1; text-align:center; padding:1rem; border:2px solid #ddd;
                            border-radius:8px; cursor:pointer;">
                    <i class="ri-wallet-3-line" style="font-size:2rem; color:#b78d65;"></i>
                    <div style="font-weight:600; color:#b78d65;">ElongoPay</div>
                    <small style="color:#777;">Portefeuille digital</small>
                </div>
            </div>

            {{-- PayPal --}}
            <div id="paypal-button-container" class="paypal-container"></div>

            {{-- ElongoPay --}}
            <div id="elongopay-nihao-section" style="display:none; margin-top:1rem;">
                <button onclick="openNihaoElongoPayWindow()"
                    style="width:100%; padding:1rem;
                        background:linear-gradient(135deg,#007AFF,#8A2BE2);
                        color:white; border:none; border-radius:8px;
                        font-size:1rem; font-weight:700; cursor:pointer;">
                    <i class="ri-wallet-3-line"></i> Payer avec ElongoPay
                </button>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
@php
    $mode          = config('services.paypal.mode');
    $clientId      = config("services.paypal.{$mode}.client_id");
    $nihaoAmountXOF = 3500000;
    $usdRate       = \App\Models\Currency::where('code', 'USD')->value('exchange_rate') ?? 600;
    $amountUSD     = $nihaoAmountXOF / $usdRate;
@endphp

<script src="https://www.paypal.com/sdk/js?client-id={{ $clientId }}&currency=USD&intent=capture"></script>

<script>
    const NIHAO_AMOUNT_XOF  = {{ $nihaoAmountXOF }};
    const ELONGOPAY_BASE    = '{{ config("services.elongopay.base_url", "http://localhost:8001") }}';

    const CURRENCY_CONFIG = {
        currentCode:   '{{ FrontHelper::current_currency()->code }}',
        currentSymbol: '{{ FrontHelper::current_currency()->symbol }}',
        usdRate:       {{ $usdRate }},
        convertToUSD:  (xof) => parseFloat((xof / {{ $usdRate }}).toFixed(2)),
    };

    const amountUSD = CURRENCY_CONFIG.convertToUSD(NIHAO_AMOUNT_XOF);

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('total-usd').textContent = amountUSD.toFixed(2) + ' USD';
    });

    // ─── FORMULAIRE ───────────────────────────────────────────────────────────

    function getFormData() {
        const form     = document.getElementById('nihaoRegistrationForm');
        const formData = new FormData(form);
        const data     = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        data.total_amount_xof = NIHAO_AMOUNT_XOF;
        data.total_amount_usd = amountUSD;
        return data;
    }

    function validateForm() {
        const required = ['first_name', 'last_name', 'email', 'phone', 'canton_edition'];
        let valid = true;
        required.forEach(name => {
            const field = document.querySelector(`[name="${name}"]`);
            if (!field.value.trim()) {
                valid = false;
                field.style.borderColor = '#dc3545';
            } else {
                field.style.borderColor = '#ddd';
            }
        });
        if (!valid) alert('Veuillez remplir tous les champs obligatoires (*)');
        return valid;
    }

    // ─── SÉLECTION MODE PAIEMENT ──────────────────────────────────────────────

    let selectedNihaoPayment = 'paypal';

    function selectNihaoPayment(method) {
        selectedNihaoPayment = method;

        document.querySelectorAll('.payment-opt').forEach(el => {
            el.style.borderColor      = '#ddd';
            el.style.backgroundColor  = 'transparent';
        });

        const active = document.getElementById('opt-' + method);
        active.style.borderColor     = '#b78d65';
        active.style.backgroundColor = 'rgba(183,141,101,0.05)';

        if (method === 'paypal') {
            document.getElementById('paypal-button-container').style.display   = 'block';
            document.getElementById('elongopay-nihao-section').style.display   = 'none';
        } else {
            document.getElementById('paypal-button-container').style.display   = 'none';
            document.getElementById('elongopay-nihao-section').style.display   = 'block';
        }
    }

    // ─── PAYPAL ───────────────────────────────────────────────────────────────

    paypal.Buttons({
        style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'paypal' },

        createOrder: function() {
            if (!validateForm()) return false;

            return fetch("{{ route('nihao.travel.create-order') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    amount:    amountUSD,
                    amountXOF: NIHAO_AMOUNT_XOF,
                    formData:  getFormData()
                })
            }).then(r => r.json()).then(data => {
                if (data.error) throw new Error(data.error);
                return data.orderID;
            }).catch(err => {
                console.error(err);
                alert('Impossible de créer la commande. Veuillez réessayer.');
            });
        },

        onApprove: function(data) {
            return fetch("{{ route('nihao.travel.capture-order') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orderID: data.orderID, formData: getFormData() })
            }).then(r => r.json()).then(details => {
                if (details.success) {
                    window.location.href = "{{ route('nihao.travel.confirmation') }}?order_id="
                        + details.travel_id + "&code=" + details.travel_code;
                } else {
                    alert('Erreur lors du paiement: ' + (details.error || 'Erreur inconnue'));
                }
            }).catch(() => alert('Une erreur est survenue lors du traitement du paiement.'));
        },

        onError:  (err) => { console.error(err); alert('Erreur PayPal. Veuillez réessayer.'); },
        onCancel: ()    => { alert('Paiement annulé.'); }
    }).render('#paypal-button-container');

    // ─── ELONGOPAY WINDOW ─────────────────────────────────────────────────────

    let nihaoElongoWindow = null;

    function openNihaoElongoPayWindow() {
        if (!validateForm()) return;

        const origin = encodeURIComponent(window.location.origin);
        const ref    = 'NIHAO-' + Date.now();
        const url    = `${ELONGOPAY_BASE}/payment?amount=${NIHAO_AMOUNT_XOF}&origin=${origin}&ref=${ref}`;

        const w    = 500, h = 680;
        const left = (screen.width  / 2) - (w / 2);
        const top  = (screen.height / 2) - (h / 2);

        nihaoElongoWindow = window.open(
            url,
            'elongopay_nihao',
            `width=${w},height=${h},left=${left},top=${top},resizable=no,scrollbars=yes,toolbar=no,menubar=no,location=no`
        );

        window.addEventListener('message', handleNihaoElongoMessage);
    }

    function handleNihaoElongoMessage(event) {
        if (!event.data || event.data.type !== 'ELONGOPAY_SUCCESS') return;

        window.removeEventListener('message', handleNihaoElongoMessage);

        if (nihaoElongoWindow && !nihaoElongoWindow.closed) {
            nihaoElongoWindow.close();
        }

        const { transaction_id, amount_xof } = event.data;

        // Indicateur de chargement
        const btn = document.querySelector('#elongopay-nihao-section button');
        btn.disabled    = true;
        btn.textContent = 'Traitement en cours...';

        fetch("{{ route('nihao.travel.elongopay.capture') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                transaction_id: transaction_id,
                amount_xof:     amount_xof,
                form_data:      getFormData(),
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.href = "{{ route('nihao.travel.confirmation') }}?order_id="
                    + data.travel_id + "&code=" + data.travel_code;
            } else {
                alert('Erreur: ' + (data.error || 'Erreur inconnue'));
                btn.disabled    = false;
                btn.innerHTML   = '<i class="ri-wallet-3-line"></i> Payer avec ElongoPay';
            }
        })
        .catch(() => {
            alert('Erreur réseau. Veuillez réessayer.');
            btn.disabled  = false;
            btn.innerHTML = '<i class="ri-wallet-3-line"></i> Payer avec ElongoPay';
        });
    }
</script>
@endsection
