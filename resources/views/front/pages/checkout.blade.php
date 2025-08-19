@extends('front.layouts.master')
@section('title', 'Récapitulatif de commande')
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
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

        .wisapay-logo {
            max-width: 200px;
            margin: 0 auto 1.5rem;
            display: block;
        }

        /* Styles pour le formulaire de livraison */
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

            .delivery-options {
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

            .wisapay-logo {
                max-width: 150px;
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
            <div class="stat-value">${{ number_format($totalCart, 2, '.', ',') }}</div>
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
                <div class="product-price">${{ number_format($item['price'] * $item['quantity'], 2, '.', ',') }}</div>
                <i class="ri-arrow-down-s-line accordion-icon"></i>
            </div>

            <div class="product-details">
                <div class="detail-row">
                    <span class="detail-label">Prix unitaire:</span>
                    <span class="detail-value">${{ number_format($item['price'], 2, '.', ',') }}</span>
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
            <span class="total-value">${{ number_format($totalCart, 2, '.', ',') }}</span>
        </div>

        <button class="checkout-btn" id="openPaymentModal">
            Procéder au paiement
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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

    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier si le bouton existe avant d'ajouter l'event listener
        const paymentButton = document.getElementById('openPaymentModal');
        if (paymentButton) {
            paymentButton.addEventListener('click', function() {
                showDeliveryOptions();
            });
        }
    });

    // Fonction pour afficher les options de livraison
    function showDeliveryOptions() {
        Swal.fire({
            title: 'Options de livraison',
            html: `
                <div style="text-align: center;">
                    <p style="margin-bottom: 1.5rem;">Souhaitez-vous une livraison à domicile ?</p>

                    <div class="delivery-options">
                        <div class="delivery-option" id="deliveryYes">
                            <i class="ri-truck-line"></i>
                            <div>Oui, livrez-moi</div>
                        </div>
                        <div class="delivery-option" id="deliveryNo">
                            <i class="ri-store-2-line"></i>
                            <div>Non, je viens chercher</div>
                        </div>
                    </div>

                    <div id="deliveryFormContainer" style="display: none;">
                        <div class="delivery-form">
                            <div class="form-group">
                                <label class="form-label">Type de livraison</label>
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
                                <label class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="address" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Code postal</label>
                                <input type="text" class="form-control" id="postalCode" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="phone" required>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Continuer vers le paiement',
            cancelButtonText: 'Annuler',
            cancelButtonColor: '#777',
            confirmButtonColor: '#b78d65',
            width: '600px',
            allowOutsideClick: false,
            didOpen: () => {
                // Gestion du choix de livraison
                const deliveryYes = document.getElementById('deliveryYes');
                const deliveryNo = document.getElementById('deliveryNo');

                if (deliveryYes) {
                    deliveryYes.addEventListener('click', function() {
                        requiresDelivery = true;
                        this.classList.add('selected');
                        if (deliveryNo) deliveryNo.classList.remove('selected');
                        document.getElementById('deliveryFormContainer').style.display = 'block';
                        Swal.getConfirmButton().disabled = false;
                    });
                }

                if (deliveryNo) {
                    deliveryNo.addEventListener('click', function() {
                        requiresDelivery = false;
                        this.classList.add('selected');
                        if (deliveryYes) deliveryYes.classList.remove('selected');
                        document.getElementById('deliveryFormContainer').style.display = 'none';
                        Swal.getConfirmButton().disabled = false;
                    });
                }

                // Désactiver le bouton continuer tant qu'aucun choix n'est fait
                Swal.getConfirmButton().disabled = true;
            },
            preConfirm: () => {
                if (requiresDelivery) {
                    // Valider le formulaire de livraison
                    const deliveryType = document.getElementById('deliveryType').value;
                    const country = document.getElementById('country').value;
                    const city = document.getElementById('city').value;
                    const address = document.getElementById('address').value;
                    const postalCode = document.getElementById('postalCode').value;
                    const email = document.getElementById('email').value;
                    const phone = document.getElementById('phone').value;

                    if (!deliveryType || !country || !city || !address || !postalCode || !email || !phone) {
                        Swal.showValidationMessage('Veuillez remplir tous les champs du formulaire');
                        return false;
                    }

                    // Stocker les données de livraison
                    deliveryFormData = {
                        deliveryType,
                        country,
                        city,
                        address,
                        postalCode,
                        email,
                        phone
                    };
                }
                return true;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Afficher le modal WisaPay
                showWisaPayModal();
            }
        });
    }

    // Fonction pour afficher le modal WisaPay
    function showWisaPayModal() {
        const totalAmount = {{ $totalCart }};
        const formattedAmount = '${{ number_format($totalCart, 2, '.', ',') }}';

        // Afficher d'abord le logo WisaPay avec le message
        Swal.fire({
            title: 'Paiement via WisaPay',
            html: `
                <div style="text-align: center;">
                    <img src="{{ asset(FrontHelper::getEnvFolder() .'partners/p1.png') }}" alt="WisaPay" class="wisapay-logo">
                    <p style="margin-top: 1rem; font-size: 1.1rem;">
                        Veuillez confirmer le paiement sur votre portefeuille WisaPay
                    </p>
                    <div style="margin: 1.5rem 0;">
                        <div style="display: inline-block; padding: 0.5rem 1rem; background: #f5f5f5; border-radius: 20px;">
                            <span style="font-weight: 600; color: #b78d65;">Montant:</span>
                            ${formattedAmount}
                        </div>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: 'Annuler',
            cancelButtonColor: '#777',
            width: '500px',
            allowOutsideClick: false,
            didOpen: () => {
                // Simuler un paiement après 5 secondes
                setTimeout(() => {
                    Swal.fire({
                        title: 'Paiement réussi!',
                        html: `
                            <div style="text-align: center;">
                                <i class="ri-checkbox-circle-fill" style="font-size: 4rem; color: #4CAF50;"></i>
                                <p style="margin-top: 1rem; font-size: 1.1rem;">
                                    Votre paiement de ${formattedAmount} a été effectué avec succès.
                                </p>
                                ${requiresDelivery ?
                                    `<p style="font-size: 0.9rem;">Votre commande sera livrée à l'adresse fournie.</p>` :
                                    '<p style="font-size: 0.9rem;">Veuillez venir chercher votre commande en magasin.</p>'
                                }
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'Continuer',
                        confirmButtonColor: '#b78d65',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Envoyer les données au serveur et rediriger
                            submitOrderData();
                        }
                    });
                }, 5000);
            }
        });
    }

    // Fonction pour envoyer les données de commande
    function submitOrderData() {
        // Ici vous devriez envoyer les données au serveur via AJAX
        const orderData = {
            requiresDelivery,
            deliveryInfo: requiresDelivery ? deliveryFormData : null,
            totalAmount: {{ $totalCart }},
            cartItems: {!! json_encode(session('cart', [])) !!}
        };

        console.log('Données de commande:', orderData);

        // Simulation d'envoi - à remplacer par un appel AJAX réel
        fetch("{{ route('checkout.process') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            // Redirection vers la page de confirmation
            window.location.href = "/";
        })
        .catch(error => {
            console.error('Erreur:', error);
            window.location.href = "/";
        });
    }
</script>
@endsection
