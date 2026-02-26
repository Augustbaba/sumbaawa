<div class="product-wrapper-grid">
    <div class="row g-3 g-sm-4">
        @forelse ($produits as $produit)
            <div class="col-xl-4 col-6 col-grid-box">
                <div class="basic-product theme-product-1">
                    <div class="overflow-hidden">
                        <div class="img-wrapper">
                            <a href="{{ route('produits.single', $produit) }}">
                                <img src="{{ asset($produit->image_main) }}"
                                     class="img-fluid blur-up lazyload"
                                     alt="{{ $produit->name }}"
                                     style="height: 250px; object-fit: cover;">
                            </a>
                            <div class="rating-label"><i class="ri-star-s-fill"></i> <span>4.5</span></div>
                            <div class="cart-info">
                                <a href="{{ route('wishlist.add', $produit) }}"
                                   title="Ajouter aux favoris"
                                   class="wishlist-icon">
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
                                <a href="{{ route('produits.single', $produit) }}"
                                   title="Voir le détail"
                                   class="quick-view-btn">
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
                                <h4 class="price"
                                    data-price="{{ $produit->price }}"
                                    data-original-price="{{ $produit->original_price ?? 0 }}">
                                    {{ FrontHelper::format_currency($produit->price) }}
                                    @if ($produit->original_price)
                                        <del>{{ FrontHelper::format_currency($produit->original_price) }}</del>
                                        <span class="discounted-price">
                                            {{ round((($produit->original_price - $produit->price) / $produit->original_price) * 100) }}% Off
                                        </span>
                                    @endif
                                </h4> <br>
                                <button class="btn btn-solid btn-sm w-100 mt-2 add-to-cart"
                                        data-redirect="{{ route('checkout') }}"
                                        data-product-id="{{ $produit->id }}"
                                        data-product-name="{{ $produit->name }}"
                                        data-product-image="{{ asset($produit->image_main) }}"
                                        data-product-price="{{ $produit->price }}"
                                        data-product-original-price="{{ $produit->original_price ?? '' }}"
                                        data-product-color="{{ $produit->color ?? '' }}"
                                        data-product-confort="{{ $produit->niveau_confort ?? '' }}"
                                        data-product-poids="{{ $produit->poids }}"
                                        title="Commander">
                                    <i class="ri-shopping-bag-line me-1"></i> Commander
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="ri-information-line"></i>
                    <p class="mb-0">Aucun produit trouvé dans cette catégorie.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<div class="product-pagination">
    <div class="theme-paggination-block">
        {{ $produits->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>

{{-- Script pour initialiser les prix après le chargement AJAX --}}
<script>
    (function() {
        // Fonction pour mettre à jour les prix (sera appelée après chargement AJAX)
        if (typeof CurrencyHelper !== 'undefined' && CurrencyHelper.load) {
            CurrencyHelper.load().then(() => {
                document.querySelectorAll('[data-price]').forEach(function(element) {
                    const priceInXOF = parseFloat(element.dataset.price);
                    const originalPrice = element.dataset.originalPrice;

                    if (!isNaN(priceInXOF)) {
                        // Formater le prix principal
                        const formattedPrice = CurrencyHelper.format(priceInXOF);

                        // Construire le HTML du prix
                        let priceHtml = formattedPrice;

                        // Ajouter le prix original barré si disponible
                        if (originalPrice && parseFloat(originalPrice) > 0) {
                            const originalPriceNum = parseFloat(originalPrice);
                            const formattedOriginalPrice = CurrencyHelper.format(originalPriceNum);
                            const discount = Math.round(((originalPriceNum - priceInXOF) / originalPriceNum) * 100);
                            priceHtml += ` <del>${formattedOriginalPrice}</del>`;
                            priceHtml += ` <span class="discounted-price">${discount}% Off</span>`;
                        }

                        element.innerHTML = priceHtml;
                    }
                });
            });
        }
    })();
</script>
