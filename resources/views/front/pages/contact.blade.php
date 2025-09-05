@php
    use Illuminate\Support\Str;
@endphp

@extends('front.layouts.master')
@section('title', 'Contactez-nous')
@section('filAriane')
    <li class="breadcrumb-item active" aria-current="page">Contactez-nous</li>
@endsection
@section('content')
    <section class="contact-page">
        <div class="container">
            <div class="row g-sm-4 g-3">
                <div class="col-lg-5">
                    <div class="contact-title">
                        <h2>Contactez-nous</h2>
                        <p>Nous sommes là pour vous aider ! N'hésitez pas à Contactez-nous pour toute question, commentaire ou demande de renseignements. Nous vous répondrons dans les plus brefs délais.</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form class="theme-form contact-form" method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="form-box">
                                    <label for="name" class="form-label">Nom & Prénom(s)</label>
                                    <input type="text" id="name" class="form-control" value="{{ old('name') }}" placeholder="Nom & Prénom(s)" name="name">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-box">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Email" name="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-box">
                                    <label for="review">Téléphone</label>
                                    <input type="number" id="review" class="form-control" value="{{ old('phone') }}" placeholder="Téléphone" name="phone">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-box">
                                    <label for="subject">Objet</label>
                                    <input type="text" id="subject" class="form-control" value="{{ old('subject') }}" placeholder="Objet" name="subject">
                                    @error('subject')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-box">
                                    <label for="review">Message</label>
                                    <textarea rows="6" class="form-control" name="message" required placeholder="Ecrire un message">{{ old('message') }}</textarea>
                                    @error('message')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-box">
                                    <button class="btn btn-solid" type="submit">Envoyer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12">
                    <div class="contact-right">
                        <ul>
                            <li>
                                <div class="contact-icon">
                                    <i class="ri-phone-fill"></i>
                                </div>
                                <div class="media-body">
                                    <h6>Téléphone</h6>
                                    <p>+242044724102</p>
                                </div>
                            </li>
                            <li>
                                <div class="contact-icon">
                                    <i class="ri-map-pin-fill"></i>
                                </div>
                                <div class="media-body">
                                    <h6>Adresse</h6>
                                    <p>Sumba Awa Address</p>
                                </div>
                            </li>
                            <li>
                                <div class="contact-icon">
                                    <i class="ri-mail-fill"></i>
                                </div>
                                <div class="media-body">
                                    <h6>Email</h6>
                                    <p>support@sumbaawa.com</p>
                                </div>
                            </li>
                            <li>
                                <div class="contact-icon">
                                    <i class="ri-cellphone-fill"></i>
                                </div>
                                <div class="media-body">
                                    <h6>Call Center</h6>
                                    <p>+242044724102</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--contact section ends-->


    <!-- map section start -->
    <section class="map-section">
        <iframe frameborder="0" class="w-100 h-100"
            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1605.811957341231!2d25.45976406005396!3d36.3940974010114!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1550912388321"></iframe>
    </section>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/js/cart.js') }}"></script>
    <script>
        // Charger jQuery 3.6.0 si nécessaire
        window.jQuery || document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"><\/script>');
    </script>
    <script>
        (function($) {
            $(document).ready(function () {
                // Initialiser le compteur du panier
                cartUtils.initializeCartCount();

                // Initialiser la table du panier au chargement
                $.ajax({
                    url: '{{ route('cart.get') }}',
                    method: 'GET',
                    success: function (response) {
                        cartUtils.updateCartTable(response.cart);
                        cartUtils.updateCartOffcanvas(response.cart);
                    },
                    error: function (xhr) {
                        console.error('Erreur lors de la récupération du panier:', xhr.responseText);
                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: "error",
                            title: "Erreur lors de la récupération du panier"
                        });
                    }
                });
            });
        })(jQuery);

        // Success message with SweetAlert2
        document.addEventListener('DOMContentLoaded', function() {
            const status_contact_success = '{{ session('contact_success') }}';
            if (status_contact_success) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    icon: "success",
                    title: status_contact_success
                }).then(() => {
                    window.history.pushState({}, document.title, window.location.pathname);
                });
            }

        });
    </script>
@endsection
