<!-- Search Modal Start -->
    <div class="modal fade search-modal theme-modal-2" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5">Rechercher dans la boutique</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="search-input-box">
                        <form action="{{ route('search') }}" class="" method="get">
                            <input type="text" class="form-control" name="q" required placeholder="Rechercher par produit ou par catégorie...">
                            <button type="submit" class="btn"><i class="ri-search-2-line"></i></button>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- Search Modal End -->


    <div class="offcanvas offcanvas-end cart-offcanvas" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header">
            <h3 class="offcanvas-title">Mon panier (<span id="cart-item-count">{{ count(session('cart', [])) }}</span>)</h3>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            @php
                $cart = session('cart', []);
                $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
                $freeShippingThreshold = 50;
                $progressPercentage = min(($total / $freeShippingThreshold) * 100, 100);
                $isCartEmpty = empty($cart);
            @endphp

            <div class="sidebar-title">
                <a href="{{ route('cart.clear') }}" class="clear-cart {{ $isCartEmpty ? 'disabled' : '' }}">Vider le panier</a>
            </div>
            <div class="cart-media">
                <ul class="cart-product">
                    @forelse ($cart as $item)
                        <li>
                            <div class="media">
                                <a href="{{ route('produits.single', Str::slug($item['name'])) }}">
                                    <img src="{{ $item['image_main'] }}" class="img-fluid" alt="{{ $item['name'] }}">
                                </a>
                                <div class="media-body">
                                    <a href="{{ route('produits.single', Str::slug($item['name'])) }}">
                                        <h4>{{ $item['name'] }}</h4>
                                    </a>
                                    <h4 class="quantity">
                                        <span>{{ $item['quantity'] }} x ${{ number_format($item['price'], 2) }}</span>
                                    </h4>
                                    <div class="qty-box">
                                        <div class="input-group qty-container">
                                            <button class="btn qty-btn-minus" data-product-id="{{ $item['id'] }}">
                                                <i class="ri-subtract-line"></i>
                                            </button>
                                            <input type="number" readonly name="qty" class="form-control input-qty" value="{{ $item['quantity'] }}">
                                            <button class="btn qty-btn-plus" data-product-id="{{ $item['id'] }}">
                                                <i class="ri-add-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="close-circle">
                                        <button class="close_button delete-button" data-product-id="{{ $item['id'] }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li><p>Le panier est vide.</p></li>
                    @endforelse
                </ul>
                <ul class="cart_total">
                    <li>
                        <div class="total">
                            <h5>Sous-total : <span>${{ number_format($total, 2) }}</span></h5>
                        </div>
                    </li>
                    <li>
                        <div class="buttons">
                            <a href="{{ route('cart.view') }}" class="btn view-cart {{ $isCartEmpty ? 'disabled' : '' }}">Voir le panier</a>
                            <a href="{{ route('checkout') }}" class="btn checkout {{ $isCartEmpty ? 'disabled' : '' }}">Passer la commande</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <div class="modal fade theme-modal-2 variation-modal" id="variationModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i>
                </button>
                <div class="modal-body">
                    <div class="product-right product-page-details variation-title">
                        <h2 class="main-title">
                            <a href="product-page(accordian).html">Cami Tank Top (Blue)</a>
                        </h2>
                        <h3 class="price-detail">$14.25 <span>5% off</span></h3>
                    </div>
                    <div class="variation-box">
                        <h4 class="sub-title">Color:</h4>
                        <ul class="quantity-variant color">
                            <li class="bg-light">
                                <span style="background-color: rgb(240, 0, 0);"></span>
                            </li>
                            <li class="bg-light">
                                <span style="background-color: rgb(47, 147, 72);"></span>
                            </li>
                            <li class="bg-light active">
                                <span style="background-color: rgb(0, 132, 255);"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="variation-qty-button">
                        <div class="qty-section">
                            <div class="qty-box">
                                <div class="input-group qty-container">
                                    <button class="btn qty-btn-minus">
                                        <i class="ri-subtract-line"></i>
                                    </button>
                                    <input type="number" readonly name="qty" class="form-control input-qty" value="1">
                                    <button class="btn qty-btn-plus">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="product-buttons">
                            <button class="btn btn-animation btn-solid hover-solid scroll-button"
                                id="replacecartbtnVariation14" type="submit" data-bs-dismiss="modal">
                                <i class="ri-shopping-cart-line me-1"></i>
                                Update Item
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart Offcanvas End -->


    <!-- cookie bar start -->
    {{-- <div class="cookie-bar">
        <p>Nous utilisons des cookies pour améliorer notre site et votre expérience d'achat. En poursuivant votre navigation, vous acceptez notre politique en matière de cookies.</p>
        <a href="#!" class="btn btn-solid btn-xs">Accepter</a>
        <a href="#!" class="btn btn-solid btn-xs">Refuser</a>
    </div> --}}
    <!-- cookie bar end -->

    <!--modal popup start-->
    {{-- <div class="modal fade bd-example-modal-lg theme-modal" id="exampleModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body modal1">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="modal-bg">
                                    <button type="button" class="btn-close"
                                        data-bs-dismiss="modal"><span>&times;</span></button>
                                    <div class="offer-content">
                                        <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/Offer-banner.png') }}"
                                            class="img-fluid blur-up lazyload" alt="Bannière d'offre">
                                        <h2>Newsletter</h2>
                                        <form
                                            action="https://pixelstrap.us19.list-manage.com/subscribe/post?u=5a128856334b598b395f1fc9b&amp;id=082f74cbda"
                                            class="auth-form needs-validation" method="post"
                                            id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                                            target="_blank">
                                            <div class="form-group mx-sm-3">
                                                <input type="email" class="form-control" name="EMAIL" id="mce-EMAIL"
                                                    placeholder="Entrez votre email" required="required">
                                                <button type="submit" class="btn btn-solid"
                                                    id="mc-submit">S'abonner</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!--modal popup end-->








    <!-- exit modal popup start-->
    {{-- <div class="modal fade bd-example-modal-lg theme-modal exit-modal" id="exit_popup" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body modal1">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="modal-bg">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                                        <i class="ri-close-line"></i>
                                    </button>
                                    <div class="media">
                                        <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/stop.png') }}"
                                            class="stop img-fluid blur-up lazyload me-3" alt="Arrêt">
                                        <div class="media-body text-start align-self-center">
                                            <div>
                                                <h2>Attendez !</h2>
                                                <h4>Nous vous offrons
                                                    <b>10% de réduction</b>
                                                    <span>pour votre première commande</span>
                                                </h4>
                                                <h5>Utilisez le code de réduction à la caisse</h5>
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
    </div> --}}
    <!-- Add to cart modal popup end-->


    <!-- facebook chat section start -->
    <!-- <div id="fb-root"></div>
    <script>
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src =
                'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script> -->
    <!-- Your customer chat code -->
    <!-- <div class="fb-customerchat" attribution=setup_tool page_id="2123438804574660" theme_color="#0084ff"
        logged_in_greeting="Hi! Welcome to PixelStrap Themes  How can we help you?"
        logged_out_greeting="Hi! Welcome to PixelStrap Themes  How can we help you?">
    </div> -->
    <!-- facebook chat section end -->


    <!-- tap to top -->
    <div class="tap-top top-cls">
        <div>
            <i class="ri-arrow-up-double-line"></i>
        </div>
    </div>
    <!-- tap to top end -->

    <!-- latest jquery-->
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/jquery-3.3.1.min.js') }}"></script>

    <!-- fly cart ui jquery-->
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/jquery-ui.min.js') }}"></script>

    <!-- exitintent jquery-->
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/jquery.exitintent.js') }}"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/exit.js') }}"></script>

    <!-- slick js-->
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/slick.js') }}"></script>

    <!-- menu js-->
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/menu.js') }}"></script>

    <!-- lazyload js-->
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/lazysizes.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Bootstrap Notification js-->
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/bootstrap-notify.min.js') }}"></script>

    <!-- Fly cart js-->
    {{-- <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/fly-cart.js') }}"></script> --}}

    <!-- Theme js-->
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/theme-setting.js') }}"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/script.js') }}"></script>
    {{-- <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/cart.js') }}"></script> --}}
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/jquery.elevatezoom.js') }}"></script>
    {{-- <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/timer.js') }}"></script> --}}

    <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('#exampleModal').modal('show');
            }, 2500);
        });


    </script>
    <script>
        // Charger jQuery 3.6.0 si nécessaire
        window.jQuery || document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"><\/script>');
    </script>
    <script>
        (function($) {
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Initialiser le compteur du panier
                $.ajax({
                    url: '{{ route('cart.get') }}',
                    method: 'GET',
                    success: function (response) {
                        const itemCount = response.cart.items ? response.cart.items.length : 0;
                        $('.cart_qty_cls').text(itemCount);
                    },
                    error: function (xhr) {
                        console.error('Erreur lors de la récupération du panier:', xhr.responseText);
                    }
                });
            });
        })(jQuery);
    </script>
    @yield('scripts')


</body>


</html>
