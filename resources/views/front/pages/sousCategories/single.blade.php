@extends('front.layouts.master')
@section('title', $sousCategorie->label)
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/price-range.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <style>
        .view-thumbnail-slider .slider-image {
            max-width: 80px;
            max-height: 80px;
            overflow: hidden;
        }
        .view-thumbnail-slider .slider-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .description-text {
            max-height: 100px;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .description-text.expanded {
            max-height: none;
        }
        .see-more-btn {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
            display: block;
            margin-top: 5px;
        }
    </style>
@endsection
@section('filAriane')
    <li class="breadcrumb-item">
        <a href="{{ route('categories.single', $sousCategorie->categorie) }}">{{ $sousCategorie->categorie->label }}</a>
    </li>
    <li class="breadcrumb-item active">{{ $sousCategorie->label }}</li>
@endsection
@section('content')
    <!-- section start -->
    <section class="section-b-space ratio_asos">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 collection-filter">
                        <!-- side-bar collapse block start -->
                        <div class="collection-filter-block">
                            <!-- brand filter start -->
                            <button class="collection-mobile-back btn">
                                <span class="filter-back">Retour</span>
                                <i class="ri-arrow-left-s-line"></i>
                            </button>
                            <div class="collection-collapse-block open">
                                <div class="accordion collection-accordion" id="accordionPanelsStayOpenExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button pt-0" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne"
                                                    aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                                {{ $sousCategorie->categorie->label }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <ul class="collection-listing">
                                                    <li>
                                                        <div class="form-check">
                                                            <input class="form-check-input subcategory-checkbox" type="checkbox" checked value="{{ $sousCategorie->id }}">
                                                            <label class="form-check-label" for="checkbox">
                                                                {{ $sousCategorie->label }}
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- side-bar collapse block end -->
                    </div>
                    <div class="collection-content col-xl-9 col-lg-8">
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
                                                                        <a href="{{ route('produits.single', $produit) }}"><img
                                                                                src="{{ asset($produit->image_main) }}"
                                                                                class="img-fluid blur-up lazyload" alt="{{ $produit->name }}" style="height: 250px; object-fit: cover;"></a>
                                                                        <div class="rating-label"><i class="ri-star-s-fill"></i> <span>4.5</span></div>
                                                                        <div class="cart-info">
                                                                            <a href="{{ route('wishlist.add', $produit->id) }}" title="Ajouter aux favoris" class="wishlist-icon">
                                                                                <i class="ri-heart-line"></i>
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
                                                                            <a href="{{ route('produits.single', $produit) }}" title="Voir le détail" class="quick-view-btn">
                                                                                <i class="ri-eye-line"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="product-detail">
                                                                        <div>
                                                                            <div class="brand-w-color">
                                                                                <a class="product-title" href="{{ route('produits.single', $produit) }}">
                                                                                    {{ $produit->name }}
                                                                                </a>
                                                                            </div>
                                                                            <h6>{{ $produit->sousCategorie->label ?? 'Sous-catégorie non définie' }}</h6>
                                                                            <h4 class="price">$ {{ number_format($produit->price, 2, '.', ',') }}
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
                                                        <p>Aucun produit trouvé.</p>
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
                        quantity: 1, // Quantité par défaut
                        product_url: '{{ route('produits.single', ':slug') }}'.replace(':slug', $this.data('product-name').toLowerCase().replace(/\s+/g, '-'))
                    };

                    $.ajax({
                        url: '{{ route('cart.add') }}',
                        method: 'POST',
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
    </script>
@endsection
