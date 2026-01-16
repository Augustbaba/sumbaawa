@php
    use Illuminate\Support\Str;
@endphp

@extends('front.layouts.master')
@section('title', 'Mon Panier')
@section('filAriane')
    <li class="breadcrumb-item"><a href="">Produits</a></li>
    <li class="breadcrumb-item active" aria-current="page">Panier</li>
@endsection
@section('content')
    <!--section start-->
    <section class="cart-section section-b-space">
        <div class="container">
            <div class="table-responsive">
                <table class="table cart-table">
                    <thead>
                        <tr class="table-head">
                            <th>Image</th>
                            <th>Nom du produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cart = session()->get('cart',[]);
                            $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
                        @endphp
                        @if (empty($cart))
                            <tr>
                                <td colspan="6">
                                    <div class="empty-cart text-center py-5">
                                        <i class="ri-shopping-cart-line" style="font-size: 4rem; color: #ccc;"></i>
                                        <p class="mt-3">Votre panier est vide.</p>
                                        <a href="{{ route('categories.page') }}" class="btn btn-solid mt-3">
                                            Commencer vos achats
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($cart as $item)
                                <tr data-product-id="{{ $item['id'] }}">
                                    <td>
                                        <a href="{{ $item['product_url'] }}">
                                            <img src="{{ $item['image_main'] }}" class="img-fluid" alt="{{ $item['name'] }}">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ $item['product_url'] }}">{{ $item['name'] }}</a><br>
                                        <small>Couleur: {{ $item['color'] ?? 'Non Spécifié' }}</small>
                                        <div class="mobile-cart-content row">
                                            <div class="col">
                                                <div class="qty-box">
                                                    <div class="input-group qty-container">
                                                        <button class="btn qty-btn-minus" data-product-id="{{ $item['id'] }}">
                                                            <i class="ri-arrow-left-s-line"></i>
                                                        </button>
                                                        <input type="number" readonly name="qty" class="form-control input-qty" value="{{ $item['quantity'] }}">
                                                        <button class="btn qty-btn-plus" data-product-id="{{ $item['id'] }}">
                                                            <i class="ri-arrow-right-s-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col table-price">
                                                <h2 class="td-color"
                                                    data-price="{{ $item['price'] }}"
                                                    data-original-price="{{ $item['original_price'] ?? 0 }}">
                                                    {{ FrontHelper::format_currency($item['price']) }}
                                                </h2>
                                                @if (isset($item['original_price']) && $item['original_price'] > $item['price'])
                                                    <del class="original-price">{{ FrontHelper::format_currency($item['original_price']) }}</del>
                                                @endif
                                            </div>
                                            <div class="col">
                                                <h2 class="td-color">
                                                    <a href="#!" class="icon remove-btn" data-product-id="{{ $item['id'] }}">
                                                        <i class="ri-close-line"></i>
                                                    </a>
                                                </h2>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="table-price">
                                        <h2 data-price="{{ $item['price'] }}"
                                            data-original-price="{{ $item['original_price'] ?? 0 }}">
                                            {{ FrontHelper::format_currency($item['price']) }}
                                        </h2>
                                        @if (isset($item['original_price']) && $item['original_price'] > $item['price'])
                                            <del class="original-price">{{ FrontHelper::format_currency($item['original_price']) }}</del>
                                            <h6 class="theme-color savings"
                                                data-savings="{{ $item['original_price'] - $item['price'] }}">
                                                Vous économisez : {{ FrontHelper::format_currency($item['original_price'] - $item['price']) }}
                                            </h6>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="qty-box">
                                            <div class="input-group qty-container">
                                                <button class="btn qty-btn-minus" data-product-id="{{ $item['id'] }}">
                                                    <i class="ri-arrow-left-s-line"></i>
                                                </button>
                                                <input type="number" readonly name="qty" class="form-control input-qty" value="{{ $item['quantity'] }}">
                                                <button class="btn qty-btn-plus" data-product-id="{{ $item['id'] }}">
                                                    <i class="ri-arrow-right-s-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h2 class="td-color item-total"
                                            data-price="{{ $item['price'] }}"
                                            data-quantity="{{ $item['quantity'] }}">
                                            {{ FrontHelper::format_currency($item['price'] * $item['quantity']) }}
                                        </h2>
                                    </td>
                                    <td>
                                        <a href="#!" class="icon remove-btn" data-product-id="{{ $item['id'] }}">
                                            <i class="ri-close-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="d-md-table-cell d-none">Total :</td>
                            <td class="d-md-none">Total :</td>
                            <td>
                                <h2 class="cart-total" data-total="{{ $total }}">
                                    {{ FrontHelper::format_currency($total) }}
                                </h2>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row cart-buttons">
                <div class="col-6">
                    <a href="{{ route('categories.page') }}" class="btn btn-solid text-capitalize">
                        Continuer vos achats
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('checkout') }}" class="btn btn-solid text-capitalize {{ empty($cart) ? 'disabled' : '' }}">
                        Passer la commande
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!--section end-->
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/currency.js') }}"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/cart.js') }}"></script>
    <script>
        // Charger jQuery 3.6.0 si nécessaire
        window.jQuery || document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"><\/script>');
    </script>
    <script>
        (function($) {
            $(document).ready(function () {
                // Attendre que la config de devise soit chargée
                CurrencyHelper.load().then(() => {
                    // Mettre à jour tous les prix sur la page
                    updateAllPrices();

                    // Initialiser le compteur du panier
                    if (window.cartUtils) {
                        cartUtils.initializeCartCount();
                    }

                    // Initialiser la table du panier au chargement
                    initializeCart();
                });

                // Fonction pour mettre à jour tous les prix affichés
                function updateAllPrices() {
                    // Mettre à jour les prix unitaires
                    $('[data-price]').not('.item-total, .cart-total, .savings').each(function() {
                        const priceInXOF = parseFloat($(this).data('price'));
                        const originalPrice = $(this).data('original-price');

                        if (!isNaN(priceInXOF)) {
                            const formattedPrice = CurrencyHelper.format(priceInXOF);
                            $(this).html(formattedPrice);

                            // Mettre à jour le prix original barré si présent
                            const originalPriceElement = $(this).siblings('.original-price');
                            if (originalPriceElement.length && originalPrice && parseFloat(originalPrice) > 0) {
                                const formattedOriginalPrice = CurrencyHelper.format(parseFloat(originalPrice));
                                originalPriceElement.html(formattedOriginalPrice);
                            }
                        }
                    });

                    // Mettre à jour les totaux par ligne
                    $('.item-total').each(function() {
                        const priceInXOF = parseFloat($(this).data('price'));
                        const quantity = parseInt($(this).data('quantity'));

                        if (!isNaN(priceInXOF) && !isNaN(quantity)) {
                            const total = priceInXOF * quantity;
                            const formattedTotal = CurrencyHelper.format(total);
                            $(this).html(formattedTotal);
                        }
                    });

                    // Mettre à jour les économies
                    $('.savings').each(function() {
                        const savingsInXOF = parseFloat($(this).data('savings'));

                        if (!isNaN(savingsInXOF)) {
                            const formattedSavings = CurrencyHelper.format(savingsInXOF);
                            $(this).html('Vous économisez : ' + formattedSavings);
                        }
                    });

                    // Mettre à jour le total général
                    const cartTotal = $('.cart-total');
                    if (cartTotal.length) {
                        const totalInXOF = parseFloat(cartTotal.data('total'));
                        if (!isNaN(totalInXOF)) {
                            const formattedTotal = CurrencyHelper.format(totalInXOF);
                            cartTotal.html(formattedTotal);
                        }
                    }
                }

                // Initialiser le panier
                function initializeCart() {
                    $.ajax({
                        url: '{{ route('cart.get') }}',
                        method: 'GET',
                        success: function (response) {
                            if (window.cartUtils) {
                                cartUtils.updateCartTable(response.cart);
                                cartUtils.updateCartOffcanvas(response.cart);
                            }
                            // Mettre à jour les prix après avoir rechargé le panier
                            setTimeout(updateAllPrices, 100);
                        },
                        error: function (xhr) {
                            console.error('Erreur lors de la récupération du panier:', xhr.responseText);
                            Swal.fire({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                icon: "error",
                                title: "Erreur lors de la récupération du panier"
                            });
                        }
                    });
                }

                // Gestion du bouton checkout
                $('.btn-solid[href="{{ route('checkout') }}"]').on('click', function(e) {
                    if ($(this).hasClass('disabled')) {
                        e.preventDefault();
                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: "info",
                            title: "Votre panier est vide"
                        });
                    }
                });
            });
        })(jQuery);
    </script>
@endsection
