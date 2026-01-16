@extends('back.layouts.master')

@section('title', 'Nouvelle Devise')

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
                <li class="breadcrumb-item active" aria-current="page">Nouvelle devise</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="ri-add-circle-line me-2"></i>Créer une nouvelle devise
                    </h5>

                    <form action="{{ route('admin.currencies.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Code ISO <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('code') is-invalid @enderror"
                                           id="code"
                                           name="code"
                                           value="{{ old('code') }}"
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
                                           value="{{ old('symbol') }}"
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
                                           value="{{ old('name') }}"
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
                                        <span class="input-group-text">1 {{ old('code') ?: 'DEV' }} =</span>
                                        <input type="number"
                                               step="0.00000001"
                                               min="0.00000001"
                                               class="form-control @error('exchange_rate') is-invalid @enderror"
                                               id="rate"
                                               name="exchange_rate"
                                               value="{{ old('exchange_rate', 1) }}"
                                               required>
                                        <span class="input-group-text">XOF</span>
                                    </div>
                                    <div class="form-text">Taux par rapport au Franc CFA (XOF)</div>
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
                                               {{ old('is_active', true) ? 'checked' : '' }}>
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
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-2"></i>Créer la devise
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="ri-information-line me-2"></i>Informations
                    </h6>

                    <div class="alert alert-info">
                        <h6>À propos des codes ISO</h6>
                        <p class="mb-2">Utilisez les codes ISO 4217 standards :</p>
                        <ul class="mb-0">
                            <li><strong>XOF</strong> : Franc CFA</li>
                            <li><strong>EUR</strong> : Euro</li>
                            <li><strong>USD</strong> : Dollar US</li>
                            <li><strong>GBP</strong> : Livre Sterling</li>
                            <li><strong>CAD</strong> : Dollar Canadien</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6>Devise par défaut</h6>
                        <p class="mb-0">
                            La devise par défaut est XOF, la référence pour toutes les conversions.
                            Elle doit avoir un taux de <strong>1</strong>.
                        </p>
                    </div>

                    <div class="alert alert-light">
                        <h6>Conseils</h6>
                        <ul class="mb-0">
                            <li>Vérifiez les taux de change actuels</li>
                            <li>Testez les conversions après création</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Mettre à jour l'affichage du taux selon le code
        $('#code').on('input', function() {
            const code = $(this).val().toUpperCase();
            if (code.length === 3) {
                $('.input-group-text:first').text('1 ' + code + ' =');
            }
        });

        

        // Validation en temps réel du code ISO
        $('#code').on('blur', function() {
            const code = $(this).val();
            if (code && code.length === 3) {
                // Vérifier le format (3 lettres majuscules)
                if (!/^[A-Z]{3}$/.test(code)) {
                    $(this).addClass('is-invalid');
                    $(this).next('.form-text').html('<span class="text-danger">Le code doit contenir 3 lettres majuscules</span>');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.form-text').text('3 lettres majuscules (ISO 4217)');
                }
            }
        });
    });
</script>
@endsection
