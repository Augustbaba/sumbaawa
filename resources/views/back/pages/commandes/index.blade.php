@extends('back.layouts.master')

@section('title', 'Gestion des Commandes')

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
                <li class="breadcrumb-item active" aria-current="page">Commandes</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total</h6>
                            <h2 class="mb-0">{{ $commandes->total() }}</h2>
                        </div>
                        <i class="bi bi-cart-check display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">En attente</h6>
                            <h2 class="mb-0">{{ App\Models\Commande::where('status', 'pending')->count() }}</h2>
                        </div>
                        <i class="bi bi-clock-history display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Livrées</h6>
                            <h2 class="mb-0">{{ App\Models\Commande::where('status', 'delivered')->count() + App\Models\Commande::where('status', 'picked_up')->count() }}</h2>
                        </div>
                        <i class="bi bi-check-circle display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">CA</h6>
                            <h2 class="mb-0" style="font-size: 12px;">{{ number_format(App\Models\Commande::sum('total_amount'), 0, '.', ' ') }} XOF</h2>
                        </div>
                        <i class="bi bi-currency-exchange display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title"><i class="bi bi-receipt me-2"></i>Historique des Commandes</h5>
                <div>
                    <a href="{{ route('admin.commandes.export') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-download me-2"></i>Exporter CSV
                    </a>
                    <a href="{{ route('admin.commandes.stats') }}" class="btn btn-primary">
                        <i class="bi bi-bar-chart me-2"></i>Statistiques
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover mb-2">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Livraison</th>
                            <th>Statut</th>
                            <th>Paiement</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($commandes as $key => $commande)
                            <tr>
                                <td>{{ $commandes->firstItem() + $key }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $commande->code }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2">
                                            <span class="avatar-initial bg-info rounded-circle">
                                                {{ substr($commande->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $commande->user->name }}</strong><br>
                                            <small class="text-muted">{{ $commande->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ number_format($commande->total_amount, 0, '.', ' ') }} XOF</strong>
                                    @if($commande->shipping_fee)
                                        <br><small class="text-muted">+ {{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF livraison</small>
                                    @endif
                                </td>
                                <td>
                                    @if($commande->delivery_method == 'tinda_awa')
                                        <span class="badge bg-warning">Tinda Awa</span>
                                    @elseif($commande->delivery_method == 'cargo')
                                        <span class="badge bg-secondary">Cargo</span>
                                    @else
                                        <span class="badge bg-light text-dark">Non spécifié</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge
                                        @if($commande->status == 'pending') bg-warning
                                        @elseif($commande->status == 'processing') bg-info
                                        @elseif($commande->status == 'ready_pickup') bg-primary
                                        @elseif($commande->status == 'picked_up') bg-secondary
                                        @elseif($commande->status == 'in_transit') bg-primary
                                        @elseif($commande->status == 'arrived') bg-success
                                        @elseif($commande->status == 'delivered') bg-success
                                        @elseif($commande->status == 'cancelled') bg-danger
                                        @else bg-light text-dark @endif">
                                        {{ $commande->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge
                                        @if($commande->payment_status == 'paid') bg-success
                                        @else bg-danger @endif">
                                        {{ $commande->payment_status == 'paid' ? 'Payé' : 'En attente' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $commande->created_at->format('d/m/Y') }}<br>
                                    <small class="text-muted">{{ $commande->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.commandes.show', $commande) }}"
                                           class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Voir détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-primary update-status-btn"
                                                data-id="{{ $commande->id }}"
                                                data-status="{{ $commande->status }}"
                                                data-bs-toggle="tooltip"
                                                title="Changer statut">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-cart-x display-4"></i>
                                        <p class="mt-2">Aucune commande trouvée</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $commandes->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal pour changer le statut -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer le statut de la commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="commande_id" name="commande_id">

                    <div class="mb-3">
                        <label class="form-label">Nouveau statut</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Sélectionnez un statut</option>
                            <option value="pending">En attente</option>
                            <option value="processing">En cours de traitement</option>
                            <option value="ready_pickup">Prêt pour retrait</option>
                            <option value="picked_up">Récupéré par le cargo</option>
                            <option value="in_transit">En cours de livraison</option>
                            <option value="arrived">Arrivé à destination</option>
                            <option value="delivered">Livré</option>
                            <option value="cancelled">Annulé</option>
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Un email sera envoyé au client pour l'informer du changement de statut.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Charger jQuery 3.6.0 si nécessaire
    window.jQuery || document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"><\/script>');
</script>
<script>
    $(document).ready(function() {
        // Initialiser les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Gérer le bouton de changement de statut
        $('.update-status-btn').click(function() {
            const commandeId = $(this).data('id');
            const currentStatus = $(this).data('status');

            $('#commande_id').val(commandeId);
            $('#status').val(currentStatus);

            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        });

        // Soumission du formulaire de statut
        $('#statusForm').submit(function(e) {
            e.preventDefault();

            const commandeId = $('#commande_id').val();
            const newStatus = $('#status').val();

            $.ajax({
                url: '/admin/commandes/' + commandeId + '/status',
                method: 'PUT',
                accept: 'application/json',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        // Recharger la page pour voir les changements
                        location.reload();
                    } else {
                        alert('Erreur: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Une erreur est survenue. Veuillez réessayer.');
                }
            });
        });
    });
</script>
<style>
    .stat-card {
        border-radius: 10px;
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .avatar {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-initial {
        font-weight: bold;
        color: white;
    }
</style>
@endsection
