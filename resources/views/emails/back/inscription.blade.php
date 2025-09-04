@component('mail::layout')
{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        {{ config('app.name') }}
    @endcomponent
@endslot


Salut, {{ $user->firstname.' '.$user->lastname }} voici vos informations de connexion

Address email : {{ $user->email }}, 

Mot de passe : {{ $password }}

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        &copy; {{ date('Y') }}. Tous droits réservés.
    @endcomponent
@endslot
@endcomponent
