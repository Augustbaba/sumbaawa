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
