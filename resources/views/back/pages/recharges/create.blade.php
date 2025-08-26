@extends('back.layouts.master')
@section('title', 'Système de Paiement WisaPay')
@section('content')
<!-- content -->
<div class="content">
    <div class="text-center mb-5">
        
        <img src="{{ asset(FrontHelper::getEnvFolder() .'partners/p1.png') }}" alt="WisaPay Logo" style="height: 80px;">
        <h2 class="mt-3">Système de Paiement Sécurisé</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-5">
                    <div id="payment-flow">
                        <!-- Étape 1: Saisie des informations -->
                        <div id="step-1" class="payment-step active">
                            <h4 class="mb-4 text-center"><i class="bi bi-person-circle me-2"></i>Informations Client</h4>
                            <form id="paymentForm">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Numéro de Compte</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                                            <input type="text" class="form-control" id="accountNumber" placeholder="Ex: WP123456789" required>
                                        </div>
                                    </div><br>
                                    <div class="col-md-6">
                                        <label class="form-label">Nom & Prénom</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input type="text" class="form-control" id="clientName" placeholder="Ex: Jean Dupont" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Montant (XOF)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-cash"></i></span>
                                        <input type="number" class="form-control" id="amount" placeholder="Ex: 25000" required>
                                        <span class="input-group-text">XOF</span>
                                    </div>
                                    <small class="text-muted">Frais: 1% (min 100 XOF, max 1000 XOF)</small>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="button" id="nextStepBtn" class="btn btn-primary btn-lg">
                                        <i class="bi bi-arrow-right-circle me-2"></i>Continuer
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Étape 2: Saisie du code PIN (cachée initialement) -->
                        <div id="step-2" class="payment-step" style="display: none;">
                            <div class="text-center mb-4">
                                <div class="avatar avatar-lg bg-light rounded-circle mb-3">
                                    <i class="bi bi-shield-lock fs-3"></i>
                                </div>
                                <h4>Validation Sécurisée</h4>
                                <p class="text-muted">Veuillez entrer votre code PIN pour confirmer</p>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Numéro de Compte:</span>
                                    <strong id="confirmAccount"></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Bénéficiaire:</span>
                                    <strong id="confirmName"></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Montant:</span>
                                    <strong id="confirmAmount"></strong>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Code PIN</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="pinCode" placeholder="•••••" maxlength="5">
                                </div>
                                <small class="text-muted">Code PIN à 5 chiffres (12345 pour démo)</small>
                            </div>
                            
                            <div class="alert alert-danger" id="errorMessage" style="display: none;"></div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="button" id="backStepBtn" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Retour
                                </button>
                                <button type="button" id="confirmPaymentBtn" class="btn btn-success">
                                    <i class="bi bi-check-circle me-2"></i>Confirmer
                                </button>
                            </div>
                        </div>
                        
                        <!-- Étape 3: Confirmation de paiement (cachée initialement) -->
                        <div id="step-3" class="payment-step text-center" style="display: none;">
                            <div class="mb-4">
                                <div class="avatar avatar-lg bg-success text-white rounded-circle mb-3">
                                    <i class="bi bi-check2-circle fs-3"></i>
                                </div>
                                <h4>Paiement Réussi!</h4>
                                <p class="text-muted">Votre transaction a été effectuée avec succès</p>
                            </div>
                            
                            <div class="card border-success mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Référence:</span>
                                        <strong>WP{{ now()->format('YmdHis') }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Date:</span>
                                        <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Montant:</span>
                                        <strong id="successAmount"></strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Statut:</span>
                                        <strong class="text-success">Complété</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="newPaymentBtn" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Nouveau Paiement
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ./ content -->

<style>
    .payment-step {
        transition: all 0.3s ease;
    }
    
    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
    }
    
    #pinCode {
        letter-spacing: 5px;
        font-size: 1.2rem;
        text-align: center;
        font-family: monospace;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');
    const nextStepBtn = document.getElementById('nextStepBtn');
    const backStepBtn = document.getElementById('backStepBtn');
    const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');
    const newPaymentBtn = document.getElementById('newPaymentBtn');
    const errorMessage = document.getElementById('errorMessage');
    
    // Champs du formulaire
    const accountNumber = document.getElementById('accountNumber');
    const clientName = document.getElementById('clientName');
    const amount = document.getElementById('amount');
    const pinCode = document.getElementById('pinCode');
    
    // Champs de confirmation
    const confirmAccount = document.getElementById('confirmAccount');
    const confirmName = document.getElementById('confirmName');
    const confirmAmount = document.getElementById('confirmAmount');
    const successAmount = document.getElementById('successAmount');

    // Gestion des étapes
    nextStepBtn.addEventListener('click', function() {
        // Validation de l'étape 1
        if(!accountNumber.value || !clientName.value || !amount.value) {
            showError('Veuillez remplir tous les champs obligatoires');
            return;
        }
        
        const amountValue = parseFloat(amount.value);
        if(amountValue <= 0) {
            showError('Le montant doit être supérieur à zéro');
            return;
        }
        
        // Calcul des frais
        const fees = calculateFees(amountValue);
        const total = amountValue + fees;
        
        // Mise à jour de l'étape 2
        confirmAccount.textContent = accountNumber.value;
        confirmName.textContent = clientName.value;
        confirmAmount.textContent = formatCurrency(total) + ` (dont frais: ${formatCurrency(fees)})`;
        
        // Passage à l'étape 2
        step1.classList.remove('active');
        step1.style.display = 'none';
        step2.style.display = 'block';
        errorMessage.style.display = 'none';
    });
    
    backStepBtn.addEventListener('click', function() {
        // Retour à l'étape 1
        step2.style.display = 'none';
        step1.style.display = 'block';
        step1.classList.add('active');
        errorMessage.style.display = 'none';
    });
    
    confirmPaymentBtn.addEventListener('click', function() {
        // Validation du code PIN
        if(!pinCode.value) {
            showError('Veuillez entrer votre code PIN');
            return;
        }
        
        const amountValue = parseFloat(amount.value);
        
        // Vérification du code PIN (12345 pour démo)
        if(pinCode.value !== '12345') {
            showError('Code PIN incorrect. Veuillez réessayer.');
            return;
        }
        
        // Vérification du solde pour montant > 50,000 XOF
        if(amountValue > 50000) {
            showError('Solde insuffisant pour effectuer cette transaction');
            return;
        }
        
        // Calcul du total
        const fees = calculateFees(amountValue);
        const total = amountValue + fees;
        
        // Mise à jour de l'étape 3
        successAmount.textContent = formatCurrency(total);
        
        // Passage à l'étape 3
        step2.style.display = 'none';
        step3.style.display = 'block';
    });
    
    newPaymentBtn.addEventListener('click', function() {
        // Réinitialisation du formulaire
        accountNumber.value = '';
        clientName.value = '';
        amount.value = '';
        pinCode.value = '';
        
        // Retour à l'étape 1
        step3.style.display = 'none';
        step1.style.display = 'block';
        step1.classList.add('active');
    });
    
    // Fonctions utilitaires
    function calculateFees(amount) {
        // Calcul des frais: 1% (min 100 XOF, max 1000 XOF)
        return Math.min(Math.max(amount * 0.01, 100), 1000);
    }
    
    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR', { 
            style: 'currency', 
            currency: 'XOF',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }
    
    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        
        // Animation d'erreur
        errorMessage.style.animation = 'shake 0.5s';
        setTimeout(() => {
            errorMessage.style.animation = '';
        }, 500);
    }
});

// Animation de shake pour les erreurs
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);
</script>
@endsection