@extends('back.layouts.master')

@section('title', 'Ajouter un Produit')

@section('content')
<div class="content">
    {{-- Affichage des messages d'alerte généraux --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Erreurs de validation</h5>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-globe2 small me-2"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Nouveau Produit</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-plus-circle me-2"></i>Nouveau Produit</h5>
            
            <form action="{{ route('produits.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sous_categorie_id" class="form-label">Sous-Catégorie *</label>
                            <select class="form-select @error('sous_categorie_id') is-invalid @enderror" 
                                    id="sous_categorie_id" name="sous_categorie_id" required>
                                <option value="">Sélectionnez une sous-catégorie</option>
                                @foreach($sousCategories as $sousCategorie)
                                    <option value="{{ $sousCategorie->id }}" {{ old('sous_categorie_id') == $sousCategorie->id ? 'selected' : '' }}>
                                        {{ $sousCategorie->label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sous_categorie_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du produit *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix (XOF) *</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Couleurs</label>
                            <div class="row">
                                @foreach(['Noir', 'Blanc', 'Bleu', 'Rouge', 'Vert', 'Jaune', 'Gris'] as $color)
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input @error('colors') is-invalid @enderror @error('colors.*') is-invalid @enderror" 
                                                   name="colors[]" value="{{ $color }}" 
                                                   id="color_{{ $color }}" {{ in_array($color, old('colors', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="color_{{ $color }}">{{ $color }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('colors')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('colors.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="niveau_confort" class="form-label">Niveau de confort</label>
                            <select class="form-select @error('niveau_confort') is-invalid @enderror" 
                                    id="niveau_confort" name="niveau_confort">
                                <option value="">Sélectionnez un niveau</option>
                                <option value="1" {{ old('niveau_confort') == '1' ? 'selected' : '' }}>1 - Basique</option>
                                <option value="2" {{ old('niveau_confort') == '2' ? 'selected' : '' }}>2 - Standard</option>
                                <option value="3" {{ old('niveau_confort') == '3' ? 'selected' : '' }}>3 - Confortable</option>
                                <option value="4" {{ old('niveau_confort') == '4' ? 'selected' : '' }}>4 - Premium</option>
                                <option value="5" {{ old('niveau_confort') == '5' ? 'selected' : '' }}>5 - Luxe</option>
                            </select>
                            @error('niveau_confort')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="poids" class="form-label">Poids (kg)</label>
                            <input type="number" step="0.1" class="form-control @error('poids') is-invalid @enderror" 
                                   id="poids" name="poids" value="{{ old('poids') }}">
                            @error('poids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="image_main" class="form-label">Image principale *</label>
                    <input type="file" class="form-control @error('image_main') is-invalid @enderror" 
                           id="image_main" name="image_main" accept="image/*" required>
                    @error('image_main')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('image_main.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Cette image sera affichée comme image principale du produit</small>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Images secondaires</label>
                    <div id="secondary-images-container">
                        <div class="input-group mb-2">
                            <input type="file" class="form-control @error('secondary_images') is-invalid @enderror @error('secondary_images.*') is-invalid @enderror" 
                                   name="secondary_images[]" accept="image/*">
                            <button type="button" class="btn btn-outline-danger remove-image" style="display: none;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" id="add-image-btn" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-plus"></i> Ajouter une image
                    </button>
                    @error('secondary_images')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    @error('secondary_images.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Vous pouvez ajouter plusieurs images supplémentaires</small>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('secondary-images-container');
    const addButton = document.getElementById('add-image-btn');
    
    addButton.addEventListener('click', function() {
        const newInputGroup = document.createElement('div');
        newInputGroup.className = 'input-group mb-2';
        newInputGroup.innerHTML = `
            <input type="file" class="form-control" name="secondary_images[]" accept="image/*">
            <button type="button" class="btn btn-outline-danger remove-image">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(newInputGroup);
        updateRemoveButtons();
    });
    
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-image')) {
            e.target.closest('.input-group').remove();
            updateRemoveButtons();
        }
    });
    
    function updateRemoveButtons() {
        const inputGroups = container.querySelectorAll('.input-group');
        inputGroups.forEach((group, index) => {
            const removeBtn = group.querySelector('.remove-image');
            if (inputGroups.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    // Afficher les boutons de suppression au chargement initial
    updateRemoveButtons();
});
</script>
@endsection