@extends('front.layouts.master')
@section('title', 'Accueil')
@section('styles')
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
                                        <a href="#!" title="Ajouter aux favoris" class="wishlist-icon">
                                            <i class="ri-heart-line"></i>
                                        </a>
                                        <button data-bs-toggle="modal" data-bs-target="#addtocart" title="Ajouter au panier">
                                            <i class="ri-shopping-cart-line"></i>
                                        </button>
                                        <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView" title="Aperçu rapide" class="quick-view-btn" data-produit="{{ json_encode($produit) }}">
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
                                        <h4 class="price">$ {{ $produit->price }}</h4>
                                    </div>
                                    <ul class="offer-panel">
                                        <li><span class="offer-icon"><i class="ri-discount-percent-fill"></i></span>
                                            Catégorie : {{ $produit->sousCategorie->categorie->label }}</li>
                                        <li><span class="offer-icon"><i class="ri-discount-percent-fill"></i></span>
                                            Catégorie : {{ $produit->sousCategorie->categorie->label }}</li>
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
        {{-- <h4>exclusive products</h4> --}}
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
                                                            <a href="#!" title="Ajouter aux favoris" class="wishlist-icon">
                                                                <i class="ri-heart-line"></i>
                                                            </a>
                                                            <button data-bs-toggle="modal" data-bs-target="#addtocart" title="Ajouter au panier">
                                                                <i class="ri-shopping-cart-line"></i>
                                                            </button>
                                                            <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView" title="Aperçu rapide" class="quick-view-btn" data-produit="{{ json_encode($produit) }}">
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
                                                            <h4 class="price">$ {{ $produit->price }}</h4>
                                                        </div>
                                                        <ul class="offer-panel">
                                                            <li><span class="offer-icon"><i class="ri-discount-percent-fill"></i></span>
                                                                Catégorie : {{ $produit->sousCategorie->categorie->label }}</li>
                                                            <li><span class="offer-icon"><i class="ri-discount-percent-fill"></i></span>
                                                                Catégorie : {{ $produit->sousCategorie->categorie->label }}</li>
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
    <!-- service layout  end -->


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
                                    <div class="instagram-box"> <img src="{{ asset($categorie->image) }}"
                                            class="bg-img" alt="img">
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


    <!--  logo section -->
    <section class="section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="slide-6 no-arrow">
                        @foreach (FrontHelper::allPartners() as $partner)
                            <div>
                                <div class="logo-block">
                                    <a href="#!"><img src="{{ asset($partner->image) }}" style="height: 125px; object-fit: cover;" alt=""></a>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--  logo section end-->

    <!-- Quick-view modal popup start -->
    <div class="modal fade theme-modal-2 quick-view-modal" id="quickView" tabindex="-1" role="dialog">
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
                                            <!-- Les images multiples seront injectées dynamiquement via JavaScript ou un backend -->
                                            @foreach ($produit->images as $image)
                                                <div>
                                                    <img src="{{ asset($image->path) }}" class="img-fluid" alt="{{ $produit->name }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="view-thumbnail-slider no-arrow">
                                            @foreach ($produit->images as $image)
                                                <div>
                                                    <div class="slider-image">
                                                        <img src="{{ asset($image->path) }}" class="img-fluid" alt="{{ $produit->name }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="right-sidebar-modal">
                                    <a class="name" href="{{ route('produits.single', $produit) }}">{{ $produit->name }}</a>
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
                                            $ {{ $produit->price }}
                                            @if ($produit->original_price)
                                                <del>$ {{ $produit->original_price }}</del>
                                                <span class="discounted-price">{{ round((($produit->original_price - $produit->price) / $produit->original_price) * 100) }}% de réduction</span>
                                            @endif
                                        </h3>
                                        <span class="text">Taxes incluses</span>
                                    </div>
                                    <p class="description-text">{{ Str::limit($produit->description, 100, '...') }}</p>
                                    <div class="qty-box">
                                        <div class="input-group qty-container">
                                            <button class="btn qty-btn-minus">
                                                <i class="ri-arrow-left-s-line"></i>
                                            </button>
                                            <input type="number" readonly="" name="qty" class="form-control input-qty" value="1">
                                            <button class="btn qty-btn-plus">
                                                <i class="ri-arrow-right-s-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="product-buy-btn-group">
                                        <button class="btn btn-animation btn-solid buy-button hover-solid scroll-button">
                                            <span class="d-inline-block ring-animation">
                                                <i class="ri-shopping-cart-line me-1"></i>
                                            </span>
                                            Ajouter au panier
                                        </button>
                                        <a href="{{ route('produits.single', $produit) }}" class="btn btn-solid buy-button">Voir plus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick-view modal popup end -->
@endsection
@section('scripts')
    <script>
        document.querySelectorAll('.quick-view-btn').forEach(button => {
            button.addEventListener('click', function () {
                const produit = JSON.parse(this.getAttribute('data-produit'));
                const modal = document.querySelector('#quickView');

                // Mettre à jour le nom du produit
                modal.querySelector('.name').textContent = produit.name;
                modal.querySelector('.name').href = `/produits/${produit.slug}`;

                // Mettre à jour le prix
                const priceText = modal.querySelector('.price-text h3');
                priceText.innerHTML = `
                    <span class="fw-normal">Prix :</span>
                    $ ${produit.price}
                    ${produit.original_price ? `<del>$ ${produit.original_price}</del> <span class="discounted-price">${Math.round(((produit.original_price - produit.price) / produit.original_price) * 100)}% de réduction</span>` : ''}
                `;

                // Mettre à jour la description (tronquée)
                modal.querySelector('.description-text').textContent = produit.description ? produit.description.substring(0, 100) + '...' : '';

                // Mettre à jour le lien "Voir plus"
                modal.querySelector('.buy-button[href]').href = `/produits/${produit.slug}`;

                // Simuler les images multiples (à adapter selon votre structure de données)
                const images = produit.images || [{ path: produit.image_main }]; // Fallback sur image_main si pas d'images multiples
                const mainSlider = modal.querySelector('.view-main-slider');
                const thumbnailSlider = modal.querySelector('.view-thumbnail-slider');
                mainSlider.innerHTML = '';
                thumbnailSlider.innerHTML = '';
                images.forEach(image => {
                    mainSlider.innerHTML += `
                        <div>
                            <img src="${image.path}" class="img-fluid" alt="${produit.name}">
                        </div>
                    `;
                    thumbnailSlider.innerHTML += `
                        <div>
                            <div class="slider-image">
                                <img src="${image.path}" class="img-fluid" alt="${produit.name}">
                            </div>
                        </div>
                    `;
                });
            });
        });
    </script>

@endsection
