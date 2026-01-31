@extends('back.layouts.master')
@section('title', 'Modifier le Profil')
@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    <style>
        .profile-avatar {
            width: 150px;
            height: 150px;
            object-fit: cover;
            cursor: pointer;
            border: 4px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .avatar-initials {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 3rem;
            cursor: pointer;
            border: 4px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .avatar-initials:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .avatar-upload-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #0d6efd;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .avatar-container {
            position: relative;
            display: inline-block;
        }

        .required-field::after {
            content: " *";
            color: #dc3545;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Modifier le Profil</h1>
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Modifier les informations personnelles</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
                        @csrf
                        @method('PUT')

                        <!-- Avatar -->
                        <div class="text-center mb-4">
                            <div class="avatar-container">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                         alt="Avatar"
                                         class="rounded-circle profile-avatar"
                                         id="avatar-preview"
                                         onclick="document.getElementById('avatar-input').click()">
                                @else
                                    <div class="avatar-initials bg-primary text-white mx-auto"
                                         id="avatar-preview"
                                         onclick="document.getElementById('avatar-input').click()">
                                        {{ strtoupper(substr($user->name ?: $user->email, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="avatar-upload-btn" onclick="document.getElementById('avatar-input').click()">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </div>
                            <input type="file"
                                   id="avatar-input"
                                   name="avatar"
                                   accept="image/*"
                                   class="d-none"
                                   onchange="previewAvatar(event)">
                            <div class="mt-2">
                                <small class="text-muted">Cliquez sur l'avatar pour changer l'image</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user me-2"></i>Nom complet
                                    </label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           placeholder="Votre nom complet">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label required-field">
                                        <i class="fas fa-envelope me-2"></i>Email
                                    </label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           required
                                           placeholder="votre@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-2"></i>Téléphone
                                    </label>
                                    <input type="tel"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', $user->phone) }}"
                                           placeholder="+33 1 23 45 67 89">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pays_id" class="form-label">
                                        <i class="fas fa-globe me-2"></i>Pays
                                    </label>
                                    <select class="form-select @error('pays_id') is-invalid @enderror"
                                            id="pays_id"
                                            name="pays_id">
                                        <option value="">Sélectionner un pays</option>
                                        @foreach($pays as $pay)
                                            <option value="{{ $pay->id }}"
                                                {{ old('pays_id', $user->pays_id) == $pay->id ? 'selected' : '' }}>
                                                {{ $pay->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pays_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt me-2"></i>Adresse
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="3"
                                      placeholder="Votre adresse complète">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewAvatar(event) {
    const input = event.target;
    const preview = document.getElementById('avatar-preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            // Remplacer l'élément par une image
            if (preview.tagName === 'DIV') {
                const newPreview = document.createElement('img');
                newPreview.id = 'avatar-preview';
                newPreview.className = 'rounded-circle profile-avatar';
                newPreview.src = e.target.result;
                newPreview.alt = 'Avatar preview';
                newPreview.onclick = function() {
                    document.getElementById('avatar-input').click();
                };
                preview.parentNode.replaceChild(newPreview, preview);
            } else {
                preview.src = e.target.result;
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Validation du formulaire
    const form = document.getElementById('profile-form');
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function(e) {
        // Vérifier l'email
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            e.preventDefault();
            showToast('error', 'Veuillez entrer une adresse email valide');
            return false;
        }

        // Vérifier le téléphone si rempli
        const phone = document.getElementById('phone').value;
        if (phone && !/^[0-9\-\+\s\(\)]+$/.test(phone)) {
            e.preventDefault();
            showToast('error', 'Format de téléphone invalide');
            return false;
        }

        // Afficher l'indicateur de chargement
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Enregistrement...';
        submitBtn.disabled = true;
    });

    // Fonction pour afficher les toasts
    function showToast(type, message) {
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        toastContainer.appendChild(toast);
        document.body.appendChild(toastContainer);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Nettoyer après la disparition du toast
        toast.addEventListener('hidden.bs.toast', function() {
            toastContainer.remove();
        });
    }
});
</script>
@endsection
