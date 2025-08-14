@extends('front.layouts.master')
@section('title', $categorie->label)
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/price-range.css') }}">
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
                                                    @foreach ($categorie->sousCategories as $sousCategorie)
                                                        <li>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkbox1">
                                                                <label class="form-check-label" for="checkbox1">
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

                                    <button class="filter-btn btn"><i class="ri-filter-fill"></i> Filter
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

                                        <div class="product-wrapper-grid">
                                            <div class="row g-3 g-sm-4">
                                                @foreach ($categorie->sousCategories as $sousCategorie)
                                                    @foreach ($sousCategorie->produits as $produit)
                                                        <div class="col-xl-4 col-6 col-grid-box">
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
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="product-pagination">
                                            <div class="theme-paggination-block">
                                                <nav>
                                                    <ul class="pagination">
                                                        <li class="page-item">
                                                            <a class="page-link" href="#!" aria-label="Previous">
                                                                <span>
                                                                    <i class="ri-arrow-left-s-line"></i>
                                                                </span>
                                                                <span class="sr-only">Previous</span>
                                                            </a>
                                                        </li>
                                                        <li class="page-item active">
                                                            <a class="page-link" href="#!">1</a>
                                                        </li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="#!">2</a>
                                                        </li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="#!">3</a>
                                                        </li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="#!" aria-label="Next">
                                                                <span>
                                                                    <i class="ri-arrow-right-s-line"></i>
                                                                </span>
                                                                <span class="sr-only">Next</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
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
    <!-- section End -->
@endsection
@section('scripts')
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/price-range.js') }}"></script>
@endsection
