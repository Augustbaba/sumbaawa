@extends('back.layouts.master')

@section('title', 'Gestion des Recharges')

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
                <li class="breadcrumb-item active" aria-current="page">Mon Portefeuille</li>
            </ol>
        </nav>
    </div>

    {{-- Stats cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Mon Solde</h6>
                            <h2 class="mb-0" style="font-size: 1.4rem;">
                                {{ FrontHelper::format_currency(auth()->user()->solde) }}
                            </h2>
                        </div>
                        <i class="bi bi-wallet2 display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total rechargé</h6>
                            <h2 class="mb-0" style="font-size: 1.4rem;">
                                {{ FrontHelper::format_currency($transactions->sum('amount')) }}
                            </h2>
                        </div>
                        <i class="bi bi-arrow-down-circle display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Nb. recharges</h6>
                            <h2 class="mb-0">{{ $transactions->total() }}</h2>
                        </div>
                        <i class="bi bi-receipt display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title">
                    <i class="bi bi-clock-history me-2"></i>Historique des recharges
                </h5>
                <a href="{{ route('wallet.recharge') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Recharger mon solde
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover mb-2">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Référence</th>
                            <th>Transaction ID</th>
                            <th>Montant</th>
                            <th>Moyen de paiement</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $key => $tx)
                            <tr>
                                <td>{{ $transactions->firstItem() + $key }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $tx->reference }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $tx->transaction_id ?? '—' }}</small>
                                </td>
                                <td>
                                    {{ FrontHelper::format_currency($tx->amount) }}
                                </td>
                                <td>
                                    @if ($tx->payment_method === 'paypal')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-paypal me-1"></i>PayPal
                                        </span>
                                    @elseif ($tx->payment_method === 'elongopay')
                                        <span class="badge bg-info">
                                            <i class="bi bi-wallet2 me-1"></i>ElongoPay
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $tx->payment_method }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $tx->created_at->format('d/m/Y') }}<br>
                                    <small class="text-muted">{{ $tx->created_at->format('H:i') }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox display-4"></i>
                                        <p class="mt-2">Aucune recharge effectuée</p>
                                        <a href="{{ route('wallet.recharge') }}" class="btn btn-primary btn-sm mt-1">
                                            Faire ma première recharge
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $transactions->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .stat-card {
        border-radius: 10px;
        transition: transform 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection
