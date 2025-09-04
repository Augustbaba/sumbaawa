@extends('front.layouts.master')
@section('title', 'Accueil')
@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <style>
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
            background-color: #b78d65;
            border-color: #b78d65;
            color: #1a1a1a;
            transform: translateY(-3px);
        }

        .section1 {
            position: relative;
            overflow: hidden;
        }

        .section1::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(183,141,101,0.2) 0%, rgba(183,141,101,0) 70%);
            z-index: 0;
        }

        .section1::after {
            content: "";
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(183,141,101,0.1) 0%, rgba(183,141,101,0) 70%);
            z-index: 0;
        }
    </style>
@endsection
@section('content')
    <!-- collection banner -->
    <section class="pb-0 banner-section">
        <div class="container">
            <div class="row partition2">
                <!-- Bannière 1 - Promotion meubles salon -->
                <div class="col-md-6">
                    <div class="position-relative rounded-3 overflow-hidden">
                        <a href="{{ route('categories.page') }}" class="collection-banner">
                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/furniture/10.png') }}"
                                 class="img-fluid blur-up lazyload w-100"
                                 alt="Promotion meubles salon"
                                 style="height: 300px; object-fit: cover;">
                            <div class="banner-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center ps-4">
                                <h3 class="text-white mb-1">Jusqu'à -50%</h3>
                                <p class="text-white mb-2">Collection Salon Exclusive</p>
                                <span class="text-white fw-bold">ACHETER MAINTENANT →</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Bannière 2 - Nouveaux meubles chambre -->
                <div class="col-md-6">
                    <div class="position-relative rounded-3 overflow-hidden">
                        <a href="{{ route('categories.page') }}" class="collection-banner">
                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/furniture/12.png') }}"
                                 class="img-fluid blur-up lazyload w-100"
                                 alt="Promotion meubles salon"
                                 style="height: 300px; object-fit: cover;">
                            <div class="banner-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center ps-4">
                                <h3 class="text-white mb-1">Jusqu'à -50%</h3>
                                <p class="text-white mb-2">Collection Salon Exclusive</p>
                                <span class="text-white fw-bold">ACHETER MAINTENANT →</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- collection banner end -->

    <!-- Paragraph-->
    <div class="title1 section-t-space">
        <h4>Offre Spéciale</h4>
        <h2 class="title-inner1">Dernières nouveautés</h2>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="product-para">
                    <p class="text-center">À la recherche des dernières tendances en matière de mobilier et d’accessoires d’intérieur ?
                        Bienvenue dans notre sélection « Dernières Nouveautés », qui vous propose les créations les plus récentes de vos marques préférées pour sublimer chaque espace de votre maison ou bureau.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Paragraph end -->

    <!-- Product slider -->
    <section class="section-b-space pt-0 ratio_asos">
        <div class="container">
            <div class="g-3 g-md-4 row row-cols-2 row-cols-md-3 row-cols-xl-4">
                @foreach (FrontHelper::fourProducts() as $produit)
                    <div>
                        <div class="basic-product theme-product-1">
                            <div class="overflow-hidden">
                                <div class="img-wrapper">
                                    <a href="{{ route('produits.single', $produit) }}"><img
                                            src="{{ asset($produit->image_main) }}"
                                            class="img-fluid blur-up lazyload" alt="{{ $produit->name }}" style="height: 250px; object-fit: cover;"></a>
                                    <div class="rating-label"><i class="ri-star-s-fill"></i> <span>4.5</span></div>
                                    <div class="cart-info">
                                        <a href="{{ route('wishlist.add', $produit) }}" title="Ajouter aux favoris" class="wishlist-icon">
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
                                        {{-- <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView" title="Aperçu rapide" class="quick-view-btn"
                                           data-produit="{{ json_encode([
                                               'name' => $produit->name,
                                               'slug' => $produit->slug,
                                               'price' => $produit->price,
                                               'original_price' => $produit->original_price,
                                               'description' => $produit->description,
                                               'image_main' => asset($produit->image_main),
                                               'images' => $produit->images ? $produit->images->map(fn($image) => ['url' => asset($image->url)])->toArray() : null
                                           ]) }}">
                                            <i class="ri-eye-line"></i>
                                        </a> --}}
                                        <a href="{{ route('produits.single', $produit) }}"  title="Voir le détail" class="quick-view-btn">
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
                                        <h6>{{ $produit->sousCategorie->label }}</h6>
                                        <h4 class="price">$ {{ number_format($produit->price, 2, '.', ',') }}
                                            @if ($produit->original_price)
                                                <del>$ {{ number_format($produit->original_price, 2, '.', ',') }}</del>
                                                <span class="discounted-price">
                                                    {{ round((($produit->original_price - $produit->price) / $produit->original_price) * 100) }}% Off
                                                </span>
                                            @endif
                                        </h4>
                                    </div>
                                    <ul class="offer-panel">
                                        <li><span class="offer-icon"><i class="ri-discount-percent-fill"></i></span>
                                            Catégorie : {{ $produit->sousCategorie->categorie->label }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Product slider end -->

    <!-- full banner -->
    <section class="py-5 section1" style="background-color: #1a1a1a;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="text-white p-4">
                        <span class="d-block mb-2 text-uppercase" style="letter-spacing: 3px; color: #b78d65;">Nouveautés Exclusives</span>
                        <h2 class="display-4 fw-light mb-3 position-relative" style="color: #f8f8f8;">
                            <span style="position: relative; z-index: 2;">Créez Votre<br>Histoire d'Intérieur</span>
                            <span style="position: absolute; top: 0; left: 0; color: #d4af37; z-index: 1; filter: blur(1px); opacity: 0.7;">Créez Votre<br>Histoire d'Intérieur</span>
                        </h2>
                        <p class="lead mb-4" style="color: #aaa;">
                            Redéfinissez votre espace avec des pièces uniques de notre nouvelle collection de meubles design.
                        </p>
                        <a href="{{ route('categories.page') }}" class="btn btn-outline-light btn-lg px-5 py-3 rounded-0">
                            Découvrir Maintenant <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/photo-banner.png') }}"
                         alt="Nouveaux meubles design"
                         class="img-fluid rounded shadow-lg"
                         style="max-height: 500px;">
                </div>
            </div>
        </div>
    </section>
    <!-- full banner end -->

    <!-- Tab product -->
    <div class="title1 section-t-space">
        <h2 class="title-inner1">Produits Populaires</h2>
    </div>
    <section class="section-b-space pt-0 ratio_asos">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="theme-tab">
                        <div class="tab-content-cls">
                            <div id="tab-4" class="tab-content active default">
                                <div class="g-3 g-md-4 row row-cols-2 row-cols-md-3 row-cols-xl-4">
                                    @foreach (FrontHelper::fourProductsPopulars() as $produit)
                                        <div>
                                            <div class="basic-product theme-product-1">
                                                <div class="overflow-hidden">
                                                    <div class="img-wrapper">
                                                        <a href="{{ route('produits.single', $produit) }}"><img
                                                                src="{{ asset($produit->image_main) }}"
                                                                class="img-fluid blur-up lazyload" alt="{{ $produit->name }}" style="height: 250px; object-fit: cover;"></a>
                                                        <div class="rating-label"><i class="ri-star-s-fill"></i> <span>4.5</span></div>
                                                        <div class="cart-info">
                                                            <a href="{{ route('wishlist.add', $produit) }}" title="Ajouter aux favoris" class="wishlist-icon">
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
                                                            {{-- <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView" title="Aperçu rapide" class="quick-view-btn"
                                                               data-produit="{{ json_encode([
                                                                   'name' => $produit->name,
                                                                   'slug' => $produit->slug,
                                                                   'price' => $produit->price,
                                                                   'original_price' => $produit->original_price,
                                                                   'description' => $produit->description,
                                                                   'image_main' => asset($produit->image_main),
                                                                   'images' => $produit->images ? $produit->images->map(fn($image) => ['url' => asset($image->url)])->toArray() : null
                                                               ]) }}">
                                                                <i class="ri-eye-line"></i>
                                                            </a> --}}
                                                            <a href="{{ route('produits.single', $produit) }}"  title="Voir le détail" class="quick-view-btn">
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
                                                            <h6>{{ $produit->sousCategorie->label }}</h6>
                                                            <h4 class="price">$ {{ number_format($produit->price, 2, '.', ',') }}
                                                                @if ($produit->original_price)
                                                                    <del>$ {{ number_format($produit->original_price, 2, '.', ',') }}</del>
                                                                    <span class="discounted-price">
                                                                        {{ round((($produit->original_price - $produit->price) / $produit->original_price) * 100) }}% Off
                                                                    </span>
                                                                @endif
                                                            </h4>
                                                        </div>
                                                        <ul class="offer-panel">
                                                            <li><span class="offer-icon"><i class="ri-discount-percent-fill"></i></span>
                                                                Catégorie : {{ $produit->sousCategorie->categorie->label }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Tab product end -->

    <!-- service layout -->
    <div class="container">
        <section class="service border-section small-section">
            <div class="row">
                <div class="col-md-4 service-block">
                    <div class="media">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="50" height="50">
                            <path
                                d="M416 48H96C43 48 0 91 0 144v224c0 53 43 96 96 96h320c53 0 96-43 96-96V144c0-53-43-96-96-96zm64 320c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V144c0-35.3 28.7-64 64-64h320c35.3 0 64 28.7 64 64v224zM352 176H160c-8.8 0-16 7.2-16 16s7.2 16 16 16h192c8.8 0 16-7.2 16-16s-7.2-16-16-16zm0 96H160c-8.8 0-16 7.2-16 16s7.2 16 16 16h192c8.8 0 16-7.2 16-16s-7.2-16-16-16z"
                                fill="#ec8951"/>
                        </svg>
                        <div class="media-body">
                            <h4>Paiement sécurisé</h4>
                            <p>Utilisez Wisapay pour effectuer vos paiements</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 service-block">
                    <div class="media">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="50" height="50">
                            <path
                                d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256 256-114.6 256-256S397.4 0 256 0zm0 464c-114.7 0-208-93.3-208-208S141.3 48 256 48s208 93.3 208 208-93.3 208-208 208zm16-144h-32c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-64c0-8.8-7.2-16-16-16zm0-256h-32c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V64c0-8.8-7.2-16-16-16z"
                                fill="#ec8951"/>
                        </svg>
                        <div class="media-body">
                            <h4>Support client 24/7</h4>
                            <p>Assistance disponible à tout moment</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 service-block">
                    <div class="media">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="50" height="50">
                            <path
                                d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256 256-114.6 256-256S397.4 0 256 0zm0 464c-114.7 0-208-93.3-208-208S141.3 48 256 48s208 93.3 208 208-93.3 208-208 208zm96-288H160c-8.8 0-16 7.2-16 16v128c0 8.8 7.2 16 16 16h192c8.8 0 16-7.2 16-16V192c0-8.8-7.2-16-16-16zm-96 128c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"
                                fill="#ec8951"/>
                        </svg>
                        <div class="media-body">
                            <h4>Produits de qualité</h4>
                            <p>Articles soigneusement sélectionnés pour votre satisfaction</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- service layout end -->

    <!-- instagram section -->
    <section class="instagram ratio_square">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <h2 class="title-borderless">Catégories</h2>
                    <div class="slide-7 no-arrow slick-instagram">
                        @foreach (FrontHelper::allCategories() as $categorie)
                            <div>
                                <a href="{{ route('categories.single', $categorie) }}">
                                    <div class="instagram-box">
                                        <img src="{{ asset($categorie->image) }}"
                                             class="bg-img" alt="{{ $categorie->label }}">
                                        <div class="overlay"><span style="font-size: 20px; color: white;">{{ $categorie->label }}</span></div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- instagram section end -->

    <!-- logo section -->
    <section class="section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="slide-6 no-arrow">
                        @foreach (FrontHelper::allPartners() as $partner)
                            <div>
                                <div class="logo-block">
                                    <a href="#!"><img src="{{ asset($partner->image) }}" style="height: 125px; object-fit: cover;" alt="{{ $partner->name }}"></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- logo section end -->

    <!-- Quick-view modal popup start -->
    {{-- <div class="modal fade theme-modal-2 quick-view-modal" id="quickView" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i>
                </button>
                <div class="modal-body">
                    <div class="wrap-modal-slider">
                        <div class="row g-sm-4 g-3">
                            <div class="col-lg-6">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="view-main-slider">
                                            <!-- Les images multiples seront injectées dynamiquement via JavaScript -->
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="view-thumbnail-slider no-arrow">
                                            <!-- Les miniatures seront injectées dynamiquement via JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="right-sidebar-modal">
                                    <a class="name" href="#"></a>
                                    <div class="product-rating">
                                        <ul class="rating-list">
                                            <li><i class="ri-star-line"></i></li>
                                            <li><i class="ri-star-line"></i></li>
                                            <li><i class="ri-star-line"></i></li>
                                            <li><i class="ri-star-line"></i></li>
                                            <li><i class="ri-star-line"></i></li>
                                        </ul>
                                        <div class="divider">|</div>
                                        <a href="#!">0 Avis</a>
                                    </div>
                                    <div class="price-text">
                                        <h3>
                                            <span class="fw-normal">Prix :</span>
                                            <span class="price"></span>
                                        </h3>
                                        <span class="text">Taxes incluses</span>
                                    </div>
                                    <p class="description-text"></p>
                                    <div class="qty-box">
                                        <div class="input-group qty-container">
                                            <button class="btn qty-btn-minus">
                                                <i class="ri-arrow-left-s-line"></i>
                                            </button>
                                            <input type="number" readonly name="qty" class="form-control input-qty" value="1">
                                            <button class="btn qty-btn-plus">
                                                <i class="ri-arrow-right-s-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="product-buy-btn-group">
                                        <button class="btn btn-animation btn-solid buy-button hover-solid scroll-button add-to-cart"
                                                data-product-id=""
                                                data-product-name=""
                                                data-product-image=""
                                                data-product-price=""
                                                data-product-original-price=""
                                                data-product-color=""
                                                data-product-confort=""
                                                data-product-poids="">
                                            <span class="d-inline-block ring-animation">
                                                <i class="ri-shopping-cart-line me-1"></i>
                                            </span>
                                            panier
                                        </button>
                                        <a href="#" class="btn btn-solid buy-button see-more">Voir plus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Quick-view modal popup end -->
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

                // Initialiser les événements du panier (pour delete-button et clear-cart dans l'offcanvas)
                cartUtils.attachCartEvents();

                // Gestion du bouton "Ajouter au panier" dans les sliders
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
                        quantity: 1 // Quantité par défaut
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

                // Gestion du modal d'aperçu rapide
                $('.quick-view-btn').on('click', function () {
                    const produit = JSON.parse($(this).attr('data-produit'));
                    const modal = $('#quickView');

                    // Mettre à jour le nom du produit
                    modal.find('.name').text(produit.name);
                    modal.find('.name').attr('href', `/produits/details/${produit.slug}`);

                    // Mettre à jour le prix
                    const priceText = modal.find('.price-text h3');
                    priceText.html(`
                        <span class="fw-normal">Prix :</span>
                        $ ${produit.price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}
                        ${produit.original_price ? `<del>$ ${produit.original_price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}</del> <span class="discounted-price">${Math.round(((produit.original_price - produit.price) / produit.original_price) * 100)}% de réduction</span>` : ''}
                    `);

                    // Mettre à jour la description (tronquée)
                    modal.find('.description-text').text(produit.description ? produit.description.substring(0, 100) + '...' : '');

                    // Mettre à jour le lien "Voir plus"
                    modal.find('.see-more').attr('href', `/produits/details/${produit.slug}`);

                    // Mettre à jour les données du bouton "Ajouter au panier" dans le modal
                    const buyButton = modal.find('.add-to-cart');
                    buyButton.data('product-id', produit.id || '');
                    buyButton.data('product-name', produit.name || '');
                    buyButton.data('product-image', produit.image_main || '');
                    buyButton.data('product-price', produit.price || '');
                    buyButton.data('product-original-price', produit.original_price || '');
                    buyButton.data('product-color', produit.color || '');
                    buyButton.data('product-confort', produit.niveau_confort || '');
                    buyButton.data('product-poids', produit.poids || '');

                    // Mettre à jour les images
                    const images = produit.images || [{ url: produit.image_main }];
                    const mainSlider = modal.find('.view-main-slider');
                    const thumbnailSlider = modal.find('.view-thumbnail-slider');
                    mainSlider.html('');
                    thumbnailSlider.html('');
                    images.forEach(image => {
                        mainSlider.append(`
                            <div>
                                <img src="${image.url}" class="img-fluid" alt="${produit.name}">
                            </div>
                        `);
                        thumbnailSlider.append(`
                            <div>
                                <div class="slider-image">
                                    <img src="${image.url}" class="img-fluid" alt="${produit.name}">
                                </div>
                            </div>
                        `);
                    });

                    // Réinitialiser la quantité
                    modal.find('.input-qty').val(1);

                    // Gestion des boutons de quantité dans le modal
                    modal.find('.qty-btn-minus').off('click').on('click', function () {
                        const qtyInput = $(this).closest('.qty-box').find('.input-qty');
                        let qty = parseInt(qtyInput.val());
                        if (qty > 1) {
                            qtyInput.val(qty - 1);
                        }
                    });

                    modal.find('.qty-btn-plus').off('click').on('click', function () {
                        const qtyInput = $(this).closest('.qty-box').find('.input-qty');
                        let qty = parseInt(qtyInput.val());
                        qtyInput.val(qty + 1);
                    });
                });

                // Gestion du bouton "Ajouter au panier" dans le modal
                $('#quickView .add-to-cart').on('click', function (e) {
                    e.preventDefault();
                    const $this = $(this);
                    const qtyInput = $this.closest('.right-sidebar-modal').find('.input-qty');
                    const productData = {
                        id: $this.data('product-id'),
                        name: $this.data('product-name'),
                        image_main: $this.data('product-image'),
                        price: parseFloat($this.data('product-price').toString().replace(/,/g, '')),
                        original_price: $this.data('product-original-price') ? parseFloat($this.data('product-original-price').toString().replace(/,/g, '')) : null,
                        color: $this.data('product-color'),
                        niveau_confort: $this.data('product-confort'),
                        poids: $this.data('product-poids'),
                        quantity: parseInt(qtyInput.val()) || 1
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
                            $('#quickView').modal('hide');
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

        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.carousel-slide');
            const indicators = document.querySelectorAll('.carousel-indicators .indicator');
            const prevButton = document.querySelector('.carousel-control.prev');
            const nextButton = document.querySelector('.carousel-control.next');
            let currentSlide = 0;
            const totalSlides = slides.length;

            // Fonction pour afficher une slide spécifique
            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                    indicators[i].classList.toggle('active', i === index);
                });
            }

            // Changer de slide automatiquement toutes les 5 secondes
            function autoSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }

            // Événements pour les boutons précédent/suivant
            prevButton.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(currentSlide);
            });

            nextButton.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            });

            // Événements pour les indicateurs
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    currentSlide = index;
                    showSlide(currentSlide);
                });
            });

            // Lancer le carrousel automatiquement
            showSlide(currentSlide);
            setInterval(autoSlide, 5000);
        });

        // JavaScript pour le compte à rebours
        function startCountdown() {
            const countdownDate = new Date().getTime() + 7 * 24 * 60 * 60 * 1000; // 7 jours à partir de maintenant

            const updateCountdown = setInterval(() => {
                const now = new Date().getTime();
                const distance = countdownDate - now;

                if (distance < 0) {
                    clearInterval(updateCountdown);
                    document.querySelector('.countdown-container').innerHTML = '<p>Offre expirée !</p>';
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('days').textContent = String(days).padStart(2, '0');
                document.getElementById('hours').textContent = String(hours).padStart(2, '0');
                document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
                document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
            }, 1000);
        }

        document.addEventListener('DOMContentLoaded', startCountdown);
    </script>
@endsection
