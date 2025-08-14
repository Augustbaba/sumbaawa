<!-- Bannière Hero avec carrousel en arrière-plan -->
<section class="p-0 position-relative overflow-hidden">
    <div class="hero-carousel">
        <!-- Slide 1 (votre slide originale) -->
        <div class="carousel-slide active" style="
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                        url('https://images.unsplash.com/photo-1555041469-a586c61ea9bc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
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
                            <span class="d-block mb-2 text-uppercase letter-spacing-3" style="color: #b78d65; font-size: 0.9rem;">Nouvelle Collection</span>
                            <h1 class="mb-3" style="font-size: 2.8rem; font-weight: 300; line-height: 1.2;">Élégance intemporelle<br>pour votre intérieur</h1>
                            <p class="lead mb-4" style="color: #555;">Découvrez nos pièces exclusives conçues par des artisans d'exception.</p>
                            <a href="{{ route('categories.page') }}" class="btn btn-dark btn-lg px-5 py-3">Explorer la collection</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 (Promotion spéciale) -->
        <div class="carousel-slide" style="
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                        url('https://images.unsplash.com/photo-1517705008128-361805f42e86?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
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
                            <span class="d-block mb-2 text-uppercase letter-spacing-3" style="color: #e74c3c; font-size: 0.9rem;">Promotion exclusive</span>
                            <h1 class="mb-3" style="font-size: 2.8rem; font-weight: 300; line-height: 1.2;">Fauteuil design<br>à prix exceptionnel</h1>
                            <p class="lead mb-4" style="color: #555;">Profitez de -30% sur notre best-seller seulement cette semaine.</p>

                            <!-- Compteur pour la promotion -->
                            <div class="countdown-container mb-4">
                                <div class="countdown-box">
                                    <span class="countdown-number" id="days">00</span>
                                    <span class="countdown-label">Jours</span>
                                </div>
                                <div class="countdown-box">
                                    <span class="countdown-number" id="hours">00</span>
                                    <span class="countdown-label">Heures</span>
                                </div>
                                <div class="countdown-box">
                                    <span class="countdown-number" id="minutes">00</span>
                                    <span class="countdown-label">Minutes</span>
                                </div>
                                <div class="countdown-box">
                                    <span class="countdown-number" id="seconds">00</span>
                                    <span class="countdown-label">Secondes</span>
                                </div>
                            </div>

                            <a href="#" class="btn btn-danger btn-lg px-5 py-3" style="background-color: #e74c3c; border-color: #e74c3c;">Voir l'offre</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 (Nouveautés) -->
        <div class="carousel-slide" style="
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                        url('https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
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
                            <span class="d-block mb-2 text-uppercase letter-spacing-3" style="color: #3498db; font-size: 0.9rem;">Nouveautés</span>
                            <h1 class="mb-3" style="font-size: 2.8rem; font-weight: 300; line-height: 1.2;">Découvrez nos<br>créations printemps</h1>
                            <p class="lead mb-4" style="color: #555;">Une collection fraîche et moderne pour renouveler votre espace.</p>
                            <a href="#" class="btn btn-primary btn-lg px-5 py-3" style="background-color: #3498db; border-color: #3498db;">Découvrir</a>
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
