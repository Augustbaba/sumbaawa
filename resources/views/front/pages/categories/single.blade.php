@extends('front.layouts.master')
@section('title', $categorie->label)
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/price-range.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <style>
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
        <a href="{{ route('categories.page') }}">Catégories</a>
    </li>
    <li class="breadcrumb-item active">{{ $categorie->label }}</li>
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
                                                {{ $categorie->label }}
                                            </button>
                                        </h2>
                                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <ul class="collection-listing">
                                                    <li>
                                                        <div class="form-check">
                                                            <input class="form-check-input subcategory-checkbox" type="checkbox" value="all" id="checkbox-all" checked>
                                                            <label class="form-check-label" for="checkbox-all">
                                                                Voir tout
                                                            </label>
                                                        </div>
                                                    </li>
                                                    @foreach ($categorie->sousCategories as $sousCategorie)
                                                        <li>
                                                            <div class="form-check">
                                                                <input class="form-check-input subcategory-checkbox" type="checkbox" value="{{ $sousCategorie->id }}" id="checkbox-{{ $sousCategorie->id }}">
                                                                <label class="form-check-label" for="checkbox-{{ $sousCategorie->id }}">
                                                                    {{ $sousCategorie->label }}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
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
                                            @include('front.partials.produits', ['produits' => $produits])
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
                function attachCartEventsToProducts() {
                    $('.add-to-cart').off('click').on('click', function (e) {
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
                }

                // Filtrage des produits
                const checkboxes = $('.subcategory-checkbox');
                const produitsSection = $('#produits-section');
                let currentSubcategories = [];

                function filterProduits(url = '{{ route('categories.filter', $categorie) }}') {
                    const selectedSubcategories = checkboxes.filter(':checked').map(function () {
                        return this.value !== 'all' ? this.value : null;
                    }).get().filter(Boolean);

                    const isAllChecked = $('#checkbox-all').is(':checked');
                    currentSubcategories = isAllChecked ? @json($categorie->sousCategories->pluck('id')) : selectedSubcategories;

                    const fetchUrl = new URL(url);
                    currentSubcategories.forEach(id => fetchUrl.searchParams.append('subcategories[]', id));

                    $.ajax({
                        url: fetchUrl,
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        },
                        success: function (html) {
                            produitsSection.html(html);
                            attachPaginationEvents();
                            attachCartEventsToProducts();
                        },
                        error: function (xhr) {
                            console.error('Erreur lors du filtrage:', xhr.responseText);
                        }
                    });
                }

                function attachPaginationEvents() {
                    $('.pagination a').off('click').on('click', function (e) {
                        e.preventDefault();
                        filterProduits(this.href);
                    });
                }

                checkboxes.on('change', function () {
                    if (this.value === 'all' && this.checked) {
                        checkboxes.not(this).prop('checked', false);
                    } else if (this.value !== 'all' && this.checked) {
                        $('#checkbox-all').prop('checked', false);
                    }
                    filterProduits();
                });

                // Attacher les événements au chargement initial
                attachPaginationEvents();
                attachCartEventsToProducts();
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
