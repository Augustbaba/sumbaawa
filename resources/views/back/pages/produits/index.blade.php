@extends('back.layouts.master')

@section('title', 'Liste des Produits')

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
                <li class="breadcrumb-item active" aria-current="page">Produits</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex gap-4 align-items-center">
                        <div class="d-none d-md-flex">Tous les produits</div>
                        <div class="d-md-flex gap-4 align-items-center">
                            <form class="mb-3 mb-md-0">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <select class="form-select">
                                            <option>Trier par</option>
                                            <option value="desc">Récent</option>
                                            <option value="asc">Ancien</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="dropdown ms-auto">
                            <a href="{{ route('produits.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> Ajouter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-custom table-lg mb-0" id="products">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Sous-catégorie</th>
                        <th>Prix</th>
                        <th>Date création</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($produits as $produit)
                    <tr>
                        <td>{{ $produit->id }}</td>
                        <td>
                            @if($produit->image_main)
                            <img src="{{ asset('storage/'.$produit->image_main) }}" class="rounded" width="40" alt="{{ $produit->name }}">
                            @else
                            <span class="text-muted">Aucune image</span>
                            @endif
                        </td>
                        <td>{{ $produit->name }}</td>
                        <td>{{ $produit->sousCategorie->label ?? 'Non classé' }}</td>
                        <td>{{ number_format($produit->price, 2, ',', ' ') }} XOF</td>
                        <td>{{ $produit->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('produits.destroy', $produit->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $produits->links() }}
            </div>
        </div>
    </div>
</div>
@endsection