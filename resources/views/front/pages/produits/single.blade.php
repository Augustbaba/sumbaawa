@php
    use Illuminate\Support\Str;
    // Correspondance des couleurs pour affichage visuel
    $colorMap = [
        'Noir' => '#000000',
        'Bleu' => '#0000FF',
        'Gris' => '#808080',
        'Blanc' => '#FFFFFF',
        'Rouge' => '#FF0000',
        'Vert' => '#008000',
        'Jaune' => '#FFFF00',
    ];
@endphp

@extends('front.layouts.master')
@section('title', $produit->name)
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <style>
        .product-slick img, .slider-nav img {
            max-height: 400px;
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
        .color-option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .color-option input {
            margin-right: 10px;
        }
        .color-swatch {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            margin-left: 10px;
            border: 1px solid #ccc;
        }
    </style>
@endsection
@section('filAriane')
    <li class="breadcrumb-item"><a href="">Produits</a></li>
    <li class="breadcrumb-item"><a href="{{ route('categories.page') }}">{{ $produit->sousCategorie->categorie->label }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('categories.single', $produit->sousCategorie->categorie) }}">{{ $produit->sousCategorie->label }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $produit->name }}</li>
@endsection
@section('content')
    <!-- Section start -->
    <section>
        <div class="container">
            <div class="collection-wrapper">
                <div class="row g-sm-4">
                    <div class="col-lg-6">
                        <div class="product-slick">
                            <!-- Image principale -->
                            <div>
                                <img src="{{ asset($produit->image_main) }}" alt="{{ $produit->name }}"
                                     class="img-fluid blur-up lazyload image_zoom_cls-0">
                            </div>
                            <!-- Images secondaires -->
                            @foreach ($produit->images as $image)
                                <div>
                                    <img src="{{ asset($image->url) }}" alt="{{ $produit->name }}"
                                         class="img-fluid blur-up lazyload image_zoom_cls-{{ $loop->index + 1 }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="slider-nav">
                                    <!-- Miniature de l'image principale -->
                                    <div>
                                        <img src="{{ asset($produit->image_main) }}" alt="{{ $produit->name }}"
                                             class="img-fluid blur-up lazyload">
                                    </div>
                                    <!-- Miniatures des images secondaires -->
                                    @foreach ($produit->images as $image)
                                        <div>
                                            <img src="{{ asset($image->url) }}" alt="{{ $produit->name }}"
                                                 class="img-fluid blur-up lazyload">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 rtl-text position-relative">
                        <div class="product-page-details">
                            <h2 class="main-title">{{ $produit->name }}</h2>
                            <div class="product-rating">
                                <div class="rating-list">
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-fill"></i>
                                    <i class="ri-star-line"></i>
                                </div>
                                <span class="divider">|</span>
                                <a href="#!">0 Avis</a>
                            </div>
                            <div class="price-text">
                                <h3>
                                    <span class="fw-normal">Prix :</span> $ {{ number_format($produit->price, 2, '.', ',') }}
                                    @if ($produit->original_price)
                                        <del>$ {{ number_format($produit->original_price, 2, '.', ',') }}</del>
                                        <span class="discounted-price">
                                            {{ round((($produit->original_price - $produit->price) / $produit->original_price) * 100) }}% Off
                                        </span>
                                    @endif
                                </h3>
                                <span>Inclus toutes les taxes</span>
                            </div>
                            <div class="description-wrapper">
                                <p class="description-text">{!! Str::limit($produit->description, 100) !!}</p>
                                @if (strlen($produit->description) > 100)
                                    <span class="see-more-btn">Voir plus</span>
                                @endif
                            </div>
                            <div class="size-delivery-info">
                                <a href="#return" data-bs-toggle="modal"><i class="ri-truck-line"></i> Livraison & Retour</a>
                                <span></span>
                                <a href="#ask-question" data-bs-toggle="modal"><i class="ri-questionnaire-line"></i> Poser une question</a>
                            </div>
                            <div class="product-info">
                                <h4 class="sub-title">Informations sur le produit :</h4>
                                <ul class="shipping-info">
                                    <li><span>Catégorie :</span> {{ $produit->sousCategorie->categorie->label }}</li>
                                    <li><span>Sous-catégorie :</span> {{ $produit->sousCategorie->label }}</li>
                                    @if ($produit->color)
                                        <li>
                                            <span>Couleur :</span>
                                            <div class="color-options">
                                                @php
                                                    $colors = explode(',', $produit->color);
                                                @endphp
                                                @foreach ($colors as $index => $color)
                                                    <div class="color-option">
                                                        <input type="radio" name="color" value="{{ trim($color) }}"
                                                               id="color-{{ $index }}"
                                                               {{ $index === 0 ? 'checked' : '' }}>
                                                        <label for="color-{{ $index }}">{{ trim($color) }}</label>
                                                        @if (isset($colorMap[trim($color)]))
                                                            <span class="color-swatch" style="background-color: {{ $colorMap[trim($color)] }};"></span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </li>
                                    @else
                                        <li><span>Couleur :</span> Non spécifié</li>
                                    @endif
                                    @if ($produit->niveau_confort)
                                        <li><span>Niveau de confort :</span> {{ $produit->niveau_confort }}</li>
                                    @endif
                                    <li><span>Poids :</span> {{ $produit->poids }} kg</li>
                                    <li><span>Stock :</span> En stock</li>
                                </ul>
                            </div>
                            <div class="product-buttons">
                                <div class="d-flex align-items-center gap-3">
                                    <button class="btn btn-animation btn-solid hover-solid scroll-button add-to-cart"
                                            data-product-id="{{ $produit->id }}"
                                            data-product-name="{{ $produit->name }}"
                                            data-product-image="{{ asset($produit->image_main) }}"
                                            data-product-confort="{{ $produit->niveau_confort ?? '' }}"
                                            data-product-poids="{{ $produit->poids }}"
                                            data-product-price="{{ $produit->price }}">
                                        <i class="ri-shopping-cart-line me-1"></i> Ajouter au panier
                                    </button>
                                </div>
                            </div>
                            <div class="buy-box">
                                <a href="{{ route('wishlist.add', $produit) }}">
                                    <i class="ri-heart-{{ FrontHelper::isProductInFavorites($produit->id) ? 'fill' : 'line' }}"></i>
                                    <span>Ajouter au favoris</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Onglets pour description, avis, Q&A -->
            <div class="tab-product m-0">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab"
                                                    href="#top-home" role="tab" aria-selected="true"><i
                                        class="icofont icofont-ui-home"></i>Description</a></li>
                            <li class="nav-item"><a class="nav-link" id="review-top-tab" data-bs-toggle="tab"
                                                    href="#top-review" role="tab" aria-selected="false"><i
                                        class="icofont icofont-contacts"></i>Avis</a></li>
                            <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                                    href="#top-contact" role="tab" aria-selected="false"><i
                                        class="icofont icofont-contacts"></i>Questions & Réponses</a></li>
                        </ul>
                        <div class="tab-content nav-material" id="top-tabContent">
                            <div class="tab-pane fade show active" id="top-home" role="tabpanel"
                                 aria-labelledby="top-home-tab">
                                <div class="product-tab-description">
                                    <div class="part">
                                        <p>{!! $produit->description !!}</p>
                                    </div>
                                    <div class="part">
                                        <h5 class="inner-title">Caractéristiques :</h5>
                                        <ul>
                                            <li>Couleur : {{ $produit->color ?? 'Non spécifié' }}</li>
                                            @if ($produit->niveau_confort)
                                                <li>Niveau de confort : {{ $produit->niveau_confort }}</li>
                                            @endif
                                            <li>Poids : {{ $produit->poids }} kg</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                                <p>Aucun avis pour le moment.</p>
                            </div>
                            <div class="tab-pane fade" id="top-contact" role="tabpanel" aria-labelledby="contact-top-tab">
                                <p>Aucune question pour le moment.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Produits similaires -->
            <section class="section-b-space ratio_asos">
                <div class="container">
                    <div class="row">
                        <div class="col-12 product-related">
                            <h2>Produits similaires</h2>
                        </div>
                    </div>
                    <div class="product-5 product-m no-arrow">
                        @foreach ($relatedProduits as $related)
                            @php
                                $relatedColors = $related->color ? explode(',', $related->color) : [];
                                $defaultColor = $relatedColors[0] ?? '';
                            @endphp
                            <div class="basic-product theme-product-1">
                                <div class="overflow-hidden">
                                    <div class="img-wrapper">
                                        <a href="{{ route('produits.single', $related->slug) }}">
                                            <img src="{{ asset($related->image_main) }}"
                                                 class="img-fluid blur-up lazyload" alt="{{ $related->name }}">
                                        </a>
                                        <div class="rating-label"><i class="ri-star-fill"></i><span>4.5</span></div>
                                        <div class="cart-info">
                                            <ul class="hover-action">
                                                <li>
                                                    <button class="add-to-cart"
                                                            data-product-id="{{ $related->id }}"
                                                            data-product-name="{{ $related->name }}"
                                                            data-product-image="{{ asset($related->image_main) }}"
                                                            data-product-color="{{ $defaultColor }}"
                                                            data-product-confort="{{ $related->niveau_confort ?? '' }}"
                                                            data-product-poids="{{ $related->poids }}"
                                                            data-product-price="{{ $related->price }}"
                                                            title="Ajouter au panier">
                                                        <i class="ri-shopping-cart-line"></i>
                                                    </button>
                                                </li>
                                                <li>
                                                    <a href="{{ route('wishlist.add', $related) }}" title="Ajouter au favoris">
                                                        <i class="ri-heart-{{ FrontHelper::isProductInFavorites($related->id) ? 'fill' : 'line' }}"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('produits.single', $related->slug) }}" title="Voir le détail">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-detail">
                                        <div>
                                            <div class="brand-w-color">
                                                <a class="product-title" href="{{ route('produits.single', $related->slug) }}">
                                                    {{ $related->name }}
                                                </a>
                                            </div>
                                            <h6>{{ $related->sousCategorie->label }}</h6>
                                            <h4 class="price">$ {{ number_format($related->price, 2, '.', ',') }}
                                                @if ($related->original_price)
                                                    <del>$ {{ number_format($related->original_price, 2, '.', ',') }}</del>
                                                    <span class="discounted-price">
                                                        {{ round((($related->original_price - $related->price) / $related->original_price) * 100) }}% Off
                                                    </span>
                                                @endif
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- Section ends -->
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/jquery.elevatezoom.js') }}"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/timer.js') }}"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/cart.js') }}"></script>
    <script>
        // Charger jQuery 3.6.0 en dernier pour éviter les conflits
        window.jQuery || document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"><\/script>');
    </script>
    <script>
        (function($) {
            $(document).ready(function () {
                cartUtils.initializeCartCount();
                cartUtils.attachCartEvents();

                // Initialiser le slider principal et les miniatures
                try {
                    $('.product-slick').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: true,
                        asNavFor: '.slider-nav'
                    });
                    $('.slider-nav').slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        asNavFor: '.product-slick',
                        centerMode: true,
                        focusOnSelect: true,
                        arrows: false
                    });
                } catch (e) {
                    console.error('Erreur lors de l\'initialisation de Slick:', e);
                }

                // Gestion du bouton "Voir plus/Voir moins"
                $('.see-more-btn').click(function () {
                    const descriptionText = $('.description-text');
                    if (descriptionText.hasClass('expanded')) {
                        descriptionText.html('{!! addslashes(Str::limit($produit->description, 100)) !!}');
                        descriptionText.removeClass('expanded');
                        $(this).text('Voir plus');
                    } else {
                        descriptionText.html('{!! addslashes($produit->description) !!}');
                        descriptionText.addClass('expanded');
                        $(this).text('Voir moins');
                    }
                });

                // Ajouter au panier avec Toast
                $('.add-to-cart').on('click', function (e) {
                    e.preventDefault();
                    const $this = $(this);
                    const selectedColor = $this.closest('.product-buttons').length
                        ? $('input[name="color"]:checked').val() || ''
                        : $this.data('product-color'); // Utiliser la couleur par défaut pour produits similaires
                    const productData = {
                        id: $this.data('product-id'),
                        name: $this.data('product-name'),
                        image_main: $this.data('product-image'),
                        color: selectedColor,
                        niveau_confort: $this.data('product-confort'),
                        poids: $this.data('product-poids'),
                        price: parseFloat($this.data('product-price').toString().replace(/,/g, '')),
                        quantity: $this.closest('.product-buttons').length
                            ? parseInt($this.closest('.product-buttons').find('.input-number').val()) || 1
                            : 1 // Quantité par défaut pour produits similaires
                    };

                    $.ajax({
                        url: '{{ route('cart.add') }}',
                        method: 'POST',
                        data: {
                            product: productData
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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

                // Success message with SweetAlert2
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
        })(jQuery);
    </script>
@endsection
