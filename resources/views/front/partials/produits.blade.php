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
                                <a href="#!" title="Ajouter aux favoris" class="wishlist-icon">
                                    <i class="ri-heart-line"></i>
                                </a>
                                <button data-bs-toggle="modal" data-bs-target="#addtocart" title="Ajouter au panier">
                                    <i class="ri-shopping-cart-line"></i>
                                </button>
                                <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView" title="Aperçu rapide" class="quick-view-btn"
                                   data-produit="{{ json_encode([
                                       'name' => $produit->name,
                                       'slug' => $produit->slug,
                                       'price' => $produit->price,
                                       'original_price' => $produit->original_price,
                                       'description' => $produit->description,
                                       'image_main' => $produit->image_main ? asset($produit->image_main) : asset('storage/front/assets/images/stop.png'),
                                       'images' => $produit->images ? $produit->images->map(fn($image) => ['url' => asset($image->url)])->toArray() : null
                                   ]) }}">
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
                                <h4 class="price">$ {{ $produit->price }}</h4>
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
