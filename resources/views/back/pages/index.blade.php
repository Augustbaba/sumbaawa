@extends('back.layouts.master')
@section('title', 'Tableau de bord')
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="ri-history-line me-2"></i>Historique de mes Commandes
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($commandes->isEmpty())
                        <div class="text-center py-5">
                            <i class="ri-shopping-cart-line display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">Vous n'avez pas encore de commandes</h5>
                            <p class="text-muted">Commencez vos achats pour voir vos commandes ici.</p>
                            <a href="{{ route('index') }}" class="btn btn-primary">
                                <i class="ri-store-line me-2"></i>Voir les produits
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Commande</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Livraison</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commandes as $commande)
                                    <tr>
                                        <td>
                                            <strong>#{{ $commande->code }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $commande->commandesProduits->count() }} article(s)</small>
                                        </td>
                                        <td>
                                            {{ $commande->created_at }}
                                        </td>
                                        <td>
                                            <strong>{{ FrontHelper::format_currency($commande->total_amount) }} </strong>
                                            @if($commande->shipping_fee)
                                            <br>
                                            <small class="text-muted">
                                                + {{ FrontHelper::format_currency($commande->shipping_fee) }}  livraison
                                            </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($commande->delivery_method == 'tinda_awa')
                                                <span class="badge bg-info">Tinda Awa</span>
                                            @elseif($commande->delivery_method == 'cargo')
                                                <span class="badge bg-secondary">Cargo</span>
                                            @else
                                                <span class="badge bg-light text-dark">N/A</span>
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

                                            @if($commande->is_received)
                                            <br>
                                            <small class="text-success">
                                                <i class="ri-checkbox-circle-fill"></i> Récupéré
                                            </small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('user.orders.show', $commande->code) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Voir détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                @if(($commande->status == 'delivered' || $commande->status == 'picked_up') && !$commande->is_received)
                                                <form action="{{ route('user.orders.received', $commande->code) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Confirmez-vous avoir récupéré cette commande ?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-success"
                                                            title="Marquer comme récupéré">
                                                        <i class="bi bi-check"></i>
                                                    </button>
                                                </form>
                                                @endif


                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $commandes->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
