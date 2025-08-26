@extends('back.layouts.master')

@section('title', 'Détails du Produit')

@section('content')
<div class="content">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-globe2 small me-2"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('produits.index') }}">Produits</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Détails</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-eye me-2"></i>Détails du Produit</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Nom</strong></label>
                        <p>{{ $produit->name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Slug</strong></label>
                        <p>{{ $produit->slug }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Sous-Catégorie</strong></label>
                        <p>{{ $produit->sousCategorie ? $produit->sousCategorie->label : 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Prix (XOF)</strong></label>
                        <p>{{ number_format($produit->price, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Couleurs</strong></label>
                        <p>{{ $produit->color ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Niveau de confort</strong></label>
                        <p>
                            @if ($produit->niveau_confort)
                                {{ $produit->niveau_confort }} - 
                                {{ ['1' => 'Basique', '2' => 'Standard', '3' => 'Confortable', '4' => 'Premium', '5' => 'Luxe'][$produit->niveau_confort] }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Poids (kg)</strong></label>
                        <p>{{ $produit->poids ? number_format($produit->poids, 2) : 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Description</strong></label>
                        <p>{{ $produit->description ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Image principale</strong></label>
                        @if ($produit->image_main)
                            <img src="{{ asset('storage/' . $produit->image_main) }}" 
                                 alt="{{ $produit->name }}" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <p>Aucune image</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Images secondaires</strong></label>
                        @if ($produit->images->isNotEmpty())
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($produit->images as $image)
                                    <img src="{{ asset('storage/' . $image->url) }}" 
                                         alt="{{ $produit->name }}" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                @endforeach
                            </div>
                        @else
                            <p>Aucune image secondaire</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Date de création</strong></label>
                        <p>{{ $produit->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Dernière mise à jour</strong></label>
                        <p>{{ $produit->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i> Retour
                </a>
                <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i> Modifier
                </a>
            </div>
        </div>
    </div>
</div>
@endsection