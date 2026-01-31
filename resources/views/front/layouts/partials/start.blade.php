<!DOCTYPE html>
<html lang="en">


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Explorez les tendances incontournables en meubles, accessoires, électronique et automobiles ! Aménagez vos espaces avec style, découvrez les gadgets high-tech et trouvez votre voiture idéale pour confort et modernité. Collections exclusives à ne pas manquer !">
    <meta name="keywords" content="meubles, mobilier, tendances meubles, décoration intérieure, chaises, fauteuils, bureaux, tables, rangements, cabines, accessoires maison, mobilier moderne, design intérieur, sumbaawa, sumba awa, sumbaawa.com, sumbaawa.fr, sumbaawa.org, furniture, home decor, interior design, living room furniture, bedroom furniture, office furniture, véhicules, ameublement, décoration, mobilier contemporain, voiture, moto, utilitaire, SUV, berline, camionnette, 4x4, pick-up, véhicule d'occasion, achat voiture, vente voiture, concessionnaire auto, auto neuf, auto occasion, entretien auto, réparation auto, c'est ma voiture, cestmavoiture, bijoux, montres, accessoires mode, bagues, colliers, bracelets, boucles d'oreilles, montres homme, montres femme, bijouterie en ligne, achat bijoux, vente bijoux, bijou pas cher, bijou de luxe, bijoux tendance, bijoux personnalisés, le bazar électronique, électronique, gadgets, accessoires tech, smartphones, tablettes, ordinateurs portables, casques audio, enceintes Bluetooth, montres connectées, accessoires gaming, achat électronique, vente électronique, high-tech pas cher, tech tendance">

    @if (Route::currentRouteName() != 'cart.view' && Route::currentRouteName() != 'checkout')
        <meta name="robots" content="index, follow">
    @else
        <meta name="robots" content="noindex, nofollow">
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (Route::currentRouteName() == 'categories.single' && in_array(request()->segment(2), ['le-bazar-de-lelectronique', 'cest-ma-voiture']))
        @if (request()->segment(2) == 'le-bazar-de-lelectronique')
            <meta name="author" content="Le Bazar de l'Electronique">
            <link rel="apple-touch-icon" sizes="180x180" href="{{ asset(FrontHelper::getEnvFolder() . 'lbz/apple-touch-icon.png') }}">
            <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(FrontHelper::getEnvFolder() . 'lbz/favicon-32x32.png') }}">
            <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(FrontHelper::getEnvFolder() . 'lbz/favicon-16x16.png') }}">
            <link rel="manifest" href="{{ asset(FrontHelper::getEnvFolder() . 'lbz/site.webmanifest') }}">
        @else
            <meta name="author" content="C'est Ma Voiture">
            <link rel="apple-touch-icon" sizes="180x180" href="{{ asset(FrontHelper::getEnvFolder() . 'cmv/apple-touch-icon.png') }}">
            <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(FrontHelper::getEnvFolder() . 'cmv/favicon-32x32.png') }}">
            <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(FrontHelper::getEnvFolder() . 'cmv/favicon-16x16.png') }}">
            <link rel="manifest" href="{{ asset(FrontHelper::getEnvFolder() . 'cmv/site.webmanifest') }}">
        @endif

    @else
        <meta name="author" content="{{ FrontHelper::getAppName() }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset(FrontHelper::getEnvFolder() . 'favicon_io/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(FrontHelper::getEnvFolder() . 'favicon_io/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(FrontHelper::getEnvFolder() . 'favicon_io/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset(FrontHelper::getEnvFolder() . 'favicon_io/site.webmanifest') }}">
    @endif
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
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script> --}}
    <!-- Animate icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/animate.css') }}">

    <!-- Themify icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/themify-icons.css') }}">

    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/vendors/bootstrap.css') }}">

    <!-- Theme css -->
    <link rel="stylesheet" type="text/css" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/css/style.css') }}">
    <style>
    /* Styles pour le carrousel */
        .hero-carousel {
            position: relative;
            width: 100%;
            height: 80vh;
            overflow: hidden;
        }

        .carousel-slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .carousel-slide.active {
            opacity: 1;
        }

        /* Contrôles du carrousel */
        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.3);
            color: white;
            border: none;
            font-size: 2rem;
            padding: 10px 15px;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .carousel-control:hover {
            background: rgba(255,255,255,0.7);
            color: #333;
        }

        .prev {
            left: 20px;
        }

        .next {
            right: 20px;
        }

        /* Indicateurs */
        .carousel-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator.active {
            background: white;
        }

        /* Style pour le compte à rebours */
        .countdown-container {
            display: flex;
            gap: 15px;
        }

        .countdown-box {
            text-align: center;
        }

        .countdown-number {
            display: block;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .countdown-label {
            font-size: 0.8rem;
            color: #555;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .carousel-slide {
                min-height: 60vh;
            }
            h1 {
                font-size: 1.8rem !important;
            }
            .btn-lg {
                padding: 10px 20px !important;
                font-size: 1rem !important;
            }
            .countdown-container {
                flex-wrap: wrap;
            }
            .countdown-box {
                min-width: 60px;
            }
        }

        /* Styles existants */
        .furniture-hero-banner {
            transition: all 0.5s ease;
        }

        .furniture-hero-banner:hover {
            transform: scale(1.01);
        }

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

        .letter-spacing-3 {
            letter-spacing: 3px;
        }

        .view-thumbnail-slider .slider-image {
            max-width: 80px; /* Taille maximale pour les miniatures */
            max-height: 80px; /* Ajustez selon vos besoins */
            overflow: hidden;
        }

        .view-thumbnail-slider .slider-image img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Assure que l'image s'adapte sans déformation */
        }

        .cart-disabled {
            color: #ccc !important; /* Couleur grisée */
            background-color: #f5f5f5 !important; /* Fond grisé (ajustez si nécessaire) */
            cursor: not-allowed !important; /* Curseur indiquant non cliquable */
            pointer-events: none !important; /* Empêche les clics */
            opacity: 0.6 !important; /* Effet visuel grisé */
            text-decoration: none !important; /* Supprime le soulignement */
        }

        .menu-logo {
            width: 30px;
            height: auto;
            margin-right: 4px;
            vertical-align: middle;
        }

        /* Cacher sur tablette et mobile */
        @media (max-width: 991.98px) {
            .hide-on-mobile {
                display: none !important;
            }
        }

        .container-nihao {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }


        /* Section Nihao Travel améliorée */
        .nihao-mini-cta {
            background: linear-gradient(135deg, #f7f9fc 0%, #ffffff 100%);
            border-top: 1px solid #e6e9ef;
            border-left: 4px solid #2563eb;
            padding: 25px 0;
            position: relative;
            overflow: hidden;
        }

        .nihao-mini-cta::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background-color: rgba(37, 99, 235, 0.05);
            border-radius: 50%;
            z-index: 0;
        }

        .nihao-mini-cta::after {
            content: "";
            position: absolute;
            bottom: -30px;
            left: -30px;
            width: 100px;
            height: 100px;
            background-color: rgba(37, 99, 235, 0.05);
            border-radius: 50%;
            z-index: 0;
        }

        .nihao-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .nihao-text {
            flex: 1;
            min-width: 300px;
        }

        .nihao-badge {
            display: inline-flex;
            align-items: center;
            background-color: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .nihao-badge i {
            margin-right: 6px;
            font-size: 12px;
        }

        .nihao-text h6 {
            margin: 0 0 8px;
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.3;
        }

        .nihao-text p {
            margin: 0;
            font-size: 15px;
            color: #475569;
            line-height: 1.5;
        }

        .nihao-text strong {
            color: #2563eb;
            font-weight: 700;
        }

        .nihao-highlight {
            display: inline-flex;
            align-items: center;
            background-color: #fef3c7;
            color: #92400e;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 10px;
        }

        .nihao-highlight i {
            margin-right: 5px;
            font-size: 12px;
        }

        .btn-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Animation subtile au chargement */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nihao-content {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nihao-content {
                text-align: center;
                justify-content: center;
            }

            .nihao-text {
                text-align: center;
            }

            .btn-container {
                width: 100%;
                justify-content: center;
                margin-top: 15px;
            }

            .nihao-mini-cta::before,
            .nihao-mini-cta::after {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .nihao-mini-cta {
                padding: 20px 0;
            }

            .nihao-text h6 {
                font-size: 16px;
            }

            .nihao-text p {
                font-size: 14px;
            }

            .btn-solid {
                padding: 10px 20px;
                font-size: 14px;
                width: 100%;
                max-width: 250px;
            }
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
                                <li>
                                    Bienvenue sur
                                    @if (Route::currentRouteName() == 'categories.single' && in_array(request()->segment(2), ['le-bazar-de-lelectronique', 'cest-ma-voiture']))
                                        @if (request()->segment(2) == 'le-bazar-de-lelectronique')
                                            <span>Le Bazar de l'Electronique</span>
                                        @else
                                            <span>C'est Ma Voiture</span>
                                        @endif

                                    @else
                                        @if (Route::currentRouteName() != 'nihao.travel')
                                            {{ FrontHelper::getAppName() }}
                                        @else
                                            <span>Nihao Travel</span>
                                        @endif
                                    @endif
                                </li>
                                <li><i class="ri-phone-fill"></i> +242044724102</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 text-end">
                        <ul class="header-dropdown">
                            <li class="mobile-wishlist">
                                <a href="{{ route('wishlist.my') }}">
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
                                    @if (Route::currentRouteName() == 'categories.single' && in_array(request()->segment(2), ['le-bazar-de-lelectronique', 'cest-ma-voiture']))
                                        @if (request()->segment(2) == 'le-bazar-de-lelectronique')
                                            <a href="#">
                                                <img src="{{ asset(FrontHelper::getImageBySlug('le-bazar-de-lelectronique')) }}" style="width: 150px; height: auto;" class="img-fluid blur-up lazyload" alt="Le Bazar de l'Electronique">
                                            </a>
                                        @else
                                            <a href="#">
                                                <img src="{{ asset(FrontHelper::getImageBySlug('cest-ma-voiture')) }}" style="width: 150px; height: auto;" class="img-fluid blur-up lazyload" alt="C'est Ma Voiture">
                                            </a>
                                        @endif

                                    @else
                                        @if (Route::currentRouteName() != 'nihao.travel')
                                            <a href="{{ route('index') }}">
                                                <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/logo.png') }}" style="width: 150px; height: auto;" class="img-fluid blur-up lazyload" alt="">
                                            </a>
                                        @else
                                            <a href="#">
                                                <img src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/nihao.png') }}" style="width: 150px; height: auto;" class="img-fluid blur-up lazyload" alt="">
                                            </a>
                                        @endif
                                    @endif
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
                                                <a href="{{ route('categories.single', 'le-bazar-de-lelectronique') }}">
                                                    <img src="{{ asset(FrontHelper::getImageBySlug('le-bazar-de-lelectronique')) }}"
                                                        class="menu-logo hide-on-mobile"
                                                        alt="Le Bazar de l'Electronique">
                                                    Le Bazar de l'Electronique
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('categories.single', 'cest-ma-voiture') }}">
                                                    <img src="{{ asset(FrontHelper::getImageBySlug('cest-ma-voiture')) }}"
                                                        class="menu-logo hide-on-mobile"
                                                        alt="C'est Ma Voiture">
                                                    C'est Ma Voiture
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('categories.page') }}">Catégories<div class="lable-nav">Nouveau</div></a>
                                            </li>
                                            {{-- <li>
                                                <a href="{{ route('wishlist.my') }}">Favoris</a>
                                            </li>
                                            <li><a href="{{ route('contact') }}">Contactez-nous</a>
                                            </li> --}}
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
                                                    <i class="ri-money-dollar-circle-line"></i>
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
