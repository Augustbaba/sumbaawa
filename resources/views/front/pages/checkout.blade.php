@extends('front.layouts.master')
@section('title', 'Récapitulatif de commande')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Styles identiques - pas de changement */
        :root {
            --primary-dark: #1a1a1a;
            --primary-light: #f8f8f8;
            --accent-gold: #b78d65;
            --accent-gold-dark: #9a7150;
            --accent-gold-light: #e8d5c0;
            --secondary-dark: #2a2a2a;
            --text-muted: #777;
        }

        .order-summary-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .order-header {
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-dark));
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .order-header h2 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .order-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            padding: 1.5rem;
            background-color: var(--primary-light);
            border-bottom: 1px solid #eee;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-gold);
            margin: 0.5rem 0;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .products-section {
            padding: 0;
        }

        .product-accordion {
            border-bottom: 1px solid #eee;
        }

        .product-accordion:last-child {
            border-bottom: none;
        }

        .product-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .product-summary:hover {
            background-color: rgba(183, 141, 101, 0.05);
        }

        .product-main-info {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 1rem;
            border: 1px solid rgba(0,0,0,0.1);
        }

        .product-name {
            font-weight: 500;
            margin: 0;
            color: var(--primary-dark);
        }

        .product-price {
            font-weight: 600;
            color: var(--accent-gold);
            margin-left: 1rem;
            white-space: nowrap;
        }

        .product-details {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            background-color: rgba(183, 141, 101, 0.03);
        }

        .product-details.show {
            padding: 1rem 1.5rem 1.5rem;
            max-height: 200px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .detail-label {
            color: var(--text-muted);
        }

        .detail-value {
            font-weight: 500;
        }

        .order-totals {
            padding: 1.5rem;
            background-color: var(--primary-light);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px dashed #ddd;
        }

        .total-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .total-label {
            font-weight: 500;
        }

        .total-value {
            font-weight: 600;
        }

        .grand-total {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--accent-gold);
            font-size: 1.2rem;
        }

        .grand-total .total-label {
            color: var(--accent-gold);
        }

        .grand-total .total-value {
            color: var(--accent-gold);
            font-size: 1.4rem;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            padding: 1rem;
            background: var(--accent-gold);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
            text-align: center;
        }

        .checkout-btn:hover {
            background: var(--accent-gold-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(183, 141, 101, 0.4);
        }

        .checkout-btn:active {
            transform: translateY(-1px);
        }

        .accordion-icon {
            transition: transform 0.3s ease;
            margin-left: 1rem;
            color: var(--accent-gold);
        }

        .product-accordion.active .accordion-icon {
            transform: rotate(180deg);
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
            border-color: var(--accent-gold);
            background-color: rgba(183, 141, 101, 0.05);
        }

        .payment-method i {
            font-size: 2rem;
            color: var(--accent-gold);
            margin-bottom: 0.5rem;
        }

        .delivery-form {
            margin-top: 1.5rem;
            text-align: left;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--primary-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: var(--accent-gold);
            outline: none;
            box-shadow: 0 0 0 3px rgba(183, 141, 101, 0.2);
        }

        .delivery-options {
            display: flex;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .delivery-option {
            flex: 1;
            text-align: center;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .delivery-option.selected {
            border-color: var(--accent-gold);
            background-color: rgba(183, 141, 101, 0.05);
        }

        .delivery-option i {
            font-size: 2rem;
            color: var(--accent-gold);
            margin-bottom: 0.5rem;
        }

        .delivery-price {
            font-weight: 600;
            color: var(--accent-gold);
            margin-top: 0.5rem;
        }

        .paypal-button-container {
            margin: 2rem auto;
            text-align: center;
        }

        #paypal-button-container {
            max-width: 400px;
            margin: 0 auto;
        }

        .notice-box {
            background-color: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin: 1.5rem 0;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .notice-box i {
            color: #ffc107;
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .order-stats {
                grid-template-columns: 1fr 1fr;
            }

            .product-summary {
                flex-wrap: wrap;
            }

            .product-price {
                width: 100%;
                margin: 0.5rem 0 0 0;
                text-align: right;
            }

            .delivery-options,
            .payment-methods {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .order-stats {
                grid-template-columns: 1fr;
            }

            .product-main-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .product-image {
                margin-bottom: 0.5rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="order-summary-container">
    <div class="order-header">
        <h2>Votre Récapitulatif de Commande</h2>
    </div>

    <div class="order-stats">
        <div class="stat-card">
            <div class="stat-value">{{ count(session('cart', [])) }}</div>
            <div class="stat-label">Articles</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($totalWeight, 2) }} kg</div>
            <div class="stat-label">Poids Total</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"
                 data-total-xof="{{ $totalCart }}"
                 id="total-display">
                {{ FrontHelper::format_currency($totalCart) }}
            </div>
            <div class="stat-label">Total</div>
        </div>
    </div>

    <div class="products-section">
        @forelse (session('cart', []) as $item)
        <div class="product-accordion">
            <div class="product-summary" onclick="toggleProductDetails(this)">
                <div class="product-main-info">
                    <img src="{{ $item['image_main'] }}" alt="{{ $item['name'] }}" class="product-image">
                    <h3 class="product-name">{{ $item['name'] }}</h3>
                </div>
                <div class="product-price"
                     data-price="{{ $item['price'] * $item['quantity'] }}">
                    {{ FrontHelper::format_currency($item['price'] * $item['quantity']) }}
                </div>
                <i class="ri-arrow-down-s-line accordion-icon"></i>
            </div>

            <div class="product-details">
                <div class="detail-row">
                    <span class="detail-label">Couleur:</span>
                    <span class="detail-value">{{ $item['color'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Prix unitaire:</span>
                    <span class="detail-value" data-price="{{ $item['price'] }}">
                        {{ FrontHelper::format_currency($item['price']) }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Quantité:</span>
                    <span class="detail-value">{{ $item['quantity'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Poids unitaire:</span>
                    <span class="detail-value">{{ number_format($item['poids'], 2) }} kg</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Poids total:</span>
                    <span class="detail-value">{{ number_format($item['poids'] * $item['quantity'], 2) }} kg</span>
                </div>
            </div>
        </div>
        @empty
        <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
            <i class="ri-shopping-cart-line" style="font-size: 3rem; color: var(--accent-gold);"></i>
            <p>Votre panier est vide</p>
        </div>
        @endforelse
    </div>

    <div class="order-totals">
        <div class="total-row grand-total">
            <span class="total-label">Total à payer:</span>
            <span class="total-value"
                  data-total-xof="{{ $totalCart }}"
                  id="grand-total-display">
                {{ FrontHelper::format_currency($totalCart) }}
            </span>
        </div>

        <button class="checkout-btn" id="openPaymentModal">
            Procéder au paiement
        </button>
    </div>
</div>
@endsection

@section('scripts')
@php
    $mode = config('services.paypal.mode');
    $clientId = config("services.paypal.{$mode}.client_id");

    // Obtenir la devise courante et ses informations
    $currentCurrency = FrontHelper::current_currency();
@endphp
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/currency.js') }}"></script>
<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id={{ $clientId }}&currency=USD&intent=capture"></script>

<script>
    // Configuration de la devise et des taux de change
    const CURRENCY_CONFIG = {
        currentCode: '{{ $currentCurrency->code }}',
        currentSymbol: '{{ $currentCurrency->symbol }}',
        currentRate: {{ $currentCurrency->exchange_rate }},
        totalInXOF: {{ $totalCart }}, // Montant total en XOF (devise de base)

        // Taux de change de référence (tous basés sur XOF)
        rates: {
            'XOF': 1,
            'EUR': {{ \App\Models\Currency::where('code', 'EUR')->value('exchange_rate') ?? 655.957 }},
            'USD': {{ \App\Models\Currency::where('code', 'USD')->value('exchange_rate') ?? 600 }},
            'GBP': {{ \App\Models\Currency::where('code', 'GBP')->value('exchange_rate') ?? 750 }}
        }
    };

    /**
     * Convertir un montant d'une devise vers une autre
     * @param {number} amount - Montant à convertir
     * @param {string} fromCurrency - Devise source
     * @param {string} toCurrency - Devise cible
     * @returns {number}
     */
    function convertCurrency(amount, fromCurrency, toCurrency) {
        if (fromCurrency === toCurrency) {
            return amount;
        }

        // Étape 1: Convertir vers XOF (devise de base)
        let amountInXOF = amount;
        if (fromCurrency !== 'XOF') {
            // Pour convertir vers XOF: montant * taux de la devise source
            amountInXOF = amount * CURRENCY_CONFIG.rates[fromCurrency];
        }

        // Étape 2: Convertir de XOF vers la devise cible
        if (toCurrency === 'XOF') {
            return amountInXOF;
        }

        // Pour convertir de XOF vers autre devise: montant / taux de la devise cible
        return amountInXOF / CURRENCY_CONFIG.rates[toCurrency];
    }

    /**
     * Convertir le total du panier vers USD pour PayPal
     * @returns {number}
     */
    function getTotalInUSD() {
        // Le total est toujours stocké en XOF dans la session
        const totalInXOF = CURRENCY_CONFIG.totalInXOF;

        // Convertir XOF vers USD
        const totalInUSD = convertCurrency(totalInXOF, 'XOF', 'USD');

        // Arrondir à 2 décimales
        return parseFloat(totalInUSD.toFixed(2));
    }

    /**
     * Formater un montant dans la devise courante
     * @param {number} amountInXOF - Montant en XOF
     * @returns {string}
     */
    function formatAmount(amountInXOF) {
        if (typeof CurrencyHelper !== 'undefined') {
            return CurrencyHelper.format(amountInXOF);
        }

        // Fallback si CurrencyHelper n'est pas disponible
        const converted = convertCurrency(amountInXOF, 'XOF', CURRENCY_CONFIG.currentCode);
        const decimals = CURRENCY_CONFIG.currentCode === 'XOF' ? 0 : 2;
        const formatted = converted.toLocaleString('fr-FR', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
        return `${formatted} ${CURRENCY_CONFIG.currentSymbol}`;
    }

    // Fonction pour afficher/masquer les détails du produit
    function toggleProductDetails(element) {
        const accordion = element.closest('.product-accordion');
        const details = accordion.querySelector('.product-details');
        accordion.classList.toggle('active');
        details.classList.toggle('show');
    }

    // Variables globales
    let requiresDelivery = false;
    let deliveryFormData = {};
    let selectedPaymentMethod = null;

    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        // Charger la config de devise et mettre à jour les prix
        if (typeof CurrencyHelper !== 'undefined') {
            CurrencyHelper.load().then(() => {
                updateAllPrices();
            });
        } else {
            updateAllPrices();
        }

        const paymentButton = document.getElementById('openPaymentModal');
        if (paymentButton) {
            paymentButton.addEventListener('click', function() {
                showDeliveryOptions();
            });
        }
    });

    // Fonction pour mettre à jour tous les prix affichés
    function updateAllPrices() {
        // Mettre à jour tous les prix des produits
        document.querySelectorAll('[data-price]').forEach(function(element) {
            const priceInXOF = parseFloat(element.dataset.price);
            if (!isNaN(priceInXOF)) {
                element.textContent = formatAmount(priceInXOF);
            }
        });

        // Mettre à jour les totaux
        document.querySelectorAll('[data-total-xof]').forEach(function(element) {
            const totalInXOF = parseFloat(element.dataset.totalXof);
            if (!isNaN(totalInXOF)) {
                element.innerHTML = formatAmount(totalInXOF);
            }
        });
    }

    // Fonction pour afficher les options de livraison
    function showDeliveryOptions() {
        Swal.fire({
            title: 'Options de livraison',
            html: `
                <div style="text-align: center;">
                    <p style="margin-bottom: 1.5rem;">Souhaitez-vous une livraison ?</p>

                    <div class="notice-box" style="text-align: left;">
                        <i class="ri-information-line"></i>
                        <strong>Important :</strong> Après finalisation de votre commande, vous recevrez un email détaillant
                        le processus de livraison en fonction de votre choix.
                    </div>

                    <div class="delivery-options">
                        <div class="delivery-option" id="deliveryYes">
                            <i class="ri-truck-line"></i>
                            <div>Oui, livrez-moi (Tinda Awa)</div>
                        </div>
                        <div class="delivery-option" id="deliveryNo">
                            <i class="ri-store-2-line"></i>
                            <div>Non, livrez à mon cargo</div>
                        </div>
                    </div>

                    <div id="deliveryFormContainer" style="display: none;">
                        <div class="delivery-form">
                            <div class="form-group">
                                <label class="form-label">Type de transport</label>
                                <select class="form-control" id="deliveryType" required>
                                    <option value="">Sélectionnez un type</option>
                                    @foreach (FrontHelper::allTypes() as $type)
                                        <option value="{{ $type->id }}">{{ $type->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pays</label>
                                <select class="form-control" id="country">
                                    <option value="">Sélectionnez un pays</option>
                                    @foreach (FrontHelper::allPays() as $pays)
                                        <option value="{{ $pays->id }}">{{ $pays->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ville</label>
                                <input type="text" class="form-control" id="city" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Adresse complète</label>
                                <input type="text" class="form-control" id="address" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nom du destinataire</label>
                                <input type="text" class="form-control" id="recipientName" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Téléphone du destinataire</label>
                                <input type="tel" class="form-control" id="recipientPhone" required>
                            </div>
                        </div>
                    </div>

                    <div id="cargoFormContainer" style="display: none;">
                        <div class="delivery-form">
                            <div class="form-group">
                                <label class="form-label">Type de transport</label>
                                <select class="form-control" id="cargoDeliveryType" required>
                                    <option value="">Sélectionnez un type</option>
                                    @foreach (FrontHelper::allTypes() as $type)
                                        <option value="{{ $type->id }}">{{ $type->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ville du cargo</label>
                                <input type="text" class="form-control" id="cargoCity" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Adresse du cargo</label>
                                <input type="text" class="form-control" id="cargoAddress" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nom de la personne à contacter</label>
                                <input type="text" class="form-control" id="cargoContactName" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Téléphone de contact</label>
                                <input type="tel" class="form-control" id="cargoContactPhone" required>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Suivant',
            cancelButtonText: 'Annuler',
            cancelButtonColor: '#777',
            confirmButtonColor: '#b78d65',
            width: '600px',
            allowOutsideClick: false,
            didOpen: () => {
                const deliveryYes = document.getElementById('deliveryYes');
                const deliveryNo = document.getElementById('deliveryNo');

                if (deliveryYes) {
                    deliveryYes.addEventListener('click', function() {
                        requiresDelivery = true;
                        this.classList.add('selected');
                        if (deliveryNo) deliveryNo.classList.remove('selected');
                        document.getElementById('deliveryFormContainer').style.display = 'block';
                        document.getElementById('cargoFormContainer').style.display = 'none';
                        Swal.getConfirmButton().disabled = false;
                    });
                }

                if (deliveryNo) {
                    deliveryNo.addEventListener('click', function() {
                        requiresDelivery = false;
                        this.classList.add('selected');
                        if (deliveryYes) deliveryYes.classList.remove('selected');
                        document.getElementById('cargoFormContainer').style.display = 'block';
                        document.getElementById('deliveryFormContainer').style.display = 'none';
                        Swal.getConfirmButton().disabled = false;
                    });
                }

                Swal.getConfirmButton().disabled = true;
            },
            preConfirm: () => {
                if (requiresDelivery) {
                    const deliveryType = document.getElementById('deliveryType').value;
                    const country = document.getElementById('country').value;
                    const city = document.getElementById('city').value;
                    const address = document.getElementById('address').value;
                    const recipientName = document.getElementById('recipientName').value;
                    const recipientPhone = document.getElementById('recipientPhone').value;

                    if (!deliveryType || !country || !city || !address || !recipientName || !recipientPhone) {
                        Swal.showValidationMessage('Veuillez remplir tous les champs du formulaire');
                        return false;
                    }

                    deliveryFormData = {
                        deliveryType,
                        country,
                        city,
                        address,
                        recipientName,
                        recipientPhone,
                        deliveryMethod: 'tinda_awa'
                    };
                } else {
                    const cargoDeliveryType = document.getElementById('cargoDeliveryType').value;
                    const cargoCity = document.getElementById('cargoCity').value;
                    const cargoAddress = document.getElementById('cargoAddress').value;
                    const cargoContactName = document.getElementById('cargoContactName').value;
                    const cargoContactPhone = document.getElementById('cargoContactPhone').value;

                    if (!cargoDeliveryType || !cargoCity || !cargoAddress || !cargoContactName || !cargoContactPhone) {
                        Swal.showValidationMessage('Veuillez remplir tous les champs du formulaire cargo');
                        return false;
                    }

                    deliveryFormData = {
                        deliveryType: cargoDeliveryType,
                        city: cargoCity,
                        address: cargoAddress,
                        contactName: cargoContactName,
                        contactPhone: cargoContactPhone,
                        deliveryMethod: 'cargo'
                    };
                }

                // Envoyer les données de livraison au serveur
                return fetch("{{ route('store_delivery_info') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(deliveryFormData)
                }).then(response => response.json())
                  .then(data => {
                      if (!data.success) {
                          Swal.showValidationMessage('Erreur lors de l\'enregistrement des informations');
                          return false;
                      }
                      return true;
                  }).catch(error => {
                      Swal.showValidationMessage('Erreur réseau');
                      return false;
                  });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showPaymentMethodSelection();
            }
        });
    }

    // Fonction pour afficher la sélection du mode de paiement
    function showPaymentMethodSelection() {
        const formattedTotal = formatAmount(CURRENCY_CONFIG.totalInXOF);
        const totalInUSD     = getTotalInUSD();

        Swal.fire({
            title: 'Mode de paiement',
            html: `
                <div style="text-align: center;">
                    <div style="margin-bottom: 1.5rem; font-size: 1.1rem;">
                        <strong>Montant à payer:</strong><br>
                        <span style="font-size:1.3rem; color:var(--accent-gold); font-weight:700;">
                            ${formattedTotal}
                        </span>
                    </div>
                    <div class="payment-methods">
                        <div class="payment-method" id="paypalMethod" onclick="selectPaymentMethod('paypal')">
                            <i class="ri-paypal-line" style="font-size:2rem;"></i>
                            <div style="font-weight:600;">PayPal</div>
                            <small style="color:var(--text-muted);">Carte bancaire</small>
                        </div>
                        <div class="payment-method" id="elongoMethod" onclick="selectPaymentMethod('elongopay')">
                            <i class="ri-wallet-3-line" style="font-size:2rem; color:var(--accent-gold);"></i>
                            <div style="font-weight:600; color:var(--accent-gold);">ElongoPay</div>
                            <small style="color:var(--text-muted);">Portefeuille digital</small>
                        </div>
                    </div>
                    <div id="paypal-button-container" style="margin-top:1.5rem; display:none; max-width:400px; margin-left:auto; margin-right:auto;"></div>
                    <div id="elongopay-section" style="margin-top:1.5rem; display:none;">
                        <button onclick="openElongoPayWindow()"
                            style="width:100%; padding:1rem; background:linear-gradient(135deg,#007AFF,#8A2BE2);
                                color:white; border:none; border-radius:8px; font-size:1rem;
                                font-weight:700; cursor:pointer; font-family:inherit;">
                            <i class="ri-wallet-3-line"></i> Payer avec ElongoPay
                        </button>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: 'Retour',
            cancelButtonColor: '#777',
            width: '600px',
            allowOutsideClick: false,
        });
    }

    function selectPaymentMethod(method) {
        selectedPaymentMethod = method;

        document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));

        if (method === 'paypal') {
            document.getElementById('paypalMethod').classList.add('selected');
            document.getElementById('paypal-button-container').style.display = 'block';
            document.getElementById('elongopay-section').style.display = 'none';
            renderPayPalButton();
        } else {
            document.getElementById('elongoMethod').classList.add('selected');
            document.getElementById('paypal-button-container').style.display = 'none';
            document.getElementById('elongopay-section').style.display = 'block';
        }
    }

    // ─── ELONGOPAY WINDOW ──────────────────────────────────────────────────────
    let elongoPayWindow = null;

    function openElongoPayWindow() {
        const amount  = CURRENCY_CONFIG.totalInXOF;
        const origin  = encodeURIComponent(window.location.origin);
        const ref     = 'SUMB-' + Date.now();
        const url     = `http://localhost:8001/payment?amount=${amount}&origin=${origin}&ref=${ref}`;

        // Dimensions et centrage
        const w = 500, h = 680;
        const left = (screen.width  / 2) - (w / 2);
        const top  = (screen.height / 2) - (h / 2);

        elongoPayWindow = window.open(
            url,
            'elongopay_payment',
            `width=${w},height=${h},left=${left},top=${top},resizable=no,scrollbars=yes,toolbar=no,menubar=no,location=no`
        );

        // Écouter la réponse de la popup
        window.addEventListener('message', handleElongoPayMessage);
    }

    function handleElongoPayMessage(event) {
        // Optionnel en local : vérifier l'origine
        // if (event.origin !== 'http://localhost:8001') return;

        if (!event.data || event.data.type !== 'ELONGOPAY_SUCCESS') return;

        // Nettoyer le listener
        window.removeEventListener('message', handleElongoPayMessage);

        const { transaction_id, amount_xof } = event.data;

        // Fermer la popup si pas encore fermée
        if (elongoPayWindow && !elongoPayWindow.closed) {
            elongoPayWindow.close();
        }

        // Fermer le modal SweetAlert
        Swal.close();

        // Créer la commande sur Sumbaawa
        Swal.fire({
            title: 'Traitement en cours...',
            text: 'Enregistrement de votre commande',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch("{{ route('elongopay.capture') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                transaction_id:    transaction_id,
                amount_xof:        amount_xof,
                requiresDelivery:  requiresDelivery,
                deliveryInfo:      deliveryFormData
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Paiement réussi !',
                    html: `
                        <div style="text-align:center;">
                            <i class="ri-checkbox-circle-fill" style="font-size:4rem; color:#4CAF50;"></i>
                            <p style="margin-top:1rem; font-size:1.1rem;">
                                Paiement ElongoPay effectué avec succès.
                            </p>
                            <p style="font-size:0.9rem;">
                                Code commande: <strong>${data.order_code}</strong><br>
                                Vous recevrez un email de confirmation.
                            </p>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonText: 'Voir ma commande',
                    confirmButtonColor: '#b78d65',
                    allowOutsideClick: false
                }).then(result => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('order.confirmation') }}?order_id=" + data.order_id;
                    }
                });
            } else {
                Swal.fire({
                    title: 'Erreur',
                    text: data.message || 'Erreur lors de l\'enregistrement de la commande',
                    icon: 'error',
                    confirmButtonColor: '#b78d65'
                });
            }
        })
        .catch(() => {
            Swal.fire({ title: 'Erreur', text: 'Erreur réseau', icon: 'error', confirmButtonColor: '#b78d65' });
        });
    }

    // Fonction pour initialiser le bouton PayPal
    function renderPayPalButton() {
        const totalAmountUSD = getTotalInUSD();

        paypal.Buttons({
            style: {
                layout: 'vertical',
                color:  'gold',
                shape:  'rect',
                label:  'paypal'
            },

            createOrder: function(data, actions) {
                return fetch("{{ route('paypal.create-order') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        amount: totalAmountUSD,
                        currency: CURRENCY_CONFIG.currentCode,
                        amountInXOF: CURRENCY_CONFIG.totalInXOF,
                        requiresDelivery: requiresDelivery,
                        deliveryInfo: deliveryFormData
                    })
                }).then(function(res) {
                    return res.json();
                }).then(function(data) {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    return data.orderID;
                }).catch(function(error) {
                    console.error('Erreur lors de la création de la commande:', error);
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Impossible de créer la commande PayPal. Veuillez réessayer.',
                        icon: 'error',
                        confirmButtonColor: '#b78d65'
                    });
                });
            },

            onApprove: function(data, actions) {
                return fetch("{{ route('paypal.capture-order') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        orderID: data.orderID,
                        currency: CURRENCY_CONFIG.currentCode,
                        amountInXOF: CURRENCY_CONFIG.totalInXOF,
                        requiresDelivery: requiresDelivery,
                        deliveryInfo: deliveryFormData
                    })
                }).then(function(res) {
                    return res.json();
                }).then(function(details) {
                    if (details.success) {
                        Swal.fire({
                            title: 'Paiement réussi!',
                            html: `
                                <div style="text-align: center;">
                                    <i class="ri-checkbox-circle-fill" style="font-size: 4rem; color: #4CAF50;"></i>
                                    <p style="margin-top: 1rem; font-size: 1.1rem;">
                                        Votre paiement a été effectué avec succès.
                                    </p>
                                    <p style="font-size: 0.9rem;">
                                        Code commande: ${details.order_code}<br>
                                        Vous recevrez un email de confirmation sous peu.
                                    </p>
                                </div>
                            `,
                            icon: 'success',
                            confirmButtonText: 'Voir ma commande',
                            confirmButtonColor: '#b78d65',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('order.confirmation') }}?order_id=" + details.order_id;
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Erreur de paiement',
                            text: details.error || 'Le paiement n\'a pas pu être traité.',
                            icon: 'error',
                            confirmButtonColor: '#b78d65'
                        });
                    }
                }).catch(function(error) {
                    console.error('Erreur lors de la capture du paiement:', error);
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Une erreur est survenue lors du traitement du paiement.',
                        icon: 'error',
                        confirmButtonColor: '#b78d65'
                    });
                });
            },

            onError: function(err) {
                console.error('Erreur PayPal:', err);
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
