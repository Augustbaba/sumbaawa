@extends('front.layouts.master')
@section('title', $categorie->label)
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/price-range.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
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
                        <!-- side-bar collapse block stat -->
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
                                                {{ $categorie->label }} </button>
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
                        <!-- side-bar collapse block end here -->
                    </div>
                    <div class="collection-content col-xl-9 col-lg-8">
                        <div class="page-main-content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="filter-btn btn"><i class="ri-filter-fill"></i> Filtrer
                                    </button>
                                    <div class="collection-product-wrapper">
                                        <div class="product-top-filter mt-0">
                                            <div class="product-filter-content w-100">
                                                <div class="d-flex align-items-center gap-sm-3 gap-2">
                                                </div>
                                                <div class="collection-grid-view">
                                                    <ul>
                                                        <li class="product-2-layout-view grid-icon">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/inner-page/icon/2.png') }}" alt="sort"
                                                                class=" ">
                                                        </li>
                                                        <li class="product-3-layout-view grid-icon active">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/inner-page/icon/3.png') }}" alt="sort"
                                                                class=" ">
                                                        </li>
                                                        <li class="product-4-layout-view grid-icon">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/inner-page/icon/4.png') }}" alt="sort"
                                                                class=" ">
                                                        </li>
                                                        <li class="list-layout-view list-icon">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/inner-page/icon/list.png') }}"
                                                                alt="sort" class=" ">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Section pour les produits et pagination (chargée dynamiquement) -->
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
                                            <!-- Les images seront injectées dynamiquement via JavaScript -->
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
                                    <a class="name" href="#">Nom du produit</a>
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
                                            <!-- Prix injecté dynamiquement -->
                                        </h3>
                                        <span class="text">Taxes incluses</span>
                                    </div>
                                    <div class="description-wrapper">
                                        <p class="description-text"></p>
                                        <span class="see-more-btn" style="display: none;">Voir plus</span>
                                    </div>
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
                                        <a href="#" class="btn btn-solid buy-button">Voir plus</a>
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
    <!-- section End -->
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/price-range.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.subcategory-checkbox');
            const produitsSection = document.getElementById('produits-section');
            let currentSubcategories = []; // Stocker les sous-catégories actuelles

            function filterProduits(url = '{{ route('categories.filter', $categorie) }}') {
                const selectedSubcategories = Array.from(checkboxes)
                    .filter(cb => cb.checked && cb.value !== 'all')
                    .map(cb => cb.value);

                const isAllChecked = document.getElementById('checkbox-all').checked;

                // Si "Voir tout" est coché, inclure toutes les sous-catégories
                currentSubcategories = isAllChecked ? @json($categorie->sousCategories->pluck('id')) : selectedSubcategories;

                // Construire l'URL avec subcategories comme tableau
                const fetchUrl = new URL(url);
                currentSubcategories.forEach(id => fetchUrl.searchParams.append('subcategories[]', id));

                fetch(fetchUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau: ' + response.status);
                    }
                    return response.text();
                })
                .then(html => {
                    produitsSection.innerHTML = html;
                    // Réattacher les événements pour la pagination et modals
                    attachPaginationEvents();
                    attachModalEvents();
                    // Réinitialiser lazyload
                    // lazyloadImages();
                })
                .catch(error => console.error('Erreur lors du filtrage:', error));
            }

            // function lazyloadImages() {
            //     const lazyImages = document.querySelectorAll('img.lazyload');
            //     lazyImages.forEach(img => {
            //         if (img.dataset.src) {
            //             img.src = img.dataset.src;
            //             img.classList.remove('lazyload');
            //         }
            //     });
            // }

            function attachPaginationEvents() {
                const paginationLinks = document.querySelectorAll('.pagination a');
                paginationLinks.forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        filterProduits(this.href);
                    });
                });
            }

            function attachModalEvents() {
                document.querySelectorAll('.quick-view-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        let produit;
                        try {
                            produit = JSON.parse(this.getAttribute('data-produit'));
                        } catch (e) {
                            console.error('Erreur JSON dans data-produit:', e, this.getAttribute('data-produit'));
                            return;
                        }

                        const modal = document.querySelector('#quickView');
                        if (!modal) {
                            console.error('Modal #quickView non trouvé');
                            return;
                        }

                        // Mettre à jour le nom du produit
                        const nameElement = modal.querySelector('.name');
                        if (nameElement) {
                            nameElement.textContent = produit.name || 'Nom non disponible';
                            nameElement.href = produit.slug ? `/produits/details/${produit.slug}` : '#';
                        }

                        // Mettre à jour le prix
                        const priceText = modal.querySelector('.price-text h3');
                        if (priceText) {
                            priceText.innerHTML = `
                                <span class="fw-normal">Prix :</span>
                                $ ${produit.price || '0'}
                                ${produit.original_price ? `<del>$ ${produit.original_price}</del> <span class="discounted-price">${Math.round(((produit.original_price - produit.price) / produit.original_price) * 100)}% de réduction</span>` : ''}
                            `;
                        }

                        // Mettre à jour la description
                        const descriptionWrapper = modal.querySelector('.description-wrapper');
                        const descriptionText = modal.querySelector('.description-text');
                        const seeMoreBtn = modal.querySelector('.see-more-btn');
                        if (descriptionText && seeMoreBtn) {
                            const fullDescription = produit.description || 'Pas de description';
                            const truncatedDescription = fullDescription.length > 100 ? fullDescription.substring(0, 100) + '...' : fullDescription;
                            descriptionText.innerHTML = truncatedDescription; // Utiliser innerHTML pour gérer le HTML
                            descriptionText.classList.add('truncated');
                            seeMoreBtn.style.display = fullDescription.length > 100 ? 'block' : 'none'; // Afficher le bouton si nécessaire

                            // Supprimer les anciens écouteurs pour éviter les duplications
                            const newSeeMoreBtn = seeMoreBtn.cloneNode(true);
                            seeMoreBtn.parentNode.replaceChild(newSeeMoreBtn, seeMoreBtn);

                            newSeeMoreBtn.addEventListener('click', function () {
                                if (descriptionText.classList.contains('truncated')) {
                                    descriptionText.innerHTML = fullDescription;
                                    descriptionText.classList.remove('truncated');
                                    newSeeMoreBtn.textContent = 'Voir moins';
                                } else {
                                    descriptionText.innerHTML = truncatedDescription;
                                    descriptionText.classList.add('truncated');
                                    newSeeMoreBtn.textContent = 'Voir plus';
                                }
                            });
                        }

                        // Mettre à jour le lien "Voir plus"
                        const buyButton = modal.querySelector('.buy-button[href]');
                        if (buyButton) {
                            buyButton.href = produit.slug ? `/produits/details/${produit.slug}` : '#';
                        }

                        // Gérer les images
                        const images = produit.images && Array.isArray(produit.images) && produit.images.length > 0
                            ? produit.images
                            : [{ url: produit.image_main || '{{ asset('storage/front/assets/images/default-placeholder.png') }}' }];
                        const mainSlider = modal.querySelector('.view-main-slider');
                        const thumbnailSlider = modal.querySelector('.view-thumbnail-slider');

                        if (mainSlider && thumbnailSlider) {
                            // Détruire les sliders existants pour éviter les erreurs
                            if (typeof $.fn.slick !== 'undefined') {
                                $(mainSlider).slick('unslick');
                                $(thumbnailSlider).slick('unslick');
                            }

                            mainSlider.innerHTML = '';
                            thumbnailSlider.innerHTML = '';
                            images.forEach(image => {
                                if (image.url) {
                                    mainSlider.innerHTML += `
                                        <div>
                                            <img src="${image.url}" class="img-fluid" alt="${produit.name || 'Produit'}">
                                        </div>
                                    `;
                                    thumbnailSlider.innerHTML += `
                                        <div>
                                            <div class="slider-image">
                                                <img src="${image.url}" class="img-fluid" alt="${produit.name || 'Produit'}">
                                            </div>
                                        </div>
                                    `;
                                } else {
                                    console.warn('Image URL manquante:', image);
                                }
                            });

                            // Initialiser le slider
                            if (typeof $.fn.slick !== 'undefined') {
                                $(mainSlider).slick({
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    arrows: true,
                                    asNavFor: '.view-thumbnail-slider'
                                });
                                $(thumbnailSlider).slick({
                                    slidesToShow: 3,
                                    slidesToScroll: 1,
                                    asNavFor: '.view-main-slider',
                                    centerMode: true,
                                    focusOnSelect: true,
                                    arrows: false
                                });
                            }
                        } else {
                            console.error('Sliders non trouvés:', mainSlider, thumbnailSlider);
                        }
                    });
                });
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => filterProduits());
            });

            const allCheckbox = document.getElementById('checkbox-all');
            allCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    checkboxes.forEach(cb => {
                        if (cb.value !== 'all') cb.checked = false;
                    });
                }
                filterProduits();
            });

            checkboxes.forEach(cb => {
                if (cb.value !== 'all') {
                    cb.addEventListener('change', function () {
                        if (this.checked) {
                            allCheckbox.checked = false;
                        }
                        filterProduits();
                    });
                }
            });

            // Attacher les événements de pagination et modals au chargement initial
            attachPaginationEvents();
            attachModalEvents();
            // lazyloadImages();
        });
    </script>
@endsection
