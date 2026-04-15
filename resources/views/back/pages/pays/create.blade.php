@extends('back.layouts.master')

@section('title', 'Ajouter un Pays')

@section('content')
<div class="content">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('pays.index') }}">
                        <i class="bi bi-flag me-2"></i> Pays
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Ajouter</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-plus-circle me-2"></i>Ajouter un Pays</h5>

            <form action="{{ route('pays.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du pays *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('pays.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Génération automatique du slug à partir du nom
    document.getElementById('name').addEventListener('input', function() {
        let slugInput = document.getElementById('slug');
        if (slugInput.value === '' || slugInput.value === this.value.toLowerCase().replace(/\s+/g, '-')) {
            slugInput.value = this.value.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-');
        }
    });
</script>
@endpush
@endsection
