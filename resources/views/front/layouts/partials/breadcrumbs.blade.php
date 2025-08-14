<!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <h2>@yield('title')</h2>
            <nav class="theme-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}">Accueil</a>
                    </li>
                    @yield('filAriane')

                </ol>
            </nav>
        </div>
    </div>
    <!-- breadcrumb End -->
