@extends('front.layouts.master')
@section('title', 'Paiement annulé')

@section('content')
<div class="container" style="max-width: 800px; margin: 4rem auto; text-align: center;">
    <div style="background: white; padding: 3rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
        <i class="ri-close-circle-fill" style="font-size: 5rem; color: #f44336;"></i>
        <h1 style="margin: 1.5rem 0; color: #1a1a1a;">Paiement annulé</h1>

        <p style="font-size: 1.1rem; margin: 2rem 0;">
            Vous avez annulé le processus de paiement. Votre panier a été conservé.
        </p>

        <div style="margin-top: 2rem;">
            <a href="{{ route('cart.view') }}" class="btn" style="
                background: #b78d65;
                color: white;
                padding: 0.8rem 2rem;
                border-radius: 6px;
                text-decoration: none;
                display: inline-block;
                margin: 0 0.5rem;
                transition: all 0.3s;
            ">Retour au panier</a>

            <a href="{{ route('index') }}" class="btn" style="
                background: #2a2a2a;
                color: white;
                padding: 0.8rem 2rem;
                border-radius: 6px;
                text-decoration: none;
                display: inline-block;
                margin: 0 0.5rem;
                transition: all 0.3s;
            ">Continuer mes achats</a>
        </div>
    </div>
</div>
@endsection
