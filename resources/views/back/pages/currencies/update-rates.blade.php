@extends('back.layouts.master')

@section('title', 'Mettre à jour les taux de change')

@section('content')
<div class="content">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-globe2 small me-2"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.currencies.index') }}">
                        <i class="ri-money-dollar-circle-line me-2"></i> Devises
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Mettre à jour les taux</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="ri-refresh-line me-2"></i>Mettre à jour les taux de change
                    </h5>

                    <!-- Méthode automatique -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="ri-download-cloud-line me-2"></i>Mise à jour automatique
                            </h6>
                        </div>
                        <div class="card-body">
                            <p>Récupérer automatiquement les derniers taux de change depuis une API en ligne.</p>

                            <div class="alert alert-warning">
                                <i class="ri-information-line me-2"></i>
                                <strong>Note :</strong> Vous devez configurer une clé API dans le fichier .env
                                <br>
                                <small>Ex: EXCHANGE_RATE_API_KEY=votre_cle_api</small>
                            </div>

                            <form action="{{ route('admin.currencies.updateRates') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-download-cloud-line me-2"></i>Mettre à jour depuis l'API
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Méthode manuelle -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="ri-edit-line me-2"></i>Mise à jour manuelle
                            </h6>
                        </div>
                        <div class="card-body">
                            <p>Modifier manuellement les taux de change pour chaque devise.</p>

                            <form action="{{ route('admin.currencies.updateRatesManual') }}" method="POST">
                                @csrf

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Devise</th>
                                                <th>Code</th>
                                                <th>Taux actuel</th>
                                                <th>Nouveau taux</th>
                                                <th>Équivalent</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($currencies as $currency)
                                            <tr>
                                                <td>
                                                    <strong>{{ $currency->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $currency->symbol }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark">{{ $currency->code }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-primary">
                                                        1 {{ $currency->code }} = {{ number_format($currency->rate, 4) }} XOF
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">1 {{ $currency->code }} =</span>
                                                        <input type="number"
                                                               step="0.0001"
                                                               min="0.0001"
                                                               class="form-control"
                                                               name="rates[{{ $currency->id }}]"
                                                               value="{{ old('rates.' . $currency->id, $currency->rate) }}">
                                                        <span class="input-group-text">XOF</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        1000 XOF = {{ number_format(1000 / $currency->rate, 2) }} {{ $currency->code }}
                                                    </small>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('admin.currencies.index') }}" class="btn btn-secondary">
                                        <i class="ri-arrow-left-line me-2"></i>Retour
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="ri-save-line me-2"></i>Enregistrer les nouveaux taux
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                        <h6>Devise de référence</h6>
                        <p class="mb-0">
                            Tous les taux sont exprimés par rapport au <strong>Franc CFA (XOF)</strong>.
                            La devise par défaut (XOF) a toujours un taux de <strong>1</strong>.
                        </p>
                    </div>

                    <div class="alert alert-warning">
                        <h6>Précision</h6>
                        <p class="mb-0">
                            Utilisez au moins 4 décimales pour les taux de change.
                            Cela garantit des conversions précises.
                        </p>
                    </div>

                    <div class="alert alert-light">
                        <h6>Sources de taux</h6>
                        <ul class="mb-0">
                            <li><a href="https://www.exchangerate-api.com" target="_blank">ExchangeRate-API</a></li>
                            <li><a href="https://exchangeratesapi.io" target="_blank">Exchange Rates API</a></li>
                            <li><a href="https://www.ecb.europa.eu" target="_blank">Banque Centrale Européenne</a></li>
                        </ul>
                    </div>

                    <div class="alert alert-success">
                        <h6>Conseils</h6>
                        <ul class="mb-0">
                            <li>Mettez à jour les taux régulièrement</li>
                            <li>Vérifiez la précision des conversions</li>
                            <li>Testez avec des montants réels</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
