<section class="p-0 position-relative overflow-hidden">
    <div class="hero-carousel">
        <!-- Slide 1 : Meubles -->
        <div class="carousel-slide active" style="
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                        url('{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/banner1.jpg') }}');
            background-size: cover;
            background-position: center;
            min-height: 80vh;
            display: flex;
            align-items: center;
        ">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="p-4 p-lg-5" style="
                            background: rgba(255,255,255,0.9);
                            border-radius: 5px;
                            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
                        ">
                            <span class="d-block mb-2 text-uppercase letter-spacing-3" style="color: #b78d65; font-size: 0.9rem;">Élégance intérieure</span>
                            <h1 class="mb-3" style="font-size: 2.8rem; font-weight: 300; line-height: 1.2;">Sublimez<br>vos espaces</h1>
                            <p class="lead mb-4" style="color: #555;">Découvrez nos meubles raffinés alliant confort, design et durabilité.</p>
                            <a href="{{ route('categories.page') }}" class="btn btn-dark btn-lg px-5 py-3">Explorer la collection</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 : Bijoux -->
        <div class="carousel-slide" style="
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                        url('{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/banner2.jpg') }}');
            background-size: cover;
            background-position: center;
            min-height: 80vh;
            display: flex;
            align-items: center;
        ">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="p-4 p-lg-5" style="
                            background: rgba(255,255,255,0.9);
                            border-radius: 5px;
                            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
                        ">
                            <span class="d-block mb-2 text-uppercase letter-spacing-3" style="color: #c0392b; font-size: 0.9rem;">Éclat & Raffinement</span>
                            <h1 class="mb-3" style="font-size: 2.8rem; font-weight: 300; line-height: 1.2;">Bijoux uniques<br>pour chaque style</h1>
                            <p class="lead mb-4" style="color: #555;">Laissez-vous séduire par des créations authentiques, élégantes et intemporelles.</p>
                            <a href="{{ route('categories.page') }}" class="btn btn-danger btn-lg px-5 py-3" style="background-color: #c0392b; border-color: #c0392b;">Découvrir nos bijoux</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 : Véhicules -->
        <div class="carousel-slide" style="
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                        url('{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/banner3.jpg') }}');
            background-size: cover;
            background-position: center;
            min-height: 80vh;
            display: flex;
            align-items: center;
        ">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="p-4 p-lg-5" style="
                            background: rgba(255,255,255,0.9);
                            border-radius: 5px;
                            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
                        ">
                            <span class="d-block mb-2 text-uppercase letter-spacing-3" style="color: #2980b9; font-size: 0.9rem;">Puissance & Style</span>
                            <h1 class="mb-3" style="font-size: 2.8rem; font-weight: 300; line-height: 1.2;">Découvrez<br>nos véhicules</h1>
                            <p class="lead mb-4" style="color: #555;">Une sélection de véhicules alliant performance, confort et design moderne.</p>
                            <a href="{{ route('categories.page') }}" class="btn btn-primary btn-lg px-5 py-3" style="background-color: #2980b9; border-color: #2980b9;">Explorer les modèles</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contrôles du carrousel -->
    <button class="carousel-control prev" aria-label="Précédent">&#10094;</button>
    <button class="carousel-control next" aria-label="Suivant">&#10095;</button>

    <!-- Indicateurs de slide -->
    <div class="carousel-indicators">
        <button class="indicator active" aria-label="Afficher la slide 1"></button>
        <button class="indicator" aria-label="Afficher la slide 2"></button>
        <button class="indicator" aria-label="Afficher la slide 3"></button>
    </div>
</section>
