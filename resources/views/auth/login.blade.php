<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ FrontHelper::getAppName() }} - Connexion</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset(FrontHelper::getEnvFolder() . 'favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(FrontHelper::getEnvFolder() . 'favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(FrontHelper::getEnvFolder() . 'favicon_io/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset(FrontHelper::getEnvFolder() . 'favicon_io/site.webmanifest') }}">

    <!-- Themify icons -->
    <link rel="stylesheet" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/back/dist/icons/themify-icons/themify-icons.css') }}" type="text/css">

    <!-- Main style file -->
    <link rel="stylesheet" href="{{ asset(FrontHelper::getEnvFolder() . 'storage/back/dist/css/app.min.css') }}" type="text/css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js') }}"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js') }}"></script>
    <![endif]-->
</head>
<body class="auth">

<!-- begin::preloader-->
<div class="preloader">
    <div class="preloader-icon"></div>
</div>
<!-- end::preloader -->


    <div class="form-wrapper">
        <div class="container">
            <div class="card">
                <div class="row g-0">
                    <div class="col">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="d-block d-lg-none text-center text-lg-start">
                                    <img width="120" src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/logo.png') }}" alt="logo">
                                </div>
                                <div class="my-5 text-center text-lg-start">
                                    <h1 class="display-8">Se Connecter</h1>
                                    @include('back.layouts.partials.alert')
                                </div>
                                <form class="mb-5" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Entrer votre email" autofocus required>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" name="password" class="form-control" placeholder="Entrer votre mot de passe" required>
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="text-center text-lg-start">
                                        <p class="small"><a href="#">Mot de passe oublié?</a></p>
                                        <button class="btn btn-primary">Se Connecter</button>
                                    </div>
                                </form>
                                <div class="social-links justify-content-center">
                                    <a href="#">
                                        <i class="ti-google bg-google"></i> Se Connecter avec Google
                                    </a>
                                    <a href="#">
                                        <i class="ti-facebook bg-facebook"></i> Se Connecter avec Facebook
                                    </a>
                                </div>
                                <p class="text-center d-block d-lg-none mt-5 mt-lg-0">
                                    Nouveau sur {{ FrontHelper::getAppName() }}? <a href="{{ route('register') }}">S'inscrire</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col d-none d-lg-flex border-start align-items-center justify-content-between flex-column text-center">
                        <div class="logo">
                            <img width="220" src="{{ asset(FrontHelper::getEnvFolder() . 'storage/front/assets/images/logo.png') }}" alt="logo">
                        </div>
                        <div>
                            <h3 class="fw-bold">Bienvenu sur {{ FrontHelper::getAppName() }}!</h3>
                            <p class="lead my-5">Si vous n'avez pas de compte, souhaitez-vous vous inscrire dès maintenant ?</p>
                            <a href="{{ route('register') }}" class="btn btn-primary">S'inscrire</a>
                        </div>
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <a href="#">Politique de Confidentialité</a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#">Conditions Générales d'Utilisation</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- Bundle scripts -->
<script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/back/libs/bundle.js') }}"></script>

<!-- Main Javascript file -->
<script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/back/dist/js/app.min.js') }}"></script>
</body>

</html>
