@extends('back.layouts.master')
@section('title', 'Mon Profil')
@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    <style>
        .profile-avatar {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .avatar-initials {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 4rem;
            border: 4px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            background-color: transparent;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: none;
        }

        .info-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6c757d;
            font-weight: 500;
            min-width: 150px;
        }

        .info-value {
            color: #212529;
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
        }

        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.9;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: bold;
        }

        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
                    <h1 class="h3 mb-0">Mon Profil</h1>
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profil</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @include('back.layouts.partials.alert')
        <!-- Colonne gauche - Avatar et actions -->
        <div class="col-lg-4">
            <!-- Carte Avatar -->
            <div class="card mb-4">
                <div class="card-body text-center p-4">
                    <!-- Avatar -->
                    <div class="mb-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                 alt="Avatar de {{ $user->name }}"
                                 class="rounded-circle profile-avatar"
                                 id="profile-avatar">
                        @else
                            <div class="avatar-initials bg-primary text-white mx-auto"
                                 id="profile-avatar">
                                {{ strtoupper(substr($user->name ?: $user->email, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Nom et email -->
                    <h3 class="mb-2">{{ $user->name ?? 'Non renseigné' }}</h3>
                    <p class="text-muted mb-4">
                        <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                    </p>

                    <!-- Actions rapides -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary action-btn">
                            <i class="fas fa-edit me-2"></i> Modifier le profil
                        </a>

                    </div>
                </div>
            </div>


        </div>

        <!-- Colonne droite - Informations détaillées -->
        <div class="col-lg-8">
            <!-- Onglets -->
            <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ session('active_tab', 'personal') == 'personal' ? 'active' : '' }}"
                            id="personal-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#personal"
                            type="button"
                            role="tab">
                        <i class="fas fa-user me-2"></i>Informations personnelles
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ session('active_tab') == 'password' ? 'active' : '' }}"
                            id="password-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#password"
                            type="button"
                            role="tab">
                        <i class="fas fa-key me-2"></i>Mot de passe
                    </button>
                </li>
            </ul>

            <!-- Contenu des onglets -->
            <div class="tab-content" id="profileTabContent">
                <!-- Onglet Informations personnelles -->
                <div class="tab-pane fade {{ session('active_tab', 'personal') == 'personal' ? 'show active' : '' }}"
                     id="personal"
                     role="tabpanel">
                    @if(session('success') && session('active_tab') == 'personal')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Détails du profil</h5>
                            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item d-flex">
                                        <div class="info-label me-3">Nom complet:</div>
                                        <div class="info-value">{{ $user->name ?? 'Non renseigné' }}</div>
                                    </div>

                                    <div class="info-item d-flex">
                                        <div class="info-label me-3">Email:</div>
                                        <div class="info-value">
                                            <i class="fas fa-envelope me-2 text-muted"></i>{{ $user->email }}
                                        </div>
                                    </div>

                                    <div class="info-item d-flex">
                                        <div class="info-label me-3">Téléphone:</div>
                                        <div class="info-value">
                                            @if($user->phone)
                                                <i class="fas fa-phone me-2 text-muted"></i>{{ $user->phone }}
                                            @else
                                                <span class="text-muted">Non renseigné</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item d-flex">
                                        <div class="info-label me-3">Pays:</div>
                                        <div class="info-value">
                                            @if($user->pays)
                                                <i class="fas fa-flag me-2 text-muted"></i>{{ $user->pays->name }}
                                            @else
                                                <span class="text-muted">Non spécifié</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="info-item d-flex">
                                        <div class="info-label me-3">Adresse:</div>
                                        <div class="info-value">
                                            @if($user->address)
                                                <i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $user->address }}
                                            @else
                                                <span class="text-muted">Non renseignée</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="info-item d-flex">
                                        <div class="info-label me-3">Membre depuis:</div>
                                        <div class="info-value">
                                            <i class="fas fa-calendar me-2 text-muted"></i>
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onglet Mot de passe -->
                <div class="tab-pane fade {{ session('active_tab') == 'password' ? 'show active' : '' }}"
                     id="password"
                     role="tabpanel">
                    @if(session('success') && session('active_tab') == 'password')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Changer le mot de passe</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.update-password') }}" method="POST" id="password-form">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Mot de passe actuel
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control @error('current_password') is-invalid @enderror"
                                               id="current_password"
                                               name="current_password"
                                               required>
                                        <button class="btn btn-outline-secondary toggle-password"
                                                type="button"
                                                data-target="current_password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-key me-2"></i>Nouveau mot de passe
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password"
                                               required>
                                        <button class="btn btn-outline-secondary toggle-password"
                                                type="button"
                                                data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        Le mot de passe doit contenir au moins 8 caractères.
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-key me-2"></i>Confirmer le nouveau mot de passe
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               required>
                                        <button class="btn btn-outline-secondary toggle-password"
                                                type="button"
                                                data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Changer le mot de passe
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-2"></i>Réinitialiser
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Basculer la visibilité du mot de passe
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
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

    // Activer l'onglet approprié en fonction de l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');
    if (activeTab) {
        const tabTrigger = document.querySelector(`button[data-bs-target="#${activeTab}"]`);
        if (tabTrigger) {
            new bootstrap.Tab(tabTrigger).show();
        }
    }
});
</script>
@endsection
