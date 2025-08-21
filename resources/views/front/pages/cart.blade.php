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
                                <td colspan="6"><p>Le panier est vide.</p></td>
                            </tr>
                        @else
                            @foreach ($cart as $item)
                                <tr data-product-id="{{ $item['id'] }}">
                                    <td>
                                        <a href="{{ route('produits.single', Str::slug($item['name'])) }}">
                                            <img src="{{ $item['image_main'] }}" class="img-fluid" alt="{{ $item['name'] }}">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('produits.single', Str::slug($item['name'])) }}">{{ $item['name'] }}</a>
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
                                                <h2 class="td-color">${{ number_format($item['price'], 2, '.', ',') }}</h2>
                                                @if (isset($item['original_price']) && $item['original_price'] > $item['price'])
                                                    <del>${{ number_format($item['original_price'], 2, '.', ',') }}</del>
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
                                        <h2>${{ number_format($item['price'], 2, '.', ',') }}</h2>
                                        @if (isset($item['original_price']) && $item['original_price'] > $item['price'])
                                            <del>${{ number_format($item['original_price'], 2, '.', ',') }}</del>
                                            <h6 class="theme-color">Vous économisez : ${{ number_format($item['original_price'] - $item['price'], 2, '.', ',') }}</h6>
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
                                        <h2 class="td-color">${{ number_format($item['price'] * $item['quantity'], 2, '.', ',') }}</h2>
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
                                <h2>${{ number_format($total, 2, '.', ',') }}</h2>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row cart-buttons">
                <div class="col-6">
                    <a href="{{ route('categories.page') }}" class="btn btn-solid text-capitalize">Continuer vos achats</a>
                </div>
                <div class="col-6">
                    <a href="{{ route('checkout') }}" class="btn btn-solid text-capitalize">Passer la commande</a>
                </div>
            </div>
        </div>
    </section>
    <!--section end-->
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/cart.js') }}"></script>
    <script>
        // Charger jQuery 3.6.0 si nécessaire
        window.jQuery || document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"><\/script>');
    </script>
    <script>
        (function($) {
            $(document).ready(function () {
                // Initialiser le compteur du panier
                cartUtils.initializeCartCount();

                // Initialiser la table du panier au chargement
                $.ajax({
                    url: '{{ route('cart.get') }}',
                    method: 'GET',
                    success: function (response) {
                        cartUtils.updateCartTable(response.cart);
                        cartUtils.updateCartOffcanvas(response.cart);
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
            });
        })(jQuery);
    </script>
@endsection
