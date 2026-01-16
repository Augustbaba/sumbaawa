// public/front/assets/js/currency-switcher.js
document.addEventListener('DOMContentLoaded', function() {
    // Gérer le switch de devise
    document.querySelectorAll('.currency-switch').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const currencyCode = this.dataset.currency;

            // Envoyer la requête au backend
            fetch('/currency/switch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ currency_code: currencyCode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Sauvegarder aussi en localStorage comme backup
                    localStorage.setItem('currency_code', currencyCode);

                    // Afficher un message de succès
                    if (window.Swal) {
                        Swal.fire({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            icon: "success",
                            title: `Devise changée vers ${currencyCode}`
                        }).then(() => {
                            // Recharger la page pour appliquer les changements
                            location.reload();
                        });
                    } else {
                        // Recharger la page directement si SweetAlert n'est pas disponible
                        location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Erreur lors du changement de devise:', error);
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: "error",
                        title: "Erreur lors du changement de devise"
                    });
                }
            });
        });
    });

    // Backup avec localStorage si les cookies sont bloqués
    if (!document.cookie.includes('currency_code')) {
        const savedCurrency = localStorage.getItem('currency_code');
        if (savedCurrency) {
            // Appliquer la devise du localStorage
            fetch('/currency/switch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ currency_code: savedCurrency })
            });
        }
    }
});
