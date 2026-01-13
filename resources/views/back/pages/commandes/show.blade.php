@extends('back.layouts.master')

@section('title', 'Détails de la Commande')

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
                    <a href="{{ route('admin.commandes.index') }}">
                        <i class="bi bi-receipt me-2"></i> Commandes
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $commande->code }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">
                            <i class="bi bi-receipt me-2"></i>Commande #{{ $commande->code }}
                        </h5>
                        <div>
                            <span class="badge
                                @if($commande->status == 'pending') bg-warning
                                @elseif($commande->status == 'processing') bg-info
                                @elseif($commande->status == 'ready_pickup') bg-primary
                                @elseif($commande->status == 'picked_up') bg-secondary
                                @elseif($commande->status == 'in_transit') bg-primary
                                @elseif($commande->status == 'arrived') bg-success
                                @elseif($commande->status == 'delivered') bg-success
                                @elseif($commande->status == 'cancelled') bg-danger
                                @else bg-light text-dark @endif fs-6">
                                {{ $commande->status_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Informations client -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Informations Client</h6>
                                    <p class="mb-1">
                                        <strong>Nom :</strong> {{ $commande->user->name }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Email :</strong> {{ $commande->user->email }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Téléphone :</strong> {{ $commande->user->phone ?? 'Non renseigné' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Informations Paiement</h6>
                                    <p class="mb-1">
                                        <strong>Méthode :</strong>
                                        <span class="badge bg-info">{{ $commande->payment_method }}</span>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Statut :</strong>
                                        <span class="badge
                                            @if($commande->payment_status == 'paid') bg-success
                                            @else bg-danger @endif">
                                            {{ $commande->payment_status == 'paid' ? 'Payé' : 'En attente' }}
                                        </span>
                                    </p>
                                    @if($commande->payment_id)
                                    <p class="mb-1">
                                        <strong>ID Transaction :</strong>
                                        <small>{{ $commande->payment_id }}</small>
                                    </p>
                                    @endif

                                    @if($commande->shipping_fee)
                                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                                        <h6 class="text-muted mb-2">Frais de Livraison</h6>

                                        <p class="mb-1">
                                            <strong>Montant :</strong>
                                            {{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF
                                        </p>

                                        @if($commande->shipping_status)
                                        <p class="mb-1">
                                            <strong>Statut paiement :</strong>
                                            <span class="badge
                                                @if($commande->shipping_status == 'fee_paid') bg-success
                                                @elseif($commande->shipping_status == 'fee_pending') bg-warning
                                                @else bg-secondary @endif">
                                                @if($commande->shipping_status == 'fee_paid') Payé
                                                @elseif($commande->shipping_status == 'fee_pending') En attente
                                                @else {{ $commande->shipping_status }}
                                                @endif
                                            </span>
                                        </p>
                                        @endif

                                        @if($commande->shipping_payment_id)
                                        <p class="mb-1">
                                            <strong>ID Paiement frais :</strong>
                                            <small>{{ $commande->shipping_payment_id }}</small>
                                        </p>
                                        @endif

                                        @if($commande->shipping_payment_date)
                                        <p class="mb-0">
                                            <strong>Date paiement frais :</strong>
                                            <small>{{ \Carbon\Carbon::parse($commande->shipping_payment_date)->format('d/m/Y H:i') }}</small>
                                        </p>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations livraison -->
                    <div class="card border mb-4">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-muted">Informations de Livraison</h6>

                            @if($commande->delivery_method == 'tinda_awa')
                                <div class="alert alert-info">
                                    <i class="bi bi-truck me-2"></i>
                                    <strong>Méthode :</strong> Livraison Tinda Awa ({{ $commande->type ? $commande->type->label : 'N/A' }})
                                </div>

                                @if(isset($commande->delivery_info))
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <strong>Destinataire :</strong>
                                            {{ $commande->delivery_info['recipientName'] ?? 'N/A' }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Téléphone :</strong>
                                            {{ $commande->delivery_info['recipientPhone'] ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <strong>Adresse :</strong>
                                            {{ $commande->delivery_info['address'] ?? 'N/A' }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Ville :</strong>
                                            {{ $commande->delivery_info['city'] ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                @endif

                            @elseif($commande->delivery_method == 'cargo')
                                <div class="alert alert-secondary">
                                    <i class="bi bi-box-seam me-2"></i>
                                    <strong>Méthode :</strong> Livraison Cargo
                                </div>

                                @if(isset($commande->delivery_info))
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <strong>Contact :</strong>
                                            {{ $commande->delivery_info['contactName'] ?? 'N/A' }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Téléphone :</strong>
                                            {{ $commande->delivery_info['contactPhone'] ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <strong>Adresse cargo :</strong>
                                            {{ $commande->delivery_info['address'] ?? 'N/A' }}
                                        </p>
                                        <p class="mb-1">
                                            <strong>Ville :</strong>
                                            {{ $commande->delivery_info['city'] ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Aucune information de livraison disponible
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Produits de la commande -->
                    <div class="card border">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-muted">Produits commandés</h6>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Prix unitaire</th>
                                            <th>Quantité</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($commande->commandesProduits as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->produit && $item->produit->image_main)
                                                    <img src="{{ asset($item->produit->image_main) }}"
                                                         alt="{{ $item->produit->name }}"
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $item->produit ? $item->produit->name : 'Produit supprimé' }}</strong><br>
                                                        <small class="text-muted">{{ $item->description_produit }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($item->unit_price, 0, '.', ' ') }} XOF</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->total_price, 0, '.', ' ') }} XOF</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Sous-total :</strong></td>
                                            <td><strong>{{ number_format($commande->total_amount, 0, '.', ' ') }} XOF</strong></td>
                                        </tr>
                                        @if($commande->shipping_fee)
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Frais de livraison :</strong></td>
                                            <td><strong>{{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF</strong></td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td colspan="3" class="text-end"><strong>Total général :</strong></td>
                                            <td><strong>{{ number_format($commande->total_amount + $commande->shipping_fee, 0, '.', ' ') }} XOF</strong></td>
                                        </tr>
                                        @endif
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite : Actions et frais de livraison -->
        <div class="col-md-4">
            <!-- Statut de livraison dynamique -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-muted">Statut de Livraison</h6>

                    @if($commande->status == 'in_transit')
                        <div class="text-center mb-3">
                            @if($commande->type && $commande->type->label == 'Livraison par avion')
                                <i class="bi bi-airplane display-4 text-primary"></i>
                                <p class="mt-2">En transit par avion 🛫</p>
                            @elseif($commande->type && $commande->type->label == 'Livraison par bateau')
                                <i class="bi bi-water display-4 text-primary"></i>
                                <p class="mt-2">En transit par bateau 🛳️</p>
                            @else
                                <i class="bi bi-truck display-4 text-primary"></i>
                                <p class="mt-2">En cours de livraison</p>
                            @endif
                        </div>
                    @endif

                    <!-- Timeline des statuts -->
                    <div class="timeline">
                        @php
                            $statuses = [
                                'pending' => ['icon' => 'bi-clock', 'label' => 'En attente'],
                                'processing' => ['icon' => 'bi-gear', 'label' => 'En traitement'],
                                'ready_pickup' => ['icon' => 'bi-box-seam', 'label' => 'Prêt pour retrait'],
                                'picked_up' => ['icon' => 'bi-check-circle', 'label' => 'Récupéré', 'cargo_only' => true],
                                'in_transit' => ['icon' => 'bi-truck', 'label' => 'En transit', 'tinda_only' => true],
                                'arrived' => ['icon' => 'bi-geo-alt', 'label' => 'Arrivé', 'tinda_only' => true],
                                'delivered' => ['icon' => 'bi-house-check', 'label' => 'Livré', 'tinda_only' => true]
                            ];

                            $currentIndex = array_search($commande->status, array_keys($statuses));
                        @endphp

                        @foreach($statuses as $status => $info)
                            @if(($commande->delivery_method == 'cargo' && isset($info['tinda_only'])) ||
                                ($commande->delivery_method == 'tinda_awa' && isset($info['cargo_only'])))
                                @continue
                            @endif

                            <div class="timeline-item {{ $loop->index <= $currentIndex ? 'active' : '' }}">
                                <div class="timeline-marker">
                                    <i class="bi {{ $info['icon'] }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6>{{ $info['label'] }}</h6>
                                    @if($loop->index == $currentIndex)
                                        <small class="text-primary">Statut actuel</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Actions admin -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-muted">Actions Administrateur</h6>

                    <!-- Bouton changer statut -->
                    <button type="button"
                            class="btn btn-primary w-100 mb-3"
                            data-bs-toggle="modal"
                            data-bs-target="#changeStatusModal">
                        <i class="bi bi-arrow-repeat me-2"></i>Changer le statut
                    </button>

                    <!-- AJOUTER CETTE SECTION POUR LE STATUT DU PAIEMENT -->
                    @if($commande->shipping_fee && $commande->shipping_status)
                    <div class="alert
                        @if($commande->shipping_status == 'fee_paid') alert-success
                        @elseif($commande->shipping_status == 'fee_pending') alert-warning
                        @else alert-secondary @endif mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi
                                    @if($commande->shipping_status == 'fee_paid') bi-check-circle
                                    @elseif($commande->shipping_status == 'fee_pending') bi-clock
                                    @else bi-info-circle @endif me-2"></i>
                                <strong>Frais de livraison :</strong>
                                @if($commande->shipping_status == 'fee_paid')
                                    <span class="text-success">Payé</span>
                                @elseif($commande->shipping_status == 'fee_pending')
                                    <span class="text-warning">En attente de paiement</span>
                                @else
                                    <span>{{ $commande->shipping_status }}</span>
                                @endif
                            </div>
                            <span class="badge bg-dark">
                                {{ number_format($commande->shipping_fee, 0, '.', ' ') }} XOF
                            </span>
                        </div>

                        @if($commande->shipping_payment_id)
                        <small class="d-block mt-1">
                            ID: {{ $commande->shipping_payment_id }}
                        </small>
                        @endif
                    </div>
                    @endif

                    <!-- Formulaire frais de livraison (si status = ready_pickup ET pas encore de frais) -->
                    @if($commande->status == 'ready_pickup' && !$commande->shipping_fee)
                    <div class="border rounded p-3 mb-3">
                        <h6 class="text-muted mb-3">Définir les frais de livraison</h6>

                        <form id="shippingFeeForm">
                            @csrf
                            <input type="hidden" name="delivery_method" value="{{ $commande->delivery_method }}">

                            <div class="mb-3">
                                <label class="form-label">Frais de livraison (XOF)</label>
                                <input type="number"
                                    class="form-control"
                                    name="shipping_fee"
                                    min="0"
                                    step="100"
                                    required>
                            </div>

                            @if($commande->delivery_method == 'tinda_awa')
                            <div class="mb-3">
                                <label class="form-label">Date de livraison estimée</label>
                                <input type="date"
                                    class="form-control"
                                    name="estimated_delivery"
                                    min="{{ date('Y-m-d', strtotime('+1 days')) }}"
                                    required>
                            </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Observations (optionnel)</label>
                                <textarea class="form-control"
                                        name="observations"
                                        rows="3"
                                        placeholder="Informations supplémentaires pour le client..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-send-check me-2"></i>Envoyer les frais au client
                            </button>
                        </form>
                    </div>
                    @elseif($commande->shipping_fee && $commande->shipping_status == 'fee_pending')
                    <div class="alert alert-warning">
                        <i class="bi bi-clock-history me-2"></i>
                        <strong>En attente de paiement</strong><br>
                        Les frais de livraison ont été envoyés au client mais ne sont pas encore payés.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-3 text-muted">Informations</h6>

                    <p class="mb-2">
                        <strong>Date de commande :</strong><br>
                        {{ $commande->created_at->format('d/m/Y H:i') }}
                    </p>

                    @if($commande->shipping_payment_date)
                    <p class="mb-2">
                        <strong>Frais payés le :</strong><br>
                        {{ \Carbon\Carbon::parse($commande->shipping_payment_date)->format('d/m/Y H:i') }}
                    </p>
                    @endif

                    @if($commande->estimated_delivery)
                    <p class="mb-2">
                        <strong>Livraison estimée :</strong><br>
                        {{ \Carbon\Carbon::parse($commande->estimated_delivery)->format('d/m/Y') }}
                    </p>
                    @endif

                    @if($commande->observations)
                    <p class="mb-2">
                        <strong>Observations :</strong><br>
                        {{ $commande->observations }}
                    </p>
                    @endif

                    @if($commande->updated_at != $commande->created_at)
                    <p class="mb-0">
                        <strong>Dernière mise à jour :</strong><br>
                        {{ $commande->updated_at->format('d/m/Y H:i') }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour changer le statut -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer le statut de la commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.commandes.updateStatus', $commande->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nouveau statut</label>
                        <select class="form-select" name="status" required>
                            <option value="">Sélectionnez un statut</option>
                            <option value="pending" {{ $commande->status == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="processing" {{ $commande->status == 'processing' ? 'selected' : '' }}>En cours de traitement</option>
                            <option value="ready_pickup" {{ $commande->status == 'ready_pickup' ? 'selected' : '' }}>Prêt pour retrait</option>

                            <!-- Options spécifiques cargo -->
                            @if($commande->delivery_method == 'cargo')
                                <option value="picked_up" {{ $commande->status == 'picked_up' ? 'selected' : '' }}>Récupéré par le cargo</option>
                            @endif

                            <!-- Options spécifiques Tinda Awa -->
                            @if($commande->delivery_method == 'tinda_awa')
                                <option value="in_transit" {{ $commande->status == 'in_transit' ? 'selected' : '' }}>En cours de livraison</option>
                                <option value="arrived" {{ $commande->status == 'arrived' ? 'selected' : '' }}>Arrivé à destination</option>
                                <option value="delivered" {{ $commande->status == 'delivered' ? 'selected' : '' }}>Livré</option>
                            @endif

                            <option value="cancelled" {{ $commande->status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Un email sera automatiquement envoyé au client pour l'informer du changement.
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
        // Formulaire des frais de livraison
        $('#shippingFeeForm').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                url: '/admin/commandes/{{ $commande->id }}/shipping-fees',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès!',
                            text: response.message,
                            confirmButtonColor: '#198754'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: response.message,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue. Veuillez réessayer.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        });
    });
</script>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-marker {
        position: absolute;
        left: -30px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }

    .timeline-item.active .timeline-marker {
        background: #0d6efd;
        color: white;
    }

    .timeline-content {
        padding-left: 10px;
    }
</style>
@endsection
