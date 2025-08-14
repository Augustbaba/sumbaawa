<!-- Search Modal Start -->
    <div class="modal fade search-modal theme-modal-2" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5">Search in store</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="search-input-box">
                        <input type="text" class="form-control" placeholder="Search with brands and categories...">
                        <i class="ri-search-2-line"></i>
                    </div>

                    <ul class="search-category">
                        <li class="category-title">Top search:</li>
                        <li>
                            <a href="category-page.html">Baby Essentials</a>
                        </li>
                        <li>
                            <a href="category-page.html">Bag Emporium</a>
                        </li>
                        <li>
                            <a href="category-page.html">Bags</a>
                        </li>
                        <li>
                            <a href="category-page.html">Books</a>
                        </li>
                    </ul>

                    <div class="search-product-box mt-sm-4 mt-3">
                        <h3 class="search-title">Most Searched</h3>

                        <div class="row row-cols-xl-4 row-cols-md-3 row-cols-2 g-sm-4 g-3">
                            <div class="col">
                                <div class="basic-product theme-product-1">
                                    <div class="overflow-hidden">
                                        <div class="img-wrapper">
                                            <div class="ribbon"><span>Exclusive</span></div>
                                            <a href="product-page(image-swatch).html">
                                                <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion-1/product/1.jpg') }}"
                                                    class="img-fluid blur-up lazyloaded" alt="">
                                            </a>
                                            <div class="rating-label"><i class="ri-star-fill"></i><span>2.5</span>
                                            </div>
                                            <div class="cart-info">
                                                <a href="#!" title="Add to Wishlist" class="wishlist-icon">
                                                    <i class="ri-heart-line"></i>
                                                </a>
                                                <button data-bs-toggle="modal" data-bs-target="#addtocart"
                                                    title="Add to cart">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </button>
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView"
                                                    title="Quick View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="compare.html" title="Compare">
                                                    <i class="ri-loop-left-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                            <div>
                                                <div class="brand-w-color">
                                                    <a class="product-title" href="product-page(accordian).html">
                                                        Glamour Gaze
                                                    </a>
                                                    <div class="color-panel">
                                                        <ul>
                                                            <li style="background-color: papayawhip;"></li>
                                                            <li style="background-color: burlywood;"></li>
                                                            <li style="background-color: gainsboro;"></li>
                                                        </ul>
                                                        <span>+2</span>
                                                    </div>
                                                </div>
                                                <h6>Boyfriend Shirts</h6>
                                                <h4 class="price">$ 2.79<del> $3.00 </del><span
                                                        class="discounted-price"> 7%
                                                        Off
                                                    </span>
                                                </h4>
                                            </div>
                                            <ul class="offer-panel">
                                                <li>
                                                    <span class="offer-icon">
                                                        <i class="ri-discount-percent-fill"></i>
                                                    </span>
                                                    Limited Time Offer: 4% off
                                                </li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 4% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 4% off</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="basic-product theme-product-1">
                                    <div class="overflow-hidden">
                                        <div class="img-wrapper">
                                            <a href="product-page(accordian).html"><img
                                                    src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion-1/product/11.jpg') }}"
                                                    class="img-fluid blur-up lazyloaded" alt=""></a>
                                            <div class="rating-label"><i class="ri-star-s-fill"></i>
                                                <span>6.5</span>
                                            </div>
                                            <div class="cart-info">
                                                <a href="#!" title="Add to Wishlist" class="wishlist-icon">
                                                    <i class="ri-heart-line"></i>
                                                </a>
                                                <button data-bs-toggle="modal" data-bs-target="#addtocart"
                                                    title="Add to cart">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </button>
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView"
                                                    title="Quick View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="compare.html" title="Compare">
                                                    <i class="ri-loop-left-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                            <div>
                                                <div class="brand-w-color">
                                                    <a class="product-title" href="product-page(accordian).html">
                                                        VogueVista
                                                    </a>
                                                </div>
                                                <h6>Chic Crop Top</h6>
                                                <h4 class="price">$ 5.60<del> $6.80 </del><span
                                                        class="discounted-price"> 5%
                                                        Off
                                                    </span>
                                                </h4>
                                            </div>
                                            <ul class="offer-panel">
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 25% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 25% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 25% off</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="basic-product theme-product-1">
                                    <div class="overflow-hidden">
                                        <div class="img-wrapper">
                                            <a href="product-page(accordian).html"><img
                                                    src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion-1/product/15.jpg') }}"
                                                    class="img-fluid blur-up lazyloaded" alt=""></a>
                                            <div class="rating-label"><i class="ri-star-s-fill"></i>
                                                <span>3.7</span>
                                            </div>
                                            <div class="cart-info">
                                                <a href="#!" title="Add to Wishlist" class="wishlist-icon">
                                                    <i class="ri-heart-line"></i>
                                                </a>
                                                <button data-bs-toggle="modal" data-bs-target="#addtocart"
                                                    title="Add to cart">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </button>
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView"
                                                    title="Quick View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="compare.html" title="Compare">
                                                    <i class="ri-loop-left-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                            <div>
                                                <div class="brand-w-color">
                                                    <a class="product-title" href="product-page(accordian).html">
                                                        Urban Chic
                                                    </a>
                                                </div>
                                                <h6>Classic Jacket</h6>
                                                <h4 class="price">$ 3.80 </h4>
                                            </div>
                                            <ul class="offer-panel">
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 10% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 10% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 10% off</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="basic-product theme-product-1">
                                    <div class="overflow-hidden">
                                        <div class="img-wrapper">
                                            <a href="product-page(image-swatch).html">
                                                <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion-1/product/16.jpg') }}"
                                                    class="img-fluid blur-up lazyloaded" alt="">
                                            </a>
                                            <div class="rating-label"><i class="ri-star-s-fill"></i>
                                                <span>8.7</span>
                                            </div>
                                            <div class="cart-info">
                                                <a href="#!" title="Add to Wishlist" class="wishlist-icon">
                                                    <i class="ri-heart-line"></i>
                                                </a>
                                                <button data-bs-toggle="modal" data-bs-target="#addtocart"
                                                    title="Add to cart">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </button>
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#quickView"
                                                    title="Quick View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="compare.html" title="Compare">
                                                    <i class="ri-loop-left-line"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                            <div>
                                                <div class="brand-w-color">
                                                    <a class="product-title" href="product-page(accordian).html">
                                                        Couture Edge
                                                    </a>
                                                </div>
                                                <h6>Versatile Shacket</h6>
                                                <h4 class="price"> $3.00
                                                </h4>
                                            </div>
                                            <ul class="offer-panel">
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 12% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 12% off</li>
                                                <li><span class="offer-icon"><i
                                                            class="ri-discount-percent-fill"></i></span>
                                                    Limited Time Offer: 12% off</li>
                                            </ul>
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
    <!-- Search Modal End -->


    <!-- Cart Offcanvas Start -->
    <div class="offcanvas offcanvas-end cart-offcanvas" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header">
            <h3 class="offcanvas-title">My Cart (3)</h3>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <div class="pre-text-box">
                <p>spend $20.96 More And Enjoy Free Shipping!</p>
                <div class="progress" role="progressbar">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 58.08%;">
                        <i class="ri-truck-line"></i>
                    </div>
                </div>
            </div>

            <div class="sidebar-title">
                <a href="#!">Clear Cart</a>
            </div>

            <div class="cart-media">
                <ul class="cart-product">
                    <li>
                        <div class="media">
                            <a href="#!">
                                <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion-1/product/5.jpg') }}" class="img-fluid"
                                    alt="Classic Jacket">
                            </a>
                            <div class="media-body">
                                <a href="#!">
                                    <h4>Couture Edge</h4>
                                </a>
                                <h4 class="quantity">
                                    <span>1 x $6.74</span>
                                </h4>

                                <div class="qty-box">
                                    <div class="input-group qty-container">
                                        <button class="btn qty-btn-minus">
                                            <i class="ri-subtract-line"></i>
                                        </button>
                                        <input type="number" readonly name="qty" class="form-control input-qty"
                                            value="1">
                                        <button class="btn qty-btn-plus">
                                            <i class="ri-add-line"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="close-circle">
                                    <button class="close_button edit-button" data-bs-toggle="modal"
                                        data-bs-target="#variationModal">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button class="close_button delete-button" type="submit">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="media">
                            <a href="#!">
                                <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion-1/product/13.jpg') }}" class="img-fluid"
                                    alt="Classic Jacket">
                            </a>
                            <div class="media-body">
                                <a href="#!">
                                    <h4>Classic Jacket</h4>
                                </a>
                                <h4 class="quantity">
                                    <span>1 x $7.84</span>
                                </h4>
                                <div class="qty-box">
                                    <div class="input-group qty-container">
                                        <button class="btn qty-btn-minus">
                                            <i class="ri-subtract-line"></i>
                                        </button>
                                        <input type="number" readonly name="qty" class="form-control input-qty"
                                            value="1">
                                        <button class="btn qty-btn-plus">
                                            <i class="ri-add-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="close-circle">
                                    <button class="close_button delete-button" type="submit">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="media">
                            <a href="#!">
                                <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion-1/product/12.jpg') }}" class="img-fluid"
                                    alt="Classic Jacket">
                            </a>
                            <div class="media-body">
                                <a href="#!">
                                    <h4>Urban Chic</h4>
                                </a>
                                <h4 class="quantity">
                                    <span>2 x $3.84</span>
                                </h4>
                                <div class="qty-box">
                                    <div class="input-group qty-container">
                                        <button class="btn qty-btn-minus">
                                            <i class="ri-subtract-line"></i>
                                        </button>
                                        <input type="number" readonly name="qty" class="form-control input-qty"
                                            value="1">
                                        <button class="btn qty-btn-plus">
                                            <i class="ri-add-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="close-circle">
                                    <button class="close_button delete-button" type="submit">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <ul class="cart_total">
                    <li>
                        <div class="total">
                            <h5>Sub Total : <span>$36.74</span>
                            </h5>
                        </div>
                    </li>
                    <li>
                        <div class="buttons">
                            <a href="cart.html" class="btn view-cart">View Cart</a>
                            <a href="checkout.html" class="btn checkout">Check Out</a>
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
    <div class="cookie-bar">
        <p>Nous utilisons des cookies pour améliorer notre site et votre expérience d'achat. En poursuivant votre navigation, vous acceptez notre politique en matière de cookies.</p>
        <a href="#!" class="btn btn-solid btn-xs">Accepter</a>
        <a href="#!" class="btn btn-solid btn-xs">Refuser</a>
    </div>
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








    <!-- Add to cart modal popup start-->
    <div class="modal fade bd-example-modal-lg theme-modal cart-modal" id="addtocart" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body modal1">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="modal-bg addtocart">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                    <div class="media">
                                        <a href="#!">
                                            <img class="img-fluid blur-up lazyload pro-img"
                                                src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion/product/55.jpg') }}" alt="">
                                        </a>
                                        <div class="media-body align-self-center text-center">
                                            <a href="#!">
                                                <h6>
                                                    <i class="ri-checkbox-circle-fill"></i>Item
                                                    <span>men full sleeves</span>
                                                    <span> successfully added to your Cart</span>
                                                </h6>
                                            </a>
                                            <div class="buttons">
                                                <a href="#!" class="view-cart btn btn-solid">Your cart</a>
                                                <a href="#!" class="checkout btn btn-solid">Check out</a>
                                                <a href="#!" class="continue btn btn-solid">Continue shopping</a>
                                            </div>

                                            <div class="upsell_payment">
                                                <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/payment_cart.png') }}"
                                                    class="img-fluid blur-up lazyload" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-section">
                                        <div class="col-12 product-upsell text-center">
                                            <h4>Customers who bought this item also.</h4>
                                        </div>
                                        <div class="row" id="upsell_product">
                                            <div class="product-box col-sm-3 col-6">
                                                <div class="img-wrapper">
                                                    <div class="front">
                                                        <a href="#!">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion/product/1.jpg') }}"
                                                                class="img-fluid blur-up lazyload mb-1"
                                                                alt="cotton top">
                                                        </a>
                                                    </div>
                                                    <div class="product-detail">
                                                        <h6><a href="#!"><span>cotton top</span></a></h6>
                                                        <h4><span>$25</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-box col-sm-3 col-6">
                                                <div class="img-wrapper">
                                                    <div class="front">
                                                        <a href="#!">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion/product/34.jpg') }}"
                                                                class="img-fluid blur-up lazyload mb-1"
                                                                alt="cotton top">
                                                        </a>
                                                    </div>
                                                    <div class="product-detail">
                                                        <h6><a href="#!"><span>cotton top</span></a></h6>
                                                        <h4><span>$25</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-box col-sm-3 col-6">
                                                <div class="img-wrapper">
                                                    <div class="front">
                                                        <a href="#!">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion/product/13.jpg') }}"
                                                                class="img-fluid blur-up lazyload mb-1"
                                                                alt="cotton top">
                                                        </a>
                                                    </div>
                                                    <div class="product-detail">
                                                        <h6><a href="#!"><span>cotton top</span></a></h6>
                                                        <h4><span>$25</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-box col-sm-3 col-6">
                                                <div class="img-wrapper">
                                                    <div class="front">
                                                        <a href="#!">
                                                            <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/fashion/product/19.jpg') }}"
                                                                class="img-fluid blur-up lazyload mb-1"
                                                                alt="cotton top">
                                                        </a>
                                                    </div>
                                                    <div class="product-detail">
                                                        <h6><a href="#!"><span>cotton top</span></a></h6>
                                                        <h4><span>$25</span></h4>
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
            </div>
        </div>
    </div>
    <!-- Add to cart modal popup end-->


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

    @yield('scripts')
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
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/fly-cart.js') }}"></script>

    <!-- Theme js-->
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/theme-setting.js') }}"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/script.js') }}"></script>

    <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('#exampleModal').modal('show');
            }, 2500);
        });
    </script>

</body>


</html>
