@extends('back.layouts.master')

@section('title', 'Modifier un Produit')

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
                <li class="breadcrumb-item active" aria-current="page">Modifier</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-pencil me-2"></i>Modifier le Produit</h5>

            <form action="{{ route('produits.update', $produit->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sous_categorie_id" class="form-label">Sous-Catégorie *</label>
                            <select class="form-select @error('sous_categorie_id') is-invalid @enderror"
                                    id="sous_categorie_id" name="sous_categorie_id" required>
                                <option value="">Sélectionnez une sous-catégorie</option>
                                @foreach($sousCategories as $sousCategorie)
                                    <option value="{{ $sousCategorie->id }}" {{ old('sous_categorie_id', $produit->sous_categorie_id) == $sousCategorie->id ? 'selected' : '' }}>
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
                                   id="name" name="name" value="{{ old('name', $produit->name) }}" required>
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
                                   id="price" name="price" value="{{ old('price', $produit->price) }}" required>
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
                                            <input type="checkbox" class="form-check-input"
                                                   name="colors[]" value="{{ $color }}"
                                                   id="color_{{ $color }}"
                                                   {{ in_array($color, old('colors', explode(',', $produit->color ?? ''))) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="color_{{ $color }}">{{ $color }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('colors')
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
                                <option value="1" {{ old('niveau_confort', $produit->niveau_confort) == '1' ? 'selected' : '' }}>1 - Basique</option>
                                <option value="2" {{ old('niveau_confort', $produit->niveau_confort) == '2' ? 'selected' : '' }}>2 - Standard</option>
                                <option value="3" {{ old('niveau_confort', $produit->niveau_confort) == '3' ? 'selected' : '' }}>3 - Confortable</option>
                                <option value="4" {{ old('niveau_confort', $produit->niveau_confort) == '4' ? 'selected' : '' }}>4 - Premium</option>
                                <option value="5" {{ old('niveau_confort', $produit->niveau_confort) == '5' ? 'selected' : '' }}>5 - Luxe</option>
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
                                   id="poids" name="poids" value="{{ old('poids', $produit->poids) }}">
                            @error('poids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="3">{{ old('description', $produit->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image_main" class="form-label">Image principale</label>
                    <input type="file" class="form-control @error('image_main') is-invalid @enderror"
                           id="image_main" name="image_main" accept="image/jpeg,image/png,image/jpg,image/gif">
                    @error('image_main')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($produit->image_main)
                        <div class="mt-2">
                            <img src="{{ asset($produit->image_main) }}"
                                 alt="{{ $produit->name }}"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                    @endif
                    <small class="text-muted">Laissez vide pour conserver l'image actuelle</small>
                </div>

                <div class="mb-4">
                    <label class="form-label">Images secondaires</label>
                    <div id="secondary-images-container">
                        <div class="input-group mb-2">
                            <input type="file" class="form-control" name="secondary_images[]" accept="image/jpeg,image/png,image/jpg,image/gif">
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
                    <small class="text-muted">Ajoutez jusqu'à 5 images secondaires</small>
                    @if ($produit->images->isNotEmpty())
                        <div class="mt-3">
                            <label class="form-label">Images secondaires actuelles</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($produit->images as $image)
                                    <div class="position-relative">
                                        <img src="{{ asset($image->url) }}"
                                             alt="{{ $produit->name }}"
                                             style="width: 100px; height: 100px; object-fit: cover;">
                                        <div class="position-absolute top-0 end-0">
                                            <input type="checkbox" class="form-check-input"
                                                   name="delete_images[]" value="{{ $image->id }}"
                                                   id="delete_image_{{ $image->id }}">
                                            <label class="form-check-label text-danger"
                                                   for="delete_image_{{ $image->id }}">
                                                Supprimer
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Mettre à jour
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
    const maxImages = 5;

    addButton.addEventListener('click', function() {
        const inputGroups = container.querySelectorAll('.input-group');
        if (inputGroups.length >= maxImages) {
            alert('Vous ne pouvez pas ajouter plus de ' + maxImages + ' images secondaires.');
            return;
        }
        const newInputGroup = document.createElement('div');
        newInputGroup.className = 'input-group mb-2';
        newInputGroup.innerHTML = `
            <input type="file" class="form-control" name="secondary_images[]" accept="image/jpeg,image/png,image/jpg,image/gif">
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

    container.addEventListener('change', function(e) {
        if (e.target.type === 'file') {
            const file = e.target.files[0];
            if (file && !['image/jpeg', 'image/png', 'image/jpg', 'image/gif'].includes(file.type)) {
                alert('Veuillez sélectionner une image au format JPEG, PNG, JPG ou GIF.');
                e.target.value = '';
            }
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

    updateRemoveButtons();
});
</script>
@endsection