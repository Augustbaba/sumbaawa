@extends('back.layouts.master')

@section('title', 'Détails de la Sous-Catégorie')

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
                    <a href="{{ route('sous-categories.index') }}">Sous-Catégories</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Détails</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-eye me-2"></i>Détails de la Sous-Catégorie</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Nom</strong></label>
                        <p>{{ $sousCategorie->label }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Slug</strong></label>
                        <p>{{ $sousCategorie->slug }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Catégorie Parentale</strong></label>
                        <p>{{ $sousCategorie->categorie ? $sousCategorie->categorie->label : 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Date de création</strong></label>
                        <p>{{ $sousCategorie->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><strong>Dernière mise à jour</strong></label>
                        <p>{{ $sousCategorie->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('sous-categories.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i> Retour
                </a>
                <a href="{{ route('sous-categories.edit', $sousCategorie) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i> Modifier
                </a>
            </div>
        </div>
    </div>
</div>
@endsection