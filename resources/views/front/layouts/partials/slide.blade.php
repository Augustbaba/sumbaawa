<!-- Bannière Hero avec effet parallaxe -->
<section class="p-0 position-relative overflow-hidden">
    <div class="furniture-hero-banner" style="
        background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
                    url('https://images.unsplash.com/photo-1555041469-a586c61ea9bc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
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
</section>
