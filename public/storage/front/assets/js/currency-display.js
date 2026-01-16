// public/front/assets/js/currency-display.js
(function(window) {
    'use strict';

    /**
     * Utilitaire pour afficher les prix dans la bonne devise
     */
    window.CurrencyDisplay = {
        /**
         * Mettre à jour tous les prix sur la page
         */
        updateAllPrices: function() {
            if (typeof CurrencyHelper === 'undefined') {
                console.error('CurrencyHelper not loaded');
                return;
            }

            // Mettre à jour tous les éléments avec data-price
            document.querySelectorAll('[data-price]').forEach(function(element) {
                const priceInXOF = parseFloat(element.dataset.price);
                const originalPrice = element.dataset.originalPrice;

                if (!isNaN(priceInXOF)) {
                    // Formater le prix principal
                    const formattedPrice = CurrencyHelper.format(priceInXOF);

                    // Construire le HTML du prix
                    let priceHtml = formattedPrice;

                    // Ajouter le prix original barré si disponible
                    if (originalPrice && parseFloat(originalPrice) > 0) {
                        const originalPriceNum = parseFloat(originalPrice);
                        const formattedOriginalPrice = CurrencyHelper.format(originalPriceNum);
                        const discount = Math.round(((originalPriceNum - priceInXOF) / originalPriceNum) * 100);
                        priceHtml += ` <del>${formattedOriginalPrice}</del>`;
                        priceHtml += ` <span class="discounted-price">${discount}% Off</span>`;
                    }

                    element.innerHTML = priceHtml;
                }
            });
        },

        /**
         * Initialiser l'affichage des prix
         */
        init: function() {
            const self = this;

            // Attendre que CurrencyHelper soit chargé
            if (typeof CurrencyHelper !== 'undefined') {
                CurrencyHelper.load().then(function() {
                    self.updateAllPrices();
                });
            } else {
                console.warn('CurrencyHelper not available, prices will display in default currency');
            }
        }
    };

    // Auto-initialisation au chargement du DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            window.CurrencyDisplay.init();
        });
    } else {
        window.CurrencyDisplay.init();
    }

})(window);
