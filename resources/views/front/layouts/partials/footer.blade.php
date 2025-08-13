<!-- Footer Section Start -->
<footer class="footer-style-1">
    <section class="section-b-space darken-layout">
        <div class="container">
            <div class="row footer-theme g-md-5 g-2">
                <div class="col-xl-3 col-lg-4 col-md-6 sub-title">
                    <div>
                        <div class="footer-logo"><a href="index.html"><img alt="logo" style="width: 150px; height: auto;" class="img-fluid"
                                    src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/logo.png') }}">
                            </a></div>
                        <p>Découvrez les dernières tendances meubles avec nos collections exclusives.</p>
                        <ul class="contact-list">
                            <li><i class="ri-map-pin-line"></i>Sumba Awa Address</li>
                            <li><i class="ri-phone-line"></i>123-456-7898</li>
                            <li><i class="ri-mail-line"></i>support@sumbaawa.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Catégories</h4>
                        </div>
                        <div class="footer-content">
                            <ul>
                                @foreach (FrontHelper::fiveCategoriesForFooter() as $categorie)
                                    <li><a href="{{ route('categories.single', $categorie) }}" class="text-content">{{ $categorie->label }}</a></li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Liens Rapides</h4>
                        </div>
                        <div class="footer-content">
                            <ul>
                                @auth()
                                    <li><a class="text-content" href="{{ route('logout') }}">Se Déconnecter</a></li>
                                    <li><a class="text-content" target="_blank" href="{{ route('dashboard') }}">Tableau de bord</a></li>
                                @endauth
                                @guest()
                                    <li><a class="text-content" href="{{ route('register') }}">S'inscrire</a></li>
                                    <li><a class="text-content" href="{{ route('login') }}">Se Connecter</a></li>
                                @endguest
                                <li><a class="text-content" href="">Favoris</a></li>
                                <li><a class="text-content" href="">FAQ</a></li>
                                <li><a class="text-content" href="#">Nous Contacter</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>Suivez-nous</h4>
                        </div>
                        <div class="footer-content">
                            <p class="mb-cls-content">Ne manquez rien de notre boutique en vous inscrivant à notre newsletter.</p>
                            <form novalidate="" class="form-inline">
                                <div class="form-group me-sm-3 mb-2">
                                    <input type="email" class="form-control" placeholder="Entrez votre email">
                                </div>
                                <button class="btn btn-solid mb-2">S'inscrire</button>
                            </form>
                            <div class="footer-social">
                                <ul>
                                    <li>
                                        <a target="_blank" href="https://facebook.com/">
                                            <i class="ri-facebook-fill"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="https://twitter.com/">
                                            <i class="ri-twitter-fill"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="https://instagram.com/">
                                            <i class="ri-instagram-fill"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="https://pinterest.com/">
                                            <i class="ri-pinterest-fill"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="sub-footer dark-subfooter">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="footer-end">
                            <p>{{ FrontHelper::getAppName() }} <i class="ri-copyright-line"></i> Tous droits réservés {{ date('Y') }}.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->
