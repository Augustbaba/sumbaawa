@extends('back.layouts.master')

@section('title', 'Modifier la devise : ' . $currency->name)

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
                <li class="breadcrumb-item active" aria-current="page">Modifier {{ $currency->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="ri-edit-line me-2"></i>Modifier la devise
                    </h5>

                    <form action="{{ route('admin.currencies.update', $currency->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Code ISO <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('code') is-invalid @enderror"
                                           id="code"
                                           name="code"
                                           value="{{ old('code', $currency->code) }}"
                                           placeholder="Ex: EUR, USD, XOF"
                                           maxlength="3"
                                           required>
                                    <div class="form-text">3 lettres majuscules (ISO 4217)</div>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="symbol" class="form-label">Symbole <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('symbol') is-invalid @enderror"
                                           id="symbol"
                                           name="symbol"
                                           value="{{ old('symbol', $currency->symbol) }}"
                                           placeholder="Ex: €, $, FCFA"
                                           required>
                                    @error('symbol')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $currency->name) }}"
                                           placeholder="Ex: Euro, Dollar US, Franc CFA"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rate" class="form-label">Taux de change <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">1 {{ $currency->code }} =</span>
                                        <input type="number"
                                               step="0.00000001"
                                               min="0.00000001"
                                               class="form-control @error('exchange_rate') is-invalid @enderror"
                                               id="rate"
                                               name="exchange_rate"
                                               value="{{ old('exchange_rate', $currency->exchange_rate) }}"
                                               {{ $currency->is_default ? 'readonly' : '' }}
                                               required>
                                        <span class="input-group-text">XOF</span>
                                    </div>
                                    <div class="form-text">
                                        @if($currency->is_default)
                                            <i class="ri-information-line"></i> Taux fixé à 1 pour la devise par défaut
                                        @else
                                            Taux par rapport au Franc CFA (XOF)
                                        @endif
                                    </div>
                                    @error('exchange_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                        </div>

                        <div class="row">


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox"
                                               class="form-check-input @error('is_active') is-invalid @enderror"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', $currency->is_active) ? 'checked' : '' }}
                                               {{ $currency->is_default ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Devise active
                                        </label>
                                    </div>
                                    <div class="form-text">Les devises inactives ne seront pas visibles sur le site</div>
                                    @error('is_active')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-2"></i>Retour
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-2"></i>Enregistrer
                                </button>
                                {{-- @if(!$currency->is_default)
                                <button type="button"
                                        class="btn btn-danger"
                                        onclick="confirmDelete()">
                                    <i class="ri-delete-bin-line me-2"></i>Supprimer
                                </button>
                                @endif --}}
                            </div>
                        </div>
                    </form>

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
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="ri-information-line me-2"></i>Informations sur la devise
                    </h6>

                    <div class="mb-4">
                        <h6>Statistiques</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Créée le :</span>
                                <strong>{{ $currency->created_at->format('d/m/Y H:i') }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Modifiée le :</span>
                                <strong>{{ $currency->updated_at->format('d/m/Y H:i') }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Statut :</span>
                                <span class="badge
                                    @if($currency->is_active) bg-success
                                    @else bg-secondary @endif">
                                    {{ $currency->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </li>
                            @if($currency->is_default)
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Type :</span>
                                <span class="badge bg-warning">Devise par défaut</span>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <h6>Conversion exemple</h6>
                        <p class="mb-2">
                            <strong>100 {{ $currency->code }}</strong> =
                            <strong>{{ number_format(100 * $currency->exchange_rate, 2) }} XOF</strong>
                        </p>
                        <p class="mb-0">
                            <strong>1000 XOF</strong> =
                            <strong>{{ number_format(1000 / $currency->exchange_rate, 2) }} {{ $currency->code }}</strong>
                        </p>
                    </div>

                    {{-- @if(!$currency->is_default)
                    <div class="alert alert-warning">
                        <h6>Attention</h6>
                        <p class="mb-0">
                            La suppression d'une devise peut affecter les utilisateurs qui l'ont sélectionnée.
                            Ils seront automatiquement basculés vers la devise par défaut.
                        </p>
                    </div>
                    @endif --}}
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
            text: "Cette action est irréversible !",
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

    $(document).ready(function() {
        // Mettre à jour l'affichage du taux selon le code
        $('#code').on('input', function() {
            const code = $(this).val().toUpperCase();
            if (code.length === 3) {
                $('.input-group-text:first').text('1 ' + code + ' =');
            }
        });



        
    });
</script>
@endsection
