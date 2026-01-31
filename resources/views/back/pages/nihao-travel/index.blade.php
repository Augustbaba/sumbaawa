@extends('back.layouts.master')

@section('title', 'Inscriptions Nihao Travel')

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
                <li class="breadcrumb-item active" aria-current="page">Nihao Travel</li>
            </ol>
        </nav>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Inscriptions</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Paiements reçus</h6>
                            <h3 class="mb-0">{{ $stats['paid'] }}</h3>
                        </div>
                        <i class="bi bi-cash-coin fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.nihao-travel.index') }}" class="row g-3">
                {{-- <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En traitement</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Statut paiement</label>
                    <select name="payment_status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                        <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                    </select>
                </div> --}}

                <div class="col-md-3">
                    <label class="form-label">Édition</label>
                    <select name="canton_edition" class="form-select">
                        <option value="">Toutes les éditions</option>
                        @foreach($editions as $edition)
                            <option value="{{ $edition }}" {{ request('canton_edition') == $edition ? 'selected' : '' }}>
                                {{ $edition }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Recherche</label>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Nom, email, téléphone..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel me-2"></i>Filtrer
                            </button>
                            <a href="{{ route('admin.nihao-travel.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Réinitialiser
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('admin.nihao-travel.export', request()->query()) }}"
                               class="btn btn-success">
                                <i class="bi bi-download me-2"></i>Exporter CSV
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des inscriptions -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title">
                    <i class="bi bi-airplane me-2"></i>Liste des Inscriptions
                </h5>
                <div class="text-muted">
                    {{ $travels->total() }} inscription(s) trouvée(s)
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover mb-2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Client</th>
                            <th>Édition</th>
                            <th>Montant</th>
                            <th>Paiement</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($travels as $key => $travel)
                            <tr>
                                <td>{{ $travels->firstItem() + $key }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $travel->code }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ $travel->last_name }} {{ $travel->first_name }}</h6>
                                            <small class="text-muted">{{ $travel->email }}</small>
                                            <br>
                                            <small>{{ $travel->phone }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $travel->canton_edition }}</span>
                                </td>
                                <td>
                                    <div class="text-nowrap">
                                        <strong>{{ number_format($travel->amount_xof, 0, ',', ' ') }} XOF</strong>
                                        <br>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $paymentColors = [
                                            'pending' => 'warning',
                                            'paid' => 'success',
                                            'failed' => 'danger',
                                            'refunded' => 'secondary'
                                        ];
                                        $paymentLabels = [
                                            'pending' => 'En attente',
                                            'paid' => 'Payé',
                                            'failed' => 'Échoué',
                                            'refunded' => 'Remboursé'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $paymentColors[$travel->payment_status] ?? 'secondary' }}">
                                        {{ $paymentLabels[$travel->payment_status] ?? $travel->payment_status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-nowrap">
                                        {{ $travel->created_at->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">{{ $travel->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.nihao-travel.show', $travel->id) }}"
                                           class="btn btn-sm btn-info"
                                           data-bs-toggle="tooltip"
                                           title="Voir détails">
                                            <i class="bi bi-eye"></i>
                                        </a>


                                    </div>


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                    Aucune inscription trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($travels->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    {{ $travels->links('vendor.pagination.bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialiser les tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
@endsection
