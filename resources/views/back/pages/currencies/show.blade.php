@extends('back.layouts.master')

@section('title', 'Détails de la devise : ' . $currency->name)

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
                    <a href="{{ route('admin.currencies.index') }}">
                        <i class="ri-money-dollar-circle-line me-2"></i> Devises
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $currency->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ri-money-dollar-circle-line me-2"></i>{{ $currency->name }} ({{ $currency->code }})
                        </h5>
                        <span class="badge
                            @if($currency->is_default) bg-warning
                            @elseif($currency->is_active) bg-success
                            @else bg-secondary @endif">
                            @if($currency->is_default) Devise par défaut
                            @elseif($currency->is_active) Active
                            @else Inactive
                            @endif
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informations principales -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Informations de base</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Code ISO :</strong></td>
                                            <td>
                                                <span class="badge bg-dark">{{ $currency->code }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Symbole :</strong></td>
                                            <td>{{ $currency->symbol }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nom complet :</strong></td>
                                            <td>{{ $currency->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Taux de change</h6>
                                    <div class="text-center py-3">
                                        <h3 class="text-primary mb-2">
                                            1 {{ $currency->code }} = {{ number_format($currency->exchange_rate, 4) }} XOF
                                        </h3>
                                        <p class="text-muted mb-0">
                                            1 XOF = {{ number_format(1 / $currency->exchange_rate, 6) }} {{ $currency->code }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Exemples de conversion -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="ri-calculator-line me-2"></i>Exemples de conversion
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <h4 class="text-success">100 {{ $currency->code }}</h4>
                                        <p class="mb-0">=</p>
                                        <h5 class="text-primary">{{ number_format(100 * $currency->exchange_rate, 2) }} XOF</h5>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <h4 class="text-success">500 {{ $currency->code }}</h4>
                                        <p class="mb-0">=</p>
                                        <h5 class="text-primary">{{ number_format(500 * $currency->exchange_rate, 2) }} XOF</h5>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <h4 class="text-success">1000 XOF</h4>
                                        <p class="mb-0">=</p>
                                        <h5 class="text-primary">{{ number_format(1000 / $currency->exchange_rate, 2) }} {{ $currency->code }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <h4 class="text-success">5000 XOF</h4>
                                        <p class="mb-0">=</p>
                                        <h5 class="text-primary">{{ number_format(5000 / $currency->exchange_rate, 2) }} {{ $currency->code }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="ri-bar-chart-line me-2"></i>Statistiques
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <h6>Utilisation</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Utilisateurs ayant cette devise :</span>
                                            <span class="badge bg-info fs-6">{{ $userCount }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert
                                        @if($currency->is_active) alert-success
                                        @else alert-secondary @endif">
                                        <h6>Statut</h6>
                                        <p class="mb-0">
                                            Créée le : <strong>{{ $currency->created_at }}</strong><br>
                                            Dernière modification : <strong>{{ $currency->updated_at }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-2"></i>Retour à la liste
                        </a>
                        <div class="btn-group">
                            <a href="{{ route('admin.currencies.edit', $currency->id) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-2"></i>Modifier
                            </a>
                            {{-- @if(!$currency->is_default)
                            <form action="{{ route('admin.currencies.setDefault', $currency->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Définir cette devise comme par défaut ?')">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="ri-star-line me-2"></i>Définir par défaut
                                </button>
                            </form>
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="ri-settings-line me-2"></i>Actions rapides
                    </h6>

                    <div class="d-grid gap-2">
                        @if(!$currency->is_default)
                        <form action="{{ route('admin.currencies.toggleActive', $currency->id) }}"
                              method="POST"
                              class="d-grid">
                            @csrf
                            <button type="submit"
                                    class="btn
                                        @if($currency->is_active) btn-outline-warning
                                        @else btn-outline-success @endif">
                                <i class="ri-toggle-line me-2"></i>
                                @if($currency->is_active)
                                    Désactiver la devise
                                @else
                                    Activer la devise
                                @endif
                            </button>
                        </form>
                        @endif

                        {{-- <a href="{{ route('admin.currencies.updateRatesForm') }}" class="btn btn-outline-info">
                            <i class="ri-refresh-line me-2"></i>Mettre à jour les taux
                        </a> --}}

                        {{-- @if(!$currency->is_default)
                        <button type="button"
                                class="btn btn-outline-danger"
                                onclick="confirmDelete()">
                            <i class="ri-delete-bin-line me-2"></i>Supprimer la devise
                        </button>
                        @endif --}}
                    </div>

                    {{-- @if(!$currency->is_default)
                    <form id="deleteForm"
                          action="{{ route('admin.currencies.destroy', $currency->id) }}"
                          method="POST"
                          class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif --}}
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="ri-information-line me-2"></i>Informations techniques
                    </h6>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>ID :</span>
                            <code>{{ $currency->id }}</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Code ISO :</span>
                            <code>{{ $currency->code }}</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Symbole HTML :</span>
                            <code>&amp;{{ $currency->symbol }};</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Taux brut :</span>
                            <code>{{ $currency->exchange_rate }}</code>
                        </li>
                    </ul>

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Cette devise est utilisée par {{ $userCount }} utilisateur(s). Ils seront basculés vers la devise par défaut.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    }
</script>

<style>
    .card.border {
        border-color: #dee2e6 !important;
    }

    .card.border .card-body {
        padding: 1rem;
    }

    .border.rounded.p-3 {
        transition: all 0.3s;
    }

    .border.rounded.p-3:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection
