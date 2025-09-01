@extends('front.layouts.master')
@section('title', 'Résultats de recherche')
@section('filAriane')
    <li class="breadcrumb-item active" aria-current="page">Résultats de recherche pour "{{ $query }}"</li>
@endsection
@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <style>
        :root {
            --primary-dark: #1a1a1a;
            --primary-light: #f8f8f8;
            --accent-gold: #b78d65;
            --accent-gold-dark: #9a7150;
            --text-muted: #777;
        }

        .banner-overlay {
            background: linear-gradient(to right, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 100%);
        }

        .collection-banner {
            display: block;
            transition: all 0.3s ease;
        }

        .collection-banner:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .partition2 > div:first-child .banner-overlay {
            background: linear-gradient(to right, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 100%);
        }

        .partition2 > div:last-child .banner-overlay {
            background: linear-gradient(to right, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 100%);
        }

        .btn-outline-light {
            border-width: 2px;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background-color: var(--accent-gold);
            border-color: var(--accent-gold);
            color: var(--primary-dark);
            transform: translateY(-3px);
        }

        .section-b-space {
            position: relative;
            overflow: hidden;
        }

        .section-b-space::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(183,141,101,0.2) 0%, rgba(183,141,101,0) 70%);
            z-index: 0;
        }

        .section-b-space::after {
            content: "";
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(183,141,101,0.1) 0%, rgba(183,141,101,0) 70%);
            z-index: 0;
        }

        .filter-btn {
            background: var(--accent-gold);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .filter-btn:hover {
            background: var(--accent-gold-dark);
        }

        .product-wrapper-grid .col-grid-box {
            transition: all 0.3s ease;
        }

        .product-wrapper-grid .col-grid-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .basic-product .img-wrapper img {
            width: 100%;
            object-fit: cover;
        }

        .basic-product .product-detail .brand-w-color a {
            color: var(--primary-dark);
            font-weight: 500;
            text-decoration: none;
        }

        .basic-product .product-detail .brand-w-color a:hover {
            color: var(--accent-gold);
        }

        .basic-product .product-detail h6 {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin: 0.5rem 0;
        }

        .basic-product .product-detail .price {
            color: var(--accent-gold);
            font-weight: 600;
        }

        .basic-product .product-detail .discounted-price {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-left: 0.5rem;
        }

        .no-results {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
        }

        .no-results i {
            font-size: 3rem;
            color: var(--accent-gold);
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <!-- section start -->
    <section class="section-b-space ratio_asos">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <div class="collection-content col-xl-12 col-lg-12">
                        <div class="page-main-content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="filter-btn btn"><i class="ri-filter-fill"></i> Filtrer</button>
                                    <div class="collection-product-wrapper">
                                        <div class="product-top-filter mt-0">
                                            <div class="product-filter-content w-100">
                                                <div class="d-flex align-items-center gap-sm-3 gap-2"></div>
                                                <div class="collection-grid-view">
                                                    <ul>
                                                        <li class="product-2-layout-view grid-icon">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/inner-page/icon/2.png') }}" alt="sort" class="">
                                                        </li>
                                                        <li class="product-3-layout-view grid-icon active">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/inner-page/icon/3.png') }}" alt="sort" class="">
                                                        </li>
                                                        <li class="product-4-layout-view grid-icon">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/inner-page/icon/4.png') }}" alt="sort" class="">
                                                        </li>
                                                        <li class="list-layout-view list-icon">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/inner-page/icon/list.png') }}" alt="sort" class="">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Section pour les produits et pagination -->
                                        <div id="produits-section">
                                            <div class="product-wrapper-grid">
                                                <div class="row g-3 g-sm-4">
                                                    @forelse ($produits as $produit)
                                                        <div class="col-xl-4 col-6 col-grid-box">
                                                            <div class="basic-product theme-product-1">
                                                                <div class="overflow-hidden">
                                                                    <div class="img-wrapper">
                                                                        <a href="{{ route('produits.single', $produit) }}">
                                                                            <img src="{{ asset($produit->image_main) }}"
                                                                                 class="img-fluid blur-up lazyload"
                                                                                 alt="{{ $produit->name }}"
                                                                                 style="height: 250px; object-fit: cover;">
                                                                        </a>
                                                                        <div class="rating-label">
                                                                            <i class="ri-star-s-fill"></i> <span>4.5</span>
                                                                        </div>
                                                                        <div class="cart-info">
                                                                            <a href="{{ route('wishlist.add', $produit) }}"
                                                                               title="Ajouter aux favoris"
                                                                               class="wishlist-icon">
                                                                                <i class="ri-heart-{{ FrontHelper::isProductInFavorites($produit->id) ? 'fill' : 'line' }}"></i>
                                                                            </a>
                                                                            <button class="add-to-cart"
                                                                                    data-product-id="{{ $produit->id }}"
                                                                                    data-product-name="{{ $produit->name }}"
                                                                                    data-product-image="{{ asset($produit->image_main) }}"
                                                                                    data-product-price="{{ $produit->price }}"
                                                                                    data-product-original-price="{{ $produit->original_price ?? '' }}"
                                                                                    data-product-color="{{ $produit->color ?? '' }}"
                                                                                    data-product-confort="{{ $produit->niveau_confort ?? '' }}"
                                                                                    data-product-poids="{{ $produit->poids }}"
                                                                                    title="Ajouter au panier">
                                                                                <i class="ri-shopping-cart-line"></i>
                                                                            </button>
                                                                            <a href="{{ route('produits.single', $produit) }}"
                                                                               title="Voir le détail"
                                                                               class="quick-view-btn">
                                                                                <i class="ri-eye-line"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="product-detail">
                                                                        <div>
                                                                            <div class="brand-w-color">
                                                                                <a class="product-title"
                                                                                   href="{{ route('produits.single', $produit) }}">
                                                                                    {{ $produit->name }}
                                                                                </a>
                                                                            </div>
                                                                            <h6>{{ $produit->sousCategorie->label ?? 'Sous-catégorie non définie' }}</h6>
                                                                            <h4 class="price">
                                                                                $ {{ number_format($produit->price, 2, '.', ',') }}
                                                                                @if ($produit->original_price)
                                                                                    <del>$ {{ number_format($produit->original_price, 2, '.', ',') }}</del>
                                                                                    <span class="discounted-price">
                                                                                        {{ round((($produit->original_price - $produit->price) / $produit->original_price) * 100) }}% Off
                                                                                    </span>
                                                                                @endif
                                                                            </h4>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="no-results">
                                                            <i class="ri-search-line"></i>
                                                            <p>Aucun produit trouvé pour "{{ $query }}".</p>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                            <div class="product-pagination">
                                                <div class="theme-paggination-block">
                                                    {{ $produits->links('vendor.pagination.bootstrap-5') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- section end -->
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/price-range.js') }}"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/cart.js') }}"></script>
    <script>
        (function($) {
            $(document).ready(function () {
                // Initialiser le compteur du panier
                cartUtils.initializeCartCount();

                // Attacher les événements du panier pour delete-button et clear-cart dans l'offcanvas
                cartUtils.attachCartEvents();

                // Gestion du bouton "Ajouter au panier"
                $('.add-to-cart').on('click', function (e) {
                    e.preventDefault();
                    const $this = $(this);
                    const productData = {
                        id: $this.data('product-id'),
                        name: $this.data('product-name'),
                        image_main: $this.data('product-image'),
                        price: parseFloat($this.data('product-price').toString().replace(/,/g, '')),
                        original_price: $this.data('product-original-price') ? parseFloat($this.data('product-original-price').toString().replace(/,/g, '')) : null,
                        color: $this.data('product-color'),
                        niveau_confort: $this.data('product-confort'),
                        poids: $this.data('product-poids'),
                        quantity: 1,
                        product_url: '{{ route('produits.single', ':slug') }}'.replace(':slug', $this.data('product-name').toLowerCase().replace(/\s+/g, '-'))
                    };

                    $.ajax({
                        url: '{{ route('cart.add') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            product: productData
                        },
                        success: function (response) {
                            Swal.fire({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                icon: "success",
                                title: "Votre produit a été ajouté au panier"
                            });
                            cartUtils.updateCartOffcanvas(response.cart);
                            if ($('#cartOffcanvas').length) {
                                $('#cartOffcanvas').offcanvas('show');
                            } else {
                                console.error('L\'élément #cartOffcanvas n\'existe pas dans le DOM.');
                            }
                        },
                        error: function (xhr) {
                            console.error('Erreur lors de l\'ajout au panier:', xhr.responseText);
                            Swal.fire({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                icon: "error",
                                title: "Erreur lors de l\'ajout au panier"
                            });
                        }
                    });
                });
            });
        })(jQuery);

        // Success message with SweetAlert2
        document.addEventListener('DOMContentLoaded', function() {
            const status_wishlist_success = '{{ session('wishlist_success') }}';
            const status_wishlist_info = '{{ session('wishlist_info') }}';
            if (status_wishlist_success) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    icon: "success",
                    title: status_wishlist_success
                }).then(() => {
                    window.history.pushState({}, document.title, window.location.pathname);
                });
            }

            if (status_wishlist_info) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    icon: "info",
                    title: status_wishlist_info
                }).then(() => {
                    window.history.pushState({}, document.title, window.location.pathname);
                });
            }
        });
    </script>
@endsection

