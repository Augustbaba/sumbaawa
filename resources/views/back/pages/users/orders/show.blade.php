@extends('back.layouts.master')

@section('title', 'Détails de la Commande #' . $commande->code)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="ri-home-line"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Commande #{{ $commande->code }}</li>
                </ol>
            </nav>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($commande->shipping_status == 'fee_pending' && $commande->status == 'ready_pickup')
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="ri-money-dollar-circle-line me-2"></i>
                        <strong>Frais de livraison en attente</strong><br>
                        Des frais de livraison de <strong>{{ FrontHelper::format_currency($commande->shipping_fee) }} </strong>
                        doivent être payés pour finaliser votre commande.
                    </div>
                    <a href="{{ route('shipping.payment.show', $commande->code) }}"
                    class="btn btn-success btn-sm">
                        <i class="ri-money-dollar-circle-line me-1"></i> Payer maintenant
                    </a>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="ri-receipt-line me-2"></i>Commande #{{ $commande->code }}
                        </h4>
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
                <div class="card-body">
                    <!-- Récapitulatif rapide -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="ri-calendar-line display-6 text-primary mb-2"></i>
                                    <h6 class="text-muted">Date</h6>
                                    <p class="mb-0">{{ $commande->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="ri-money-dollar-circle-line display-6 text-success mb-2"></i>
                                    <h6 class="text-muted">Total</h6>
                                    <p class="mb-0">{{ FrontHelper::format_currency($commande->total_amount + ($commande->shipping_fee ?? 0)) }} </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="ri-truck-line display-6 text-info mb-2"></i>
                                    <h6 class="text-muted">Livraison</h6>
                                    <p class="mb-0">
                                        @if($commande->delivery_method == 'tinda_awa')
                                            Tinda Awa
                                        @elseif($commande->delivery_method == 'cargo')
                                            Cargo
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <i class="ri-bank-card-line display-6 text-warning mb-2"></i>
                                    <h6 class="text-muted">Paiement</h6>
                                    <p class="mb-0">
                                        <span class="badge
                                            @if($commande->payment_status == 'paid') bg-success
                                            @else bg-danger @endif">
                                            {{ $commande->payment_status == 'paid' ? 'Payé' : 'En attente' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations de livraison -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="ri-map-pin-line me-2"></i>Informations de livraison
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($commande->delivery_method == 'tinda_awa')
                                <div class="alert alert-info">
                                    <i class="ri-truck-line me-2"></i>
                                    <strong>Livraison Tinda Awa ({{ $commande->type ? $commande->type->label : 'N/A' }})</strong>
                                </div>

                                @if(isset($commande->delivery_info))
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Destinataire :</strong><br>
                                        {{ $commande->delivery_info['recipientName'] ?? 'N/A' }}</p>
                                        <p><strong>Téléphone :</strong><br>
                                        {{ $commande->delivery_info['recipientPhone'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Adresse :</strong><br>
                                        {{ $commande->delivery_info['address'] ?? 'N/A' }},
                                        {{ $commande->delivery_info['city'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                @endif

                            @elseif($commande->delivery_method == 'cargo')
                                <div class="alert alert-secondary">
                                    <i class="ri-box-seam-line me-2"></i>
                                    <strong>Livraison Cargo ({{ $commande->type ? $commande->type->label : 'N/A' }})</strong>
                                </div>

                                @if(isset($commande->delivery_info))
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Contact :</strong><br>
                                        {{ $commande->delivery_info['contactName'] ?? 'N/A' }}</p>
                                        <p><strong>Téléphone :</strong><br>
                                        {{ $commande->delivery_info['contactPhone'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Adresse cargo :</strong><br>
                                        {{ $commande->delivery_info['address'] ?? 'N/A' }},
                                        {{ $commande->delivery_info['city'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                @endif
                            @else
                                <div class="alert alert-warning">
                                    <i class="ri-exclamation-triangle-line me-2"></i>
                                    Aucune information de livraison disponible
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Produits commandés -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="ri-shopping-cart-line me-2"></i>Produits commandés
                            </h5>
                        </div>
                        <div class="card-body">
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
                                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $item->produit ? $item->produit->name : 'Produit supprimé' }}</strong><br>
                                                        <small class="text-muted">{{ $item->description_produit }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ FrontHelper::format_currency($item->unit_price) }} </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ FrontHelper::format_currency($item->total_price) }} </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Sous-total :</strong></td>
                                            <td><strong>{{ FrontHelper::format_currency($commande->total_amount) }} </strong></td>
                                        </tr>
                                        @if($commande->shipping_fee)
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Frais de livraison :</strong></td>
                                            <td><strong>{{ FrontHelper::format_currency($commande->shipping_fee) }} </strong></td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td colspan="3" class="text-end"><strong>Total général :</strong></td>
                                            <td><strong>{{ FrontHelper::format_currency($commande->total_amount + $commande->shipping_fee) }} </strong></td>
                                        </tr>
                                        @endif
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline du statut -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="ri-time-line me-2"></i>Suivi de la commande
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @php
                                    $statuses = [
                                        'pending' => ['icon' => 'ri-time-line', 'label' => 'Commande enregistrée'],
                                        'processing' => ['icon' => 'ri-settings-3-line', 'label' => 'En préparation'],
                                        'ready_pickup' => ['icon' => 'ri-box-seam-line', 'label' => 'Prêt pour retrait'],
                                    ];

                                    // Ajouter les statuts spécifiques selon la méthode
                                    if($commande->delivery_method == 'cargo') {
                                        $statuses['picked_up'] = ['icon' => 'bi bi-check', 'label' => 'Récupéré par le cargo'];
                                    } else {
                                        $statuses['in_transit'] = ['icon' => 'bi-truck', 'label' => 'En cours de livraison'];
                                        $statuses['arrived'] = ['icon' => 'bi bi-geo-alt', 'label' => 'Arrivé à destination'];
                                        $statuses['delivered'] = ['icon' => 'bi bi-house-check', 'label' => 'Livré'];
                                    }

                                    $currentIndex = array_search($commande->status, array_keys($statuses));
                                @endphp

                                @foreach($statuses as $status => $info)
                                    <div class="timeline-item {{ $loop->index <= $currentIndex ? 'active' : '' }}">
                                        <div class="timeline-marker">
                                            <i class="{{ $info['icon'] }}"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>{{ $info['label'] }}</h6>
                                            @if($loop->index == $currentIndex)
                                                <small class="text-primary">Statut actuel</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Si livré, ajouter le bouton "récupéré" -->
                                @if($commande->status == 'delivered' || $commande->status == 'picked_up')
                                    <div class="timeline-item {{ $commande->is_received ? 'active' : '' }}">
                                        <div class="timeline-marker">
                                            <i class="bi bi-check-double"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6>Commande récupérée</h6>
                                                    @if($commande->is_received)
                                                        <small class="text-success">
                                                            Récupéré le {{ $commande->received_at }}
                                                        </small>
                                                    @endif
                                                </div>

                                                @if(!$commande->is_received)
                                                <form action="{{ route('user.orders.received', $commande->code) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Confirmez-vous avoir récupéré cette commande ?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="ri-check-line me-1"></i> Marquer comme récupéré
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Informations de paiement -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="ri-bank-card-line me-2"></i>Informations de paiement
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Méthode de paiement :</strong><br>
                                    {{ ucfirst($commande->payment_method) }}</p>

                                    <p><strong>Statut du paiement :</strong><br>
                                    <span class="badge
                                        @if($commande->payment_status == 'paid') bg-success
                                        @else bg-warning @endif">
                                        {{ $commande->payment_status == 'paid' ? 'Payé' : 'En attente' }}
                                    </span></p>
                                </div>

                                @if($commande->payment_id)
                                <div class="col-md-6">
                                    <p><strong>ID de transaction :</strong><br>
                                    <small>{{ $commande->payment_id }}</small></p>

                                    <p><strong>Date du paiement :</strong><br>
                                    {{ $commande->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                @endif
                            </div>

                            @if($commande->shipping_fee)
                            <div class="mt-3 pt-3 border-top">
                                <h6>Frais de livraison</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Montant :</strong><br>
                                        {{ FrontHelper::format_currency($commande->shipping_fee) }} </p>

                                        <!-- AJOUTER CE BOUTON SI LES FRAIS SONT EN ATTENTE -->
                                        @if($commande->shipping_status == 'fee_pending' && $commande->status == 'ready_pickup')
                                        <div class="mt-3">
                                            <a href="{{ route('shipping.payment.show', $commande->code) }}"
                                            class="btn btn-success btn-sm">
                                                <i class="ri-money-dollar-circle-line me-1"></i> Payer les frais de livraison
                                            </a>
                                            <small class="d-block text-muted mt-1">Valable 7 jours</small>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Statut :</strong><br>
                                        <span class="badge
                                            @if($commande->shipping_status == 'fee_paid') bg-success
                                            @elseif($commande->shipping_status == 'fee_pending') bg-warning
                                            @else bg-secondary @endif">
                                            @if($commande->shipping_status == 'fee_paid') Payé
                                            @elseif($commande->shipping_status == 'fee_pending') En attente
                                            @else N/A
                                            @endif
                                        </span></p>

                                        @if($commande->shipping_payment_id)
                                        <p class="mt-2"><small>ID: {{ $commande->shipping_payment_id }}</small></p>
                                        @endif

                                        @if($commande->shipping_payment_date)
                                        <p><small>Payé le: {{ \Carbon\Carbon::parse($commande->shipping_payment_date)->format('d/m/Y H:i') }}</small></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-2"></i> Retour à mes commandes
                        </a>

                        <div class="btn-group">


                            @if($commande->status == 'delivered' && !$commande->is_received)
                            <form action="{{ route('user.orders.received', $commande->code) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Confirmez-vous avoir récupéré cette commande ?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">
                                    <i class="ri-check-line me-2"></i> Marquer comme récupéré
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 40px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -40px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        border: 2px solid white;
    }

    .timeline-item.active .timeline-marker {
        background: #0d6efd;
        color: white;
    }

    .timeline-content {
        padding-left: 15px;
    }

    .timeline-content h6 {
        margin-bottom: 5px;
    }

    .card-header.bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endsection
