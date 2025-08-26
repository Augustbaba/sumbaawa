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

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title"><i class="bi bi-list-ul me-2"></i>Liste des Produits</h5>
                <a href="{{ route('produits.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Nouveau Produit
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Sous-Catégorie</th>
                            <th>Prix (XOF)</th>
                            <th>Image</th>
                            <th>Couleurs</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produits as $key => $produit)
                            <tr>
                                <td>{{ $produits->firstItem() + $key }}</td>
                                <td>{{ $produit->name }}</td>
                                <td>{{ $produit->sousCategorie ? $produit->sousCategorie->label : 'N/A' }}</td>
                                <td>{{ number_format($produit->price, 2) }}</td>
                                <td>
                                    @if ($produit->image_main)
                                        <img src="{{ asset('storage/' . $produit->image_main) }}" 
                                             alt="{{ $produit->name }}" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        Aucune image
                                    @endif
                                </td>
                                <td>{{ $produit->color ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('produits.show', $produit->id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('produits.edit', $produit->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('produits.destroy', $produit->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer ce produit ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucun produit trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $produits->links() }}
            </div>
        </div>
    </div>
</div>
@endsection