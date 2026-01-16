@extends('back.layouts.master')

@section('title', 'Gestion des Devises')

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
                <li class="breadcrumb-item active" aria-current="page">Devises</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title">
                    <i class="ri-money-dollar-circle-line me-2"></i>Gestion des Devises
                </h5>
                <div>
                    {{-- <a href="{{ route('admin.currencies.updateRatesForm') }}" class="btn btn-outline-warning me-2">
                        <i class="ri-refresh-line me-2"></i>Mettre à jour les taux
                    </a> --}}
                    <a href="{{ route('admin.currencies.create') }}" class="btn btn-primary">
                        <i class="ri-add-circle-line me-2"></i>Nouvelle devise
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card stat-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total</h6>
                                    <h2 class="mb-0">{{ $currencies->count() }}</h2>
                                </div>
                                <i class="ri-money-dollar-circle-line display-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Actives</h6>
                                    <h2 class="mb-0">{{ $currencies->where('is_active', true)->count() }}</h2>
                                </div>
                                <i class="ri-checkbox-circle-line display-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Par défaut</h6>
                                    <h2 class="mb-0">{{ $currencies->where('is_default', true)->count() }}</h2>
                                </div>
                                <i class="ri-star-line display-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Dernière mise à jour</h6>
                                    <h2 class="mb-0">{{ $currencies->max('updated_at')->format('d/m') }}</h2>
                                </div>
                                <i class="ri-calendar-line display-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table des devises -->
            <div class="table-responsive">
                <table class="table table-hover mb-2" id="currenciesTable">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Symbole</th>
                            <th>Taux</th>
                            {{-- <th>Position</th> --}}
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable">
                        @forelse($currencies as $currency)
                        <tr data-id="{{ $currency->id }}" class="{{ $currency->is_default ? 'table-primary' : '' }}">
                            <td>
                                <i class="ri-draggable handle" style="cursor: move;"></i>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <strong>{{ $currency->code }}</strong>
                                @if($currency->is_default)
                                    <span class="badge bg-warning ms-2">Défaut</span>
                                @endif
                            </td>
                            <td>{{ $currency->name }}</td>
                            <td>{{ $currency->symbol }}</td>
                            <td>
                                @if($currency->is_default)
                                    <span class="text-success">1 (Base)</span>
                                @else
                                    <span class="text-primary">
                                        1 {{ $currency->code }} = {{ number_format($currency->exchange_rate, 4) }} XOF
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        1 XOF = {{ number_format(1 / $currency->exchange_rate, 6) }} {{ $currency->code }}
                                    </small>
                                @endif
                            </td>
                            {{-- <td>{{ $currency->position }}</td> --}}
                            <td>
                                <div class="form-check form-switch">
                                    <input type="checkbox"
                                           class="form-check-input toggle-active"
                                           data-id="{{ $currency->id }}"
                                           id="active_{{ $currency->id }}"
                                           {{ $currency->is_active ? 'checked' : '' }}
                                           {{ $currency->is_default ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="active_{{ $currency->id }}">
                                        <span class="badge
                                            @if($currency->is_active) bg-success
                                            @else bg-secondary @endif">
                                            {{ $currency->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    {{-- <a href="{{ route('admin.currencies.show', $currency->id) }}"
                                       class="btn btn-sm btn-info" title="Voir">
                                        <i class="ri-eye-line"></i>
                                    </a> --}}
                                    <a href="{{ route('admin.currencies.edit', $currency->id) }}"
                                       class="btn btn-sm btn-primary" title="Modifier">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    {{-- @if(!$currency->is_default)
                                    <form action="{{ route('admin.currencies.setDefault', $currency->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Définir cette devise comme par défaut ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning" title="Définir par défaut">
                                            <i class="ri-star-line"></i>
                                        </button>
                                    </form>
                                    @endif --}}
                                    {{-- @if(!$currency->is_default)
                                    <form action="{{ route('admin.currencies.destroy', $currency->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cette devise ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                    @endif --}}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ri-money-dollar-circle-line display-4"></i>
                                    <p class="mt-2">Aucune devise trouvée</p>
                                    <a href="{{ route('admin.currencies.create') }}" class="btn btn-primary mt-2">
                                        <i class="ri-add-circle-line me-2"></i>Créer la première devise
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($currencies->hasPages())
                <div class="mt-3">
                    {{ $currencies->links('vendor.pagination.bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
<script>
    $(document).ready(function() {
        // Drag & drop pour les positions
        $("#sortable").sortable({
            handle: ".handle",
            update: function(event, ui) {
                const positions = [];
                $("#sortable tr").each(function(index) {
                    const id = $(this).data('id');
                    if (id) {
                        positions.push({
                            id: id,
                            position: index + 1
                        });
                    }
                });

                $.ajax({
                    url: "{{ route('admin.currencies.updatePositions') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        positions: positions
                    },
                    success: function(response) {
                        if (response.success) {
                            // Recharger la page pour voir les nouvelles positions
                            location.reload();
                        }
                    }
                });
            }
        });

        // Toggle actif/inactif
        $('.toggle-active').change(function() {
            const currencyId = $(this).data('id');
            const isActive = $(this).is(':checked');

            $.ajax({
                url: "{{ route('admin.currencies.toggleActive', '') }}/" + currencyId,
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        const badge = $('#active_' + currencyId).next().find('.badge');
                        if (response.is_active) {
                            badge.removeClass('bg-secondary').addClass('bg-success').text('Active');
                        } else {
                            badge.removeClass('bg-success').addClass('bg-secondary').text('Inactive');
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: 'Statut mis à jour',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: response.message
                        });
                        // Re-check the checkbox if it failed
                        $('#active_' + currencyId).prop('checked', !isActive);
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue'
                    });
                    // Re-check the checkbox if it failed
                    $('#active_' + currencyId).prop('checked', !isActive);
                }
            });
        });

        // Confirmation avant suppression
        $('form[onsubmit]').submit(function(e) {
            if (!confirm($(this).attr('onsubmit').match(/return confirm\('([^']+)'\)/)[1])) {
                e.preventDefault();
            }
        });
    });
</script>

<style>
    .stat-card {
        border-radius: 10px;
        transition: transform 0.3s;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .handle {
        color: #6c757d;
        cursor: move;
    }

    #sortable tr {
        cursor: move;
    }

    .table-primary {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }

    .form-check-input:disabled {
        background-color: #e9ecef;
        border-color: #ced4da;
    }
</style>
@endsection
