<!DOCTYPE html>
<html lang="en">


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="multikart">
    <meta name="keywords" content="multikart">
    <meta name="author" content="multikart">
    <link rel="icon" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/favicon.png') }}" type="image/x-icon">
    <title>{{ FrontHelper::getAppName() }} - @yield('title')</title>

    <!--Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&amp;family=Montserrat:ital,wght@0,100..900;1,100..900&amp;display=swap">

    <!-- Icons -->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/font-awesome.css') }}"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/remixicon.css') }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Slick slider css -->
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/slick.css') }}">

    <!-- Animate icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/animate.css') }}">

    <!-- Themify icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/themify-icons.css') }}">

    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/bootstrap.css') }}">

    <!-- Theme css -->
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/style.css') }}">
    <style>
        /* Style pour la bannière Hero */
        .furniture-hero-banner {
            transition: all 0.5s ease;
        }

        .furniture-hero-banner:hover {
            transform: scale(1.01);
        }

        /* Style pour le compteur */
        .countdown-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .countdown-box {
            text-align: center;
            background: white;
            padding: 10px 15px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            min-width: 80px;
        }

        .countdown-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #b78d65;
            display: block;
            line-height: 1;
        }

        .countdown-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
        }

        /* Animation hover pour les boutons */
        .btn-dark {
            background-color: #2a2a2a;
            border-color: #2a2a2a;
            transition: all 0.3s ease;
        }

        .btn-dark:hover {
            background-color: #b78d65;
            border-color: #b78d65;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Effet de lettrage */
        .letter-spacing-3 {
            letter-spacing: 3px;
        }
    </style>
    @yield('styles')
</head>

<body class="theme-color-1">


    <!-- loader start -->
    <div class="loader_skeleton">
        <div class="top-header">
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
                            <li class="mobile-wishlist">
                                <a href="#!">
                                    <i class="ri-heart-fill"></i>
                                </a>
                            </li>
                            <li class="onhover-dropdown mobile-account">
                                <i class="ri-user-fill"></i>
                                Mon Compte
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <header>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="main-menu">
                            <div class="menu-left">
                                {{-- <div class="navbar">
                                    <a href="#!">
                                        <div class="bar-style">
                                            <i class="ri-bar-chart-horizontal-line sidebar-bar"></i>
                                        </div>
                                    </a>
                                </div> --}}
                                <div class="brand-logo">
                                    <a href="{{ route('index') }}">
                                        <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/logo.png') }}" style="width: 150px; height: auto;" class="img-fluid blur-up lazyload" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="menu-right pull-right">
                                <div>
                                    <nav>
                                        <div class="toggle-nav"><i class="ri-bar-chart-horizontal-line sidebar-bar"></i>
                                        </div>
                                        <ul class="sm pixelstrap sm-horizontal">
                                            <li>
                                                <div class="mobile-back text-end">Retour<i
                                                        class="ri-arrow-left-s-line ps-2"></i></div>
                                            </li>
                                            <li>
                                                <a href="{{ route('index') }}">Accueil</a>
                                            </li>
                                            <li>
                                                <a href="#!">Catégories<div class="lable-nav">Nouveau</div></a>
                                            </li>
                                            <li>
                                                <a href="#!">Favoris</a>
                                            </li>
                                            <li><a href="#!">Nous Contacter</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div>
                                    <div class="icon-nav d-none d-sm-block">
                                        <ul>
                                            <li class="onhover-div mobile-search">
                                                <div>
                                                    <i class="ri-search-line"></i>
                                                </div>
                                            </li>
                                            <li class="onhover-div mobile-setting">
                                                <div>
                                                    <i class="ri-equalizer-2-line"></i>
                                                </div>
                                            </li>
                                            <li class="onhover-div mobile-cart">
                                                <div>
                                                    <i class="ri-shopping-cart-line"></i>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="home-slider">
            <div class="home"></div>
        </div>
        <section class="collection-banner">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="ldr-bg">
                            <div class="contain-banner">
                                <div>
                                    <h4></h4>
                                    <h2></h2>
                                    <h6></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="ldr-bg">
                            <div class="contain-banner">
                                <div>
                                    <h4></h4>
                                    <h2></h2>
                                    <h6></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="container section-b-space">
            <div class="row section-t-space">
                <div class="col-lg-6 offset-lg-3">
                    <div class="product-para">
                        <p class="first"></p>
                        <p class="second"></p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="no-slider row">
                        <div class="product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                                <h6></h6>
                            </div>
                        </div>
                        <div class="product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                                <h6></h6>
                            </div>
                        </div>
                        <div class="product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                                <h6></h6>
                            </div>
                        </div>
                        <div class="product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                                <h6></h6>
                            </div>
                        </div>
                        <div class="product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                                <h6></h6>
                            </div>
                        </div>
                        <div class="product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                                <h6></h6>
                            </div>
                        </div>
                        <div class="product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                                <h6></h6>
                            </div>
                        </div>
                        <div class="product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                                <h6></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- loader end -->
