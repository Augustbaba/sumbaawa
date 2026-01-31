@extends('back.layouts.master')

@section('title', 'Détails Inscription - ' . $travel->code)

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
                    <a href="{{ route('admin.nihao-travel.index') }}">
                        Nihao Travel
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $travel->code }}</li>
            </ol>
        </nav>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-badge me-2"></i>Informations Client
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Code :</th>
                                    <td>
                                        <span class="badge bg-light text-dark fs-6">{{ $travel->code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nom complet :</th>
                                    <td><strong>{{ $travel->last_name }} {{ $travel->first_name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Email :</th>
                                    <td>
                                        <a href="mailto:{{ $travel->email }}">{{ $travel->email }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Téléphone :</th>
                                    <td>
                                        <a href="tel:{{ $travel->phone }}">{{ $travel->phone }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Entreprise :</th>
                                    <td>{{ $travel->company ?? 'Non renseigné' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Édition :</th>
                                    <td>
                                        <span class="badge bg-info">{{ $travel->canton_edition }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Montant :</th>
                                    <td>
                                        <div class="text-success fw-bold">
                                            {{ number_format($travel->amount_xof, 0, ',', ' ') }} XOF
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Statut paiement :</th>
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
                                </tr>
                                <tr>
                                    <th>Date inscription :</th>
                                    <td>{{ $travel->created_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Dernière mise à jour :</th>
                                    <td>{{ $travel->updated_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Informations complémentaires -->
                    @if($travel->additional_info)
                    <div class="mt-4">
                        <h6><i class="bi bi-chat-text me-2"></i>Informations complémentaires</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                {{ $travel->additional_info }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informations PayPal -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-credit-card me-2"></i>Informations de Paiement
                    </h5>
                </div>
                <div class="card-body">
                    @if($travel->paypal_details)
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="50%">ID Transaction :</th>
                                        <td>
                                            <code>{{ $travel->paypal_transaction_id }}</code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>ID Commande :</th>
                                        <td>
                                            <code>{{ $travel->paypal_order_id }}</code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Méthode :</th>
                                        <td>
                                            <span class="badge bg-primary">PayPal</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Statut PayPal :</th>
                                        <td>
                                            <span class="badge bg-success">COMPLETED</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                @if(isset($travel->paypal_details['payer']))
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="50%">Nom PayPal :</th>
                                            <td>{{ $travel->paypal_details['payer']['name']['given_name'] ?? '' }} {{ $travel->paypal_details['payer']['name']['surname'] ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email PayPal :</th>
                                            <td>{{ $travel->paypal_details['payer']['email_address'] ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>ID PayPal :</th>
                                            <td>
                                                <small><code>{{ $travel->paypal_details['payer']['payer_id'] ?? '' }}</code></small>
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Aucune information PayPal disponible pour cette inscription.
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>
</div>



@push('scripts')
<script>
    // Initialiser les modals et tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
@endsection
