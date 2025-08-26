<!-- header start -->
    <header>
        <div class="top-header">
            <div class="mobile-fix-option"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="header-contact">
                            <ul>
                                <li>Bienvenue sur {{ FrontHelper::getAppName() }}</li>
                                <li><i class="ri-phone-fill"></i>Nous Contacter: 123 - 456 - 7890</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 text-end">
                        <ul class="header-dropdown">
                            <li class="mobile-wishlist"><a href="{{ route('wishlist.my') }}"><i class="ri-heart-fill"></i></a>
                            </li>
                            <li class="onhover-dropdown mobile-account"> <i class="ri-user-fill"></i>
                                Mon Compte
                                <ul class="onhover-show-div">
                                    @auth()
                                        <li><a href="{{ route('dashboard') }}" target="_blank">Tableau de bord</a></li>
                                        <li><a href="{{ route('logout') }}">Se Déconnecter</a></li>
                                    @endauth
                                    @guest()
                                        <li><a href="{{ route('login') }}" target="_blank">Se Connecter</a></li>
                                        <li><a href="{{ route('register') }}" target="_blank">S'inscrire</a></li>
                                    @endguest
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="main-menu">
                        <div class="menu-left">
                            {{-- <div class="navbar">
                                <a href="#!" onclick="openNav()">
                                    <div class="bar-style"><i class="ri-bar-chart-horizontal-line sidebar-bar"></i>
                                    </div>
                                </a>
                                <div id="mySidenav" class="sidenav">
                                    <a href="#!" class="sidebar-overlay" onclick="closeNav()"></a>
                                    <nav>
                                        <div onclick="closeNav()">
                                            <div class="sidebar-back text-start"><i
                                                    class="ri-arrow-left-s-line pe-2"></i>
                                                Retour</div>
                                        </div>
                                        <ul id="sub-menu" class="sm pixelstrap sm-vertical">
                                            <li> <a href="#!">clothing</a>
                                                <ul class="mega-menu clothing-menu">
                                                    <li>
                                                        <div class="row m-0">
                                                            <div class="col-xl-4">
                                                                <div class="link-section">
                                                                    <h5>women's fashion</h5>
                                                                    <ul>
                                                                        <li><a href="#!">dresses</a></li>
                                                                        <li><a href="#!">skirts</a></li>
                                                                        <li><a href="#!">western wear</a></li>
                                                                        <li><a href="#!">ethic wear</a></li>
                                                                        <li><a href="#!">sport wear</a></li>
                                                                    </ul>
                                                                    <h5>men's fashion</h5>
                                                                    <ul>
                                                                        <li><a href="#!">sports wear</a></li>
                                                                        <li><a href="#!">western wear</a></li>
                                                                        <li><a href="#!">ethic wear</a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4">
                                                                <div class="link-section">
                                                                    <h5>accessories</h5>
                                                                    <ul>
                                                                        <li><a href="#!">fashion jewellery</a>
                                                                        </li>
                                                                        <li><a href="#!">caps and hats</a></li>
                                                                        <li><a href="#!">precious jewellery</a>
                                                                        </li>
                                                                        <li><a href="#!">necklaces</a></li>
                                                                        <li><a href="#!">earrings</a></li>
                                                                        <li><a href="#!">wrist wear</a></li>
                                                                        <li><a href="#!">ties</a></li>
                                                                        <li><a href="#!">cufflinks</a></li>
                                                                        <li><a href="#!">pockets squares</a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4">
                                                                <a href="#!" class="mega-menu-banner"><img
                                                                        src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/mega-menu/fashion.jpg') }}"
                                                                        alt="" class="img-fluid blur-up lazyload"></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li> <a href="#!">bags</a>
                                                <ul>
                                                    <li><a href="#!">shopper bags</a></li>
                                                    <li><a href="#!">laptop bags</a></li>
                                                    <li><a href="#!">clutches</a></li>
                                                    <li> <a href="#!">purses</a>
                                                        <ul>
                                                            <li><a href="#!">purses</a></li>
                                                            <li><a href="#!">wallets</a></li>
                                                            <li><a href="#!">leathers</a></li>
                                                            <li><a href="#!">satchels</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li> <a href="#!">bags</a>
                                                <ul>
                                                    <li><a href="#!">shopper bags</a></li>
                                                    <li><a href="#!">laptop bags</a></li>
                                                    <li><a href="#!">clutches</a></li>
                                                    <li> <a href="#!">purses</a>
                                                        <ul>
                                                            <li><a href="#!">purses</a></li>
                                                            <li><a href="#!">wallets</a></li>
                                                            <li><a href="#!">leathers</a></li>
                                                            <li><a href="#!">satchels</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li> <a href="#!">bags</a>
                                                <ul>
                                                    <li><a href="#!">shopper bags</a></li>
                                                    <li><a href="#!">laptop bags</a></li>
                                                    <li><a href="#!">clutches</a></li>
                                                    <li> <a href="#!">purses</a>
                                                        <ul>
                                                            <li><a href="#!">purses</a></li>
                                                            <li><a href="#!">wallets</a></li>
                                                            <li><a href="#!">leathers</a></li>
                                                            <li><a href="#!">satchels</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li> <a href="#!">footwear</a>
                                                <ul>
                                                    <li><a href="#!">sport shoes</a></li>
                                                    <li><a href="#!">formal shoes</a></li>
                                                    <li><a href="#!">casual shoes</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#!">watches</a></li>
                                            <li> <a href="#!">Accessories</a>
                                                <ul>
                                                    <li><a href="#!">fashion jewellery</a></li>
                                                    <li><a href="#!">caps and hats</a></li>
                                                    <li><a href="#!">precious jewellery</a></li>
                                                    <li> <a href="#!">more..</a>
                                                        <ul>
                                                            <li><a href="#!">necklaces</a></li>
                                                            <li><a href="#!">earrings</a></li>
                                                            <li><a href="#!">wrist wear</a></li>
                                                            <li> <a href="#!">accessories</a>
                                                                <ul>
                                                                    <li><a href="#!">ties</a></li>
                                                                    <li><a href="#!">cufflinks</a></li>
                                                                    <li><a href="#!">pockets squares</a></li>
                                                                    <li><a href="#!">helmets</a></li>
                                                                    <li><a href="#!">scarves</a></li>
                                                                    <li> <a href="#!">more...</a>
                                                                        <ul>
                                                                            <li><a href="#!">accessory gift
                                                                                    sets</a>
                                                                            </li>
                                                                            <li><a href="#!">travel
                                                                                    accessories</a>
                                                                            </li>
                                                                            <li><a href="#!">phone cases</a></li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                            <li><a href="#!">belts & more</a></li>
                                                            <li><a href="#!">wearable</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><a href="#!">house of design</a></li>
                                            <li> <a href="#!">beauty & personal care</a>
                                                <ul>
                                                    <li><a href="#!">makeup</a></li>
                                                    <li><a href="#!">skincare</a></li>
                                                    <li><a href="#!">premium beauty</a></li>
                                                    <li> <a href="#!">more</a>
                                                        <ul>
                                                            <li><a href="#!">fragrances</a></li>
                                                            <li><a href="#!">luxury beauty</a></li>
                                                            <li><a href="#!">hair care</a></li>
                                                            <li><a href="#!">tools & brushes</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><a href="#!">home & decor</a></li>
                                            <li><a href="#!">kitchen</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div> --}}
                            <div class="brand-logo">
                                <a href="{{ route('index') }}">
                                    <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/logo.png') }}" style="width: 150px; height: auto;" class="img-fluid blur-up lazyload" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="menu-right pull-right">
                            <div>
                                <nav id="main-nav">
                                    <div class="toggle-nav"><i class="ri-bar-chart-horizontal-line sidebar-bar"></i>
                                    </div>
                                    <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                                        <li class="mobile-box">
                                            <div class="mobile-back text-end">Menu<i class="ri-close-line"></i></div>
                                        </li>
                                        <li><a href="{{ route('index') }}">Accueil</a></li>
                                        <li class="mega hover-cls">
                                            <a href="#!">Catégories <div class="lable-nav">Nouveau</div></a>
                                            <ul class="mega-menu full-mega-menu">
                                                <li>
                                                    <div class="container">
                                                        <div class="row g-xl-4 g-0">
                                                            @foreach (FrontHelper::fiveCategories() as $categorie)
                                                                <div class="col mega-box">
                                                                    <div class="link-section">
                                                                        <div class="menu-title">
                                                                            <h5>{{ $categorie->label }}</h5>
                                                                        </div>
                                                                        <div class="menu-content">
                                                                            <ul>
                                                                                @foreach ($categorie->sousCategories->take(5) as $sousCategorie)
                                                                                    <li><a href="{{ route('sousCategories.single', $sousCategorie) }}">{{ $sousCategorie->label }}</a></li>
                                                                                @endforeach

                                                                            </ul>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            @endforeach


                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-12">
                                                                <div class="menu-title">
                                                                    <h5><a href="{{ route('categories.page') }}">Voir toutes les catégories<i class="ms-2 ri-flashlight-fill icon-trend"></i></a></h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        {{-- <li>
                                            <a href="#!">shop</a>
                                            <ul>
                                                <li>
                                                    <a href="category-page(vegetables).html">tab style<span
                                                            class="new-tag">new</span></a>
                                                </li>
                                                <li>
                                                    <a href="category-page(top-filter).html">top filter</a>
                                                </li>
                                                <li>
                                                    <a href="category-page(modern).html">modern</a>
                                                </li>
                                                <li>
                                                    <a href="category-page.html">left sidebar</a>
                                                </li>
                                                <li>
                                                    <a href="category-page(right).html">right sidebar</a>
                                                </li>
                                                <li>
                                                    <a href="category-page(no-sidebar).html">no sidebar</a>
                                                </li>
                                                <li>
                                                    <a href="category-page(category-slider).html">Category Slider</a>
                                                </li>
                                                <li>
                                                    <a href="category-page(sidebar-popup).html">sidebar popup</a>
                                                </li>
                                                <li>
                                                    <a href="category-page(metro).html">metro</a>
                                                </li>
                                                <li>
                                                    <a href="category-page(full-width).html">full width</a>
                                                </li>
                                                <li>
                                                    <a href="category-page(load-more).html">load more</a>
                                                </li>
                                                <li>
                                                    <a href=category-page(2-grid).html>two grid</a>
                                                </li>
                                                <li>
                                                    <a href=category-page(3-grid).html>three grid</a>
                                                </li>
                                                <li>
                                                    <a href="category-page(4-grid).html">four grid</a>
                                                </li>
                                                <li>
                                                    <a href="category-page(list-view).html">list view</a>
                                                </li>
                                            </ul>
                                        </li> --}}
                                        {{-- <li class="mega hover-cls">
                                            <a href="#!">product</a>
                                            <ul class="mega-menu full-mega-menu">
                                                <li>
                                                    <div class="container">
                                                        <div class="row g-xl-4 g-0">
                                                            <div class="col mega-box">
                                                                <div class="link-section">
                                                                    <div class="menu-title">
                                                                        <h5>Product Page</h5>
                                                                    </div>
                                                                    <div class="menu-content">
                                                                        <ul>
                                                                            <li>
                                                                                <a href="product-page(thumbnail).html">Product
                                                                                    Thumbnail</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(4-image).html">Product
                                                                                    Image</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(slider).html">Product
                                                                                    Slider</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Product
                                                                                    Accordion</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(sticky).html">Product
                                                                                    Sticky</a>
                                                                            </li>
                                                                            <li>
                                                                                <a
                                                                                    href="product-page(vertical-tab).html">Product
                                                                                    Vertical Tab</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col mega-box">
                                                                <div class="link-section">
                                                                    <div class="menu-title">
                                                                        <h5>Product Page</h5>
                                                                    </div>
                                                                    <div class="menu-content">
                                                                        <ul>
                                                                            <li>
                                                                                <a
                                                                                    href="product-page(left-sidebar).html">Product
                                                                                    Sidebar Left</a>
                                                                            </li>
                                                                            <li>
                                                                                <a
                                                                                    href="product-page(right-sidebar).html">Product
                                                                                    Sidebar Right</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Product
                                                                                    No Sidebar</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Product
                                                                                    Column Thumbnail</a>
                                                                            </li>
                                                                            <li>
                                                                                <a
                                                                                    href="product-page(image-outside).html">Product
                                                                                    Thumbnail Image Outside</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col mega-box">
                                                                <div class="link-section">
                                                                    <div class="menu-title">
                                                                        <h5>Product Variants Style</h5>
                                                                    </div>
                                                                    <div class="menu-content">
                                                                        <ul>
                                                                            <li>
                                                                                <a href="product-page(3-column).html">Variant
                                                                                    Rectangle</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Variant
                                                                                    Circle</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Variant
                                                                                    Image Swatch</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(3-column).html">Variant
                                                                                    Color</a>
                                                                            </li>
                                                                            <li>
                                                                                <a
                                                                                    href="product-page(vertical-tab).html">Variant
                                                                                    Radio Button</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(sticky).html">Variant
                                                                                    Dropdown</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col mega-box">
                                                                <div class="link-section">
                                                                    <div class="menu-title">
                                                                        <h5>Product Features</h5>
                                                                    </div>
                                                                    <div class="menu-content">
                                                                        <ul>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Product
                                                                                    Simple</a>
                                                                            </li>
                                                                            <li>
                                                                                <a
                                                                                    href="product-page(left-sidebar).html">Product
                                                                                    Classified</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Size
                                                                                    Chart</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Delivery
                                                                                    & Return</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Product
                                                                                    Review</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Ask
                                                                                    an Expert</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col mega-box">
                                                                <div class="link-section">
                                                                    <div class="menu-title">
                                                                        <h5>Product Features</h5>
                                                                    </div>
                                                                    <div class="menu-content">
                                                                        <ul>
                                                                            <li>
                                                                                <a href="product-page(bundle).html">Bundle
                                                                                    (Cross Sale)</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Hot
                                                                                    Stock
                                                                                    Progress</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Out
                                                                                    Stock</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(thumbnail).html">Sale
                                                                                    Countdown</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(thumbnail).html">Product
                                                                                    Zoom</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col mega-box">
                                                                <div class="link-section">
                                                                    <div class="menu-title">
                                                                        <h5>Product Features</h5>
                                                                    </div>
                                                                    <div class="menu-content">
                                                                        <ul>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Sticky
                                                                                    Checkout</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(accordian).html">Secure
                                                                                    Checkout</a>
                                                                            </li>
                                                                            <li>
                                                                                <a
                                                                                    href="product-page(vertical-tab).html">Social
                                                                                    Share</a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="product-page(thumbnail).html">Related
                                                                                    Products</a>
                                                                            </li>
                                                                            <li>
                                                                                <a
                                                                                    href="product-page(right-sidebar).html">Wishlist
                                                                                    & Compare</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/menu-banner.jpg') }}" alt=""
                                                                    class="img-fluid mega-img d-xl-block d-none">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li> --}}
                                        <li><a href="{{ route('wishlist.my') }}">Favoris</a></li>
                                        <li><a href="">Nous Contacter</a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div>
                                <div class="icon-nav">
                                    <ul>
                                        <li class="onhover-div mobile-search">
                                            <div data-bs-toggle="modal" data-bs-target="#searchModal">
                                                <i class="ri-search-line"></i>
                                            </div>
                                        </li>
                                        {{-- <li class="onhover-div mobile-setting">
                                            <div><i class="ri-equalizer-2-line"></i></div>
                                            <div class="show-div setting">
                                                <h6>language</h6>
                                                <ul>
                                                    <li><a href="#!">english</a></li>
                                                    <li><a href="#!">french</a></li>
                                                </ul>
                                                <h6>currency</h6>
                                                <ul class="list-inline">
                                                    <li><a href="#!">euro</a></li>
                                                    <li><a href="#!">rupees</a></li>
                                                    <li><a href="#!">pound</a></li>
                                                    <li><a href="#!">dollar</a></li>
                                                </ul>
                                            </div>
                                        </li> --}}
                                        @if (Route::currentRouteName() != 'checkout')
                                            <li class="onhover-div mobile-cart">
                                                <div data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </div>
                                                <span class="cart_qty_cls">2</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header end -->
