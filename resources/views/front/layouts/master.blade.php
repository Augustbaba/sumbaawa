@include('front.layouts.partials.start')
    @include('front.layouts.partials.header')
    @if (Route::currentRouteName() == 'index')
        @include('front.layouts.partials.slide')
    @else
        @include('front.layouts.partials.breadcrumbs')
    @endif

    @yield('content')


    @include('front.layouts.partials.footer')
@include('front.layouts.partials.end')
