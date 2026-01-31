@extends('back.layouts.master')
@section('title', 'Changer le Mot de passe')
@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    <style>
        .password-strength {
            height: 5px;
            width: 0;
            transition: width 0.3s ease, background-color 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background-color: #dc3545; }
        .strength-medium { background-color: #ffc107; }
        .strength-strong { background-color: #28a745; }

        .password-requirements {
            font-size: 0.875rem;
        }

        .requirement-met {
            color: #28a745;
        }

        .requirement-not-met {
            color: #6c757d;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Changer le Mot de passe</h1>
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Changer le mot de passe</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour au profil
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Modifier votre mot de passe</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('profile.update-password') }}" method="POST" id="password-form">
                        @csrf
                        @method('PUT')

                        <!-- Mot de passe actuel -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Mot de passe actuel
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password"
                                       name="current_password"
                                       required
                                       placeholder="Entrez votre mot de passe actuel">
                                <button class="btn btn-outline-secondary toggle-password"
                                        type="button"
                                        data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Nouveau mot de passe -->
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-key me-2"></i>Nouveau mot de passe
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required
                                       placeholder="Entrez votre nouveau mot de passe"
                                       onkeyup="checkPasswordStrength(this.value)">
                                <button class="btn btn-outline-secondary toggle-password"
                                        type="button"
                                        data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Barre de force du mot de passe -->
                            <div class="mt-2">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-grow-1">
                                        <div class="password-strength" id="password-strength-bar"></div>
                                    </div>
                                    <div class="ms-2">
                                        <small id="password-strength-text" class="text-muted"></small>
                                    </div>
                                </div>
                            </div>

                            <!-- Exigences du mot de passe -->
                            <div class="password-requirements mt-3">
                                <p class="mb-2"><strong>Exigences :</strong></p>
                                <ul class="list-unstyled mb-0">
                                    <li id="req-length">
                                        <i class="fas fa-circle me-2"></i>
                                        <span>Au moins 8 caractères</span>
                                    </li>
                                    <li id="req-uppercase">
                                        <i class="fas fa-circle me-2"></i>
                                        <span>Au moins une lettre majuscule</span>
                                    </li>
                                    <li id="req-lowercase">
                                        <i class="fas fa-circle me-2"></i>
                                        <span>Au moins une lettre minuscule</span>
                                    </li>
                                    <li id="req-number">
                                        <i class="fas fa-circle me-2"></i>
                                        <span>Au moins un chiffre</span>
                                    </li>
                                    <li id="req-special">
                                        <i class="fas fa-circle me-2"></i>
                                        <span>Au moins un caractère spécial (@$!%*?&)</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Confirmation du nouveau mot de passe -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-key me-2"></i>Confirmer le nouveau mot de passe
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       required
                                       placeholder="Confirmez votre nouveau mot de passe"
                                       onkeyup="checkPasswordMatch()">
                                <button class="btn btn-outline-secondary toggle-password"
                                        type="button"
                                        data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="password-match" class="form-text"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="fas fa-save me-2"></i> Changer le mot de passe
                            </button>
                            <button type="reset" class="btn btn-outline-secondary" id="reset-btn">
                                <i class="fas fa-redo me-2"></i> Réinitialiser
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Conseils de sécurité -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Conseils de sécurité</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">Utilisez un mot de passe unique que vous n'utilisez nulle part ailleurs</li>
                        <li class="mb-2">Évitez les informations personnelles (nom, date de naissance, etc.)</li>
                        <li class="mb-2">Changez régulièrement votre mot de passe</li>
                        <li>Après avoir changé votre mot de passe, vous serez déconnecté de tous les autres appareils</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function checkPasswordStrength(password) {
    let strength = 0;
    const bar = document.getElementById('password-strength-bar');
    const text = document.getElementById('password-strength-text');

    // Vérifier les exigences
    checkPasswordRequirements(password);

    // Longueur
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;

    // Lettres minuscules
    if (/[a-z]/.test(password)) strength++;

    // Lettres majuscules
    if (/[A-Z]/.test(password)) strength++;

    // Chiffres
    if (/[0-9]/.test(password)) strength++;

    // Caractères spéciaux
    if (/[@$!%*?&]/.test(password)) strength++;

    // Mettre à jour la barre et le texte
    let strengthClass = '';
    let strengthText = '';

    if (password.length === 0) {
        bar.style.width = '0%';
        bar.className = 'password-strength';
        text.textContent = '';
        return;
    }

    if (strength < 3) {
        strengthClass = 'strength-weak';
        strengthText = 'Faible';
    } else if (strength < 5) {
        strengthClass = 'strength-medium';
        strengthText = 'Moyen';
    } else {
        strengthClass = 'strength-strong';
        strengthText = 'Fort';
    }

    const width = Math.min(100, (strength / 6) * 100);
    bar.style.width = width + '%';
    bar.className = 'password-strength ' + strengthClass;
    text.textContent = strengthText;
    text.className = strengthClass === 'strength-weak' ? 'text-danger' :
                     strengthClass === 'strength-medium' ? 'text-warning' : 'text-success';
}

function checkPasswordRequirements(password) {
    // Longueur
    const lengthReq = document.getElementById('req-length');
    lengthReq.className = password.length >= 8 ? 'requirement-met' : 'requirement-not-met';
    lengthReq.querySelector('i').className = password.length >= 8 ?
        'fas fa-check-circle me-2' : 'fas fa-circle me-2';

    // Majuscule
    const uppercaseReq = document.getElementById('req-uppercase');
    const hasUppercase = /[A-Z]/.test(password);
    uppercaseReq.className = hasUppercase ? 'requirement-met' : 'requirement-not-met';
    uppercaseReq.querySelector('i').className = hasUppercase ?
        'fas fa-check-circle me-2' : 'fas fa-circle me-2';

    // Minuscule
    const lowercaseReq = document.getElementById('req-lowercase');
    const hasLowercase = /[a-z]/.test(password);
    lowercaseReq.className = hasLowercase ? 'requirement-met' : 'requirement-not-met';
    lowercaseReq.querySelector('i').className = hasLowercase ?
        'fas fa-check-circle me-2' : 'fas fa-circle me-2';

    // Chiffre
    const numberReq = document.getElementById('req-number');
    const hasNumber = /[0-9]/.test(password);
    numberReq.className = hasNumber ? 'requirement-met' : 'requirement-not-met';
    numberReq.querySelector('i').className = hasNumber ?
        'fas fa-check-circle me-2' : 'fas fa-circle me-2';

    // Caractère spécial
    const specialReq = document.getElementById('req-special');
    const hasSpecial = /[@$!%*?&]/.test(password);
    specialReq.className = hasSpecial ? 'requirement-met' : 'requirement-not-met';
    specialReq.querySelector('i').className = hasSpecial ?
        'fas fa-check-circle me-2' : 'fas fa-circle me-2';
}

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    const matchDiv = document.getElementById('password-match');

    if (confirmPassword.length === 0) {
        matchDiv.textContent = '';
        matchDiv.className = 'form-text';
        return;
    }

    if (password === confirmPassword) {
        matchDiv.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i> Les mots de passe correspondent';
        matchDiv.className = 'form-text text-success';
    } else {
        matchDiv.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i> Les mots de passe ne correspondent pas';
        matchDiv.className = 'form-text text-danger';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Basculer la visibilité du mot de passe
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Validation du formulaire
    const form = document.getElementById('password-form');
    const submitBtn = document.getElementById('submit-btn');
    const resetBtn = document.getElementById('reset-btn');

    form.addEventListener('submit', function(e) {
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        // Vérifications
        if (newPassword.length < 8) {
            e.preventDefault();
            showToast('error', 'Le mot de passe doit contenir au moins 8 caractères');
            return false;
        }

        if (newPassword !== confirmPassword) {
            e.preventDefault();
            showToast('error', 'Les mots de passe ne correspondent pas');
            return false;
        }

        // Afficher un indicateur de chargement
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement...';
        submitBtn.disabled = true;
    });

    // Réinitialiser le formulaire
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            form.reset();
            document.getElementById('password-strength-bar').style.width = '0%';
            document.getElementById('password-strength-text').textContent = '';
            document.getElementById('password-match').textContent = '';

            // Réinitialiser les exigences
            document.querySelectorAll('[id^="req-"]').forEach(req => {
                req.className = 'requirement-not-met';
                req.querySelector('i').className = 'fas fa-circle me-2';
            });
        });
    }

    // Fonction pour afficher les toasts
    function showToast(type, message) {
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        toastContainer.appendChild(toast);
        document.body.appendChild(toastContainer);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Nettoyer après la disparition du toast
        toast.addEventListener('hidden.bs.toast', function() {
            toastContainer.remove();
        });
    }
});
</script>
@endsection
