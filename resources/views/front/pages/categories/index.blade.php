@extends('front.layouts.master')
@section('title', 'Catégories')
@section('styles')
<style>
.product-box {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.img-wrapper {
    position: relative; /* Nécessaire pour positionner le label au survol */
    overflow: hidden; /* Empêche l'image de déborder */
}

.img-wrapper img {
    width: 100%;
    height: 200px; /* Hauteur fixe pour uniformité */
    object-fit: cover; /* Assure que l'image remplit le conteneur sans déformation */
    display: block;
}

.label-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6); /* Fond semi-transparent */
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0; /* Masqué par défaut */
    transition: opacity 0.3s ease; /* Animation fluide */
}

.label-overlay h6 {
    color: #fff;
    font-size: 16px;
    text-align: center;
    margin: 0;
    padding: 10px;
}

.img-wrapper:hover .label-overlay {
    opacity: 1; /* Affiche le label au survol */
}


</style>
@endsection
@section('filAriane')
    <li class="breadcrumb-item active">Catégories</li>
@endsection
@section('content')
<!-- section start -->
<section class="section-b-space">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="collection-content col">
                    <div class="page-main-content">
                        <div class="collection-product-wrapper mt-3">
                            <div class="portfolio-section metro-section port-col">
                                <div class="row">
                                    @foreach($categories as $category)
                                        <div class="col-lg-3 col-sm-6 mt-4">
                                            <div class="product-box">
                                                <div class="img-wrapper">
                                                    <div class="front">
                                                        <a href="{{ route('categories.single', $category) }}">
                                                            <img src="{{ asset($category->image) }}"
                                                                 class="img-fluid blur-up lazyload" alt="{{ $category->label }}">
                                                            <div class="label-overlay">
                                                                <h6>{{ $category->label }}</h6>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                                {{-- <div class="product-detail">
                                                    <a href="{{ route('categories.single', $category) }}">
                                                        <h6>{{ $category->label }}</h6>
                                                    </a>
                                                </div> --}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="product-pagination">
                                <div class="theme-paggination-block">
                                    {{ $categories->links('pagination::bootstrap-5') }}
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
