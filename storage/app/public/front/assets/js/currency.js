// public/front/assets/js/currency.js
(function(window) {
    'use strict';

    let currencyConfig = {
        code: 'XOF',
        symbol: 'FCFA',
        exchange_rate: 1,
        decimals: 0,
        xof_rate: 1,
        is_xof: true
    };

    function loadCurrencyConfig() {
        return fetch('/api/current-currency')
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                currencyConfig = {
                    code: data.code,
                    symbol: data.symbol,
                    exchange_rate: data.exchange_rate,
                    decimals: data.decimals,
                    xof_rate: data.xof_rate,
                    is_xof: data.code === 'XOF'
                };
                console.log('Currency config loaded:', currencyConfig);
                return data;
            })
            .catch(error => {
                console.error('Erreur lors du chargement de la devise:', error);
                return currencyConfig;
            });
    }

    /**
     * Convertir un montant de XOF vers la devise courante
     * @param {number} amount - Montant en XOF (devise de base en BD)
     * @returns {number} - Montant converti dans la devise courante
     */
    function convertFromXOF(amount) {
        if (!amount || isNaN(amount)) return 0;

        const numAmount = parseFloat(amount.toString().replace(/[,\s]/g, ''));

        // Si la devise courante est XOF, pas de conversion
        if (currencyConfig.code === 'XOF') {
            return numAmount;
        }

        // Convertir de XOF vers la devise courante : montant / taux
        return numAmount / currencyConfig.exchange_rate;
    }

    /**
     * Convertir un montant vers XOF
     * @param {number} amount - Montant dans la devise source
     * @param {string} fromCurrency - Code de la devise source
     * @returns {number} - Montant en XOF
     */
    function convertToXOF(amount, fromCurrency = null) {
        if (!amount || isNaN(amount)) return 0;

        const numAmount = parseFloat(amount.toString().replace(/[,\s]/g, ''));

        // Si pas de devise source spécifiée, utiliser la devise courante
        if (!fromCurrency) {
            fromCurrency = currencyConfig.code;
        }

        // Si la devise source est XOF, pas de conversion
        if (fromCurrency === 'XOF') {
            return numAmount;
        }

        // Convertir vers XOF : montant * taux
        const fromRate = getExchangeRate(fromCurrency);
        return numAmount * fromRate;
    }

    /**
     * Obtenir le taux de change d'une devise par rapport à XOF
     */
    function getExchangeRate(currencyCode) {
        // Si c'est la devise courante, utiliser son taux
        if (currencyCode === currencyConfig.code) {
            return currencyConfig.exchange_rate;
        }

        // Pour d'autres devises, vous pourriez avoir besoin d'un appel API
        // Pour le moment, retourner 1 par défaut
        return 1;
    }

    /**
     * Formater un montant avec la devise
     * @param {number} amount - Montant en XOF
     */
    function formatCurrency(amount, showSymbol = true) {
        const convertedAmount = convertFromXOF(amount);
        const decimals = currencyConfig.decimals;

        // Formater le nombre avec les séparateurs de milliers
        let formatted = convertedAmount.toLocaleString('fr-FR', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });

        // Pour XOF, remplacer les espaces par des points si nécessaire
        if (currencyConfig.code === 'XOF') {
            formatted = formatted.replace(/\s/g, '.');
        }

        if (showSymbol) {
            return `${formatted} ${currencyConfig.symbol}`;
        }

        return formatted;
    }

    /**
     * Formater un prix sans symbole
     */
    function formatPrice(number) {
        const convertedAmount = convertFromXOF(number);
        const decimals = currencyConfig.decimals;

        let formatted = convertedAmount.toLocaleString('fr-FR', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });

        // Pour XOF, remplacer les espaces par des points
        if (currencyConfig.code === 'XOF') {
            formatted = formatted.replace(/\s/g, '.');
        }

        return formatted;
    }

    /**
     * Formater un montant brut (sans conversion)
     * Utile pour l'affichage dans les champs de formulaire
     */
    function formatRawPrice(number) {
        const decimals = currencyConfig.code === 'XOF' ? 0 : 2;
        let formatted = number.toLocaleString('fr-FR', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });

        if (currencyConfig.code === 'XOF') {
            formatted = formatted.replace(/\s/g, '.');
        }

        return formatted;
    }

    /**
     * Convertir un montant saisi par l'utilisateur vers XOF
     */
    function parseUserInput(amount) {
        if (!amount) return 0;

        // Nettoyer l'entrée
        let cleaned = amount.toString().replace(/[^\d.,]/g, '');

        // Remplacer la virgule par un point pour le parsing
        cleaned = cleaned.replace(/,/g, '.');

        const numAmount = parseFloat(cleaned);

        if (isNaN(numAmount)) return 0;

        // Convertir vers XOF si nécessaire
        return convertToXOF(numAmount);
    }

    // Exposer l'API publique
    window.CurrencyHelper = {
        load: loadCurrencyConfig,
        convertFromXOF: convertFromXOF,
        convertToXOF: convertToXOF,
        format: formatCurrency,
        formatPrice: formatPrice,
        formatRawPrice: formatRawPrice,
        parseUserInput: parseUserInput,
        getSymbol: () => currencyConfig.symbol,
        getCode: () => currencyConfig.code,
        getDecimals: () => currencyConfig.decimals,
        getSymbolHTML: () => `<span class="currency-symbol" style="font-size: 0.9em; color: gray;">${currencyConfig.symbol}</span>`,
        getConfig: () => currencyConfig,
        getExchangeRate: getExchangeRate,
        refresh: loadCurrencyConfig
    };

    // Charger automatiquement la configuration au démarrage
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            loadCurrencyConfig().then(() => {
                // Mettre à jour tous les prix après chargement
                updateAllPrices();
            });
        });
    } else {
        loadCurrencyConfig().then(() => {
            updateAllPrices();
        });
    }

    // Fonction pour mettre à jour tous les prix sur la page
    function updateAllPrices() {
        console.log('Updating all prices with currency:', currencyConfig.code);

        // Mettre à jour les prix avec la classe .price
        document.querySelectorAll('.price').forEach(priceElement => {
            updatePriceElement(priceElement);
        });

        // Mettre à jour les prix avec data-price
        document.querySelectorAll('[data-price]').forEach(element => {
            updateDataPriceElement(element);
        });

        // Mettre à jour les inputs de prix
        document.querySelectorAll('input[data-price-original]').forEach(input => {
            updatePriceInput(input);
        });
    }

    function updatePriceElement(element) {
        const originalText = element.getAttribute('data-original-text') || element.textContent;
        element.setAttribute('data-original-text', originalText);

        // Extraire le montant numérique
        const match = originalText.match(/(\d[\d\s.,]*)/);
        if (match) {
            let amount = parseFloat(match[1].replace(/[\s.,]/g, ''));
            if (!isNaN(amount)) {
                const formatted = CurrencyHelper.format(amount);
                element.innerHTML = originalText.replace(match[1], formatted);
            }
        }
    }

    function updateDataPriceElement(element) {
        const price = parseFloat(element.getAttribute('data-price'));
        if (!isNaN(price)) {
            const formatted = CurrencyHelper.format(price);
            element.textContent = formatted;
        }
    }

    function updatePriceInput(input) {
        const originalPrice = parseFloat(input.getAttribute('data-price-original'));
        if (!isNaN(originalPrice)) {
            const converted = CurrencyHelper.convertFromXOF(originalPrice);
            input.value = CurrencyHelper.formatRawPrice(converted);
        }
    }

    // Écouter les changements de devise
    document.addEventListener('currencyChanged', function() {
        CurrencyHelper.refresh().then(() => {
            updateAllPrices();
        });
    });

})(window);
