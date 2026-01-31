@extends('back.layouts.master')
@section('title', 'Détails de l\'Utilisateur')
@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Détails de l'Utilisateur</h4>
                    <div class="btn-group">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Colonne de gauche : Avatar et informations principales -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <!-- Avatar de l'utilisateur -->
                                    <div class="mb-3">
                                        @if($user->avatar)
                                            <img src="{{ asset($user->avatar) }}"
                                                 alt="Avatar de {{ $user->name }}"
                                                 class="rounded-circle img-fluid"
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        @else
                                            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                                 style="width: 150px; height: 150px; font-size: 3rem;">
                                                {{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>

                                    <h3 class="card-title">{{ $user->name ?? 'Nom non renseigné' }}</h3>
                                    <p class="text-muted">{{ $user->email }}</p>

                                    <!-- Statut -->
                                    <div class="mb-3">
                                        {!! $user->status_label !!}
                                    </div>
                                </div>
                            </div>


                        </div>

                        <!-- Colonne de droite : Détails complets et commandes -->
                        <div class="col-md-8">
                            <!-- Informations personnelles -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0"><i class="fas fa-user-circle"></i> Informations Personnelles</h6>
                                            <span class="badge bg-dark">ID: #{{ $user->id }}</span>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-sm table-borderless">

                                                        <tr>
                                                            <th><i class="fas fa-phone me-2"></i>Téléphone:</th>
                                                            <td>
                                                                @if($user->phone)
                                                                    <a href="tel:{{ $user->phone }}" class="text-decoration-none">
                                                                        <i class="fas fa-phone-alt me-1"></i>{{ $user->phone }}
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">Non renseigné</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-sm table-borderless">
                                                        <tr>
                                                            <th class="w-50"><i class="fas fa-globe me-2"></i>Pays:</th>
                                                            <td>
                                                                @if($user->pays)
                                                                    <span class="badge bg-info">
                                                                        <i class="fas fa-flag"></i> {{ $user->pays->name }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted">Non spécifié</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><i class="fas fa-map-marker-alt me-2"></i>Adresse:</th>
                                                            <td>
                                                                @if($user->address)
                                                                    {{ $user->address }}
                                                                @else
                                                                    <span class="text-muted">Non renseignée</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tableau des commandes -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0"><i class="fas fa-clipboard-list"></i> Historique des Commandes</h6>
                                            <span class="badge bg-primary">
                                                {{ $commandes->count() }} commande(s)
                                            </span>
                                        </div>
                                        <div class="card-body p-0">
                                            @if($commandes->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="text-center">
                                                                    <i class="fas fa-hashtag"></i> Code
                                                                </th>
                                                                <th class="text-center">
                                                                    <i class="fas fa-calendar-alt"></i> Date
                                                                </th>
                                                                <th class="text-center">
                                                                    <i class="fas fa-cogs"></i> Actions
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($commandes as $commande)
                                                            <tr>
                                                                <td class="text-center align-middle">
                                                                    <span class="badge bg-dark">
                                                                        #{{ $commande->code ?? $commande->id }}
                                                                    </span>
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <small class="text-muted">
                                                                        {{ $commande->created_at->format('d/m/Y') }}
                                                                    </small>
                                                                    <br>
                                                                    <span class="small">{{ $commande->created_at->format('H:i') }}</span>
                                                                </td>


                                                                <td class="text-center align-middle">
                                                                    <div class="btn-group btn-group-sm" role="group">
                                                                        <a href="{{ route('admin.commandes.show', $commande) }}"
                                                                           class="btn btn-outline-primary"
                                                                           title="Voir les détails">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Pagination -->
                                                @if($commandes->hasPages())
                                                <div class="card-footer">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            {{ $commandes->links('vendor.pagination.bootstrap-5') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @else
                                                <div class="text-center py-5">
                                                    <div class="mb-3">
                                                        <i class="fas fa-shopping-cart fa-3x text-muted"></i>
                                                    </div>
                                                    <h5 class="text-muted">Aucune commande trouvée</h5>
                                                    <p class="text-muted">Cet utilisateur n'a pas encore passé de commande.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rôle et permissions -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="fas fa-user-shield"></i> Rôle et Permissions</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Rôle(s) attribué(s):</h6>
                                                    <div class="mb-3">
                                                        @foreach($user->roles as $role)
                                                            <span class="badge bg-primary mb-1 p-2" style="font-size: 0.9rem;">
                                                                <i class="fas fa-user-tag me-1"></i> {{ $role->name }}
                                                            </span>
                                                        @endforeach
                                                        @if($user->roles->count() == 0)
                                                            <span class="text-muted">
                                                                <i class="fas fa-exclamation-circle me-1"></i> Aucun rôle attribué
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-initials {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin: 0 auto;
    font-size: 4rem;
}

.permissions-list .badge {
    transition: all 0.3s ease;
}

.permissions-list .badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,0.02);
}

.table-borderless td, .table-borderless th {
    border: none;
    padding: 0.3rem 0;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {


    // Animation pour les badges au survol
    const badges = document.querySelectorAll('.badge');
    badges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        });

        badge.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
});
</script>
@endpush
@endsection



