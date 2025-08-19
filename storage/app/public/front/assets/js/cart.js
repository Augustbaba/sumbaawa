(function($) {
    $(document).ready(function () {
        // Configurer le token CSRF pour AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Définir le mixin pour le toast
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });


        // Fonction pour formater les prix avec virgule pour les milliers
        function formatPrice(number) {
            return number.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        // Fonction pour mettre à jour le compteur du panier
        function updateCartCount(count) {
            if ($('.cart_qty_cls').length) {
                $('.cart_qty_cls').text(count);
            }
            if ($('#cart-item-count').length) {
                $('#cart-item-count').text(count);
            }
            // Activer/Désactiver les boutons en fonction du compteur
            $('.view-cart, .checkout, .clear-cart').toggleClass('disabled', count === 0);
        }

        // Initialiser le compteur du panier au chargement
        function initializeCartCount() {
            $.ajax({
                url: '/cart',
                method: 'GET',
                success: function (response) {
                    const itemCount = response.cart.items ? response.cart.items.length : 0;
                    updateCartCount(itemCount);
                },
                error: function (xhr) {
                    console.error('Erreur lors de la récupération du panier:', xhr.responseText);
                    Toast.fire({
                        icon: "error",
                        title: "Erreur lors de la récupération du panier"
                    });
                }
            });
        }

        // Mettre à jour la table du panier
        function updateCartTable(cart) {
            const tbody = $('.cart-table tbody');
            let total = 0;
            tbody.empty();

            if (!cart.items || cart.items.length === 0) {
                tbody.append('<tr><td colspan="6"><p>Le panier est vide.</p></td></tr>');
            } else {
                cart.items.forEach(item => {
                    if (!item.id || !item.name || !item.price || !item.quantity || !item.image_main || !item.product_url) {
                        console.error('Données incomplètes pour l\'élément du panier:', item);
                        return;
                    }

                    const priceStr = item.price.toString().replace(/,/g, '');
                    const price = parseFloat(priceStr);
                    const originalPriceStr = item.original_price ? item.original_price.toString().replace(/,/g, '') : null;
                    const originalPrice = originalPriceStr ? parseFloat(originalPriceStr) : null;

                    if (isNaN(price)) {
                        console.error('Prix non valide pour l\'élément:', item);
                        return;
                    }

                    let itemHtml = `
                        <tr data-product-id="${item.id}">
                            <td>
                                <a href="${item.product_url}">
                                    <img src="${item.image_main}" class="img-fluid" alt="${item.name}">
                                </a>
                            </td>
                            <td>
                                <a href="${item.product_url}">${item.name}</a>
                                <div class="mobile-cart-content row">
                                    <div class="col">
                                        <div class="qty-box">
                                            <div class="input-group qty-container">
                                                <button class="btn qty-btn-minus" data-product-id="${item.id}">
                                                    <i class="ri-arrow-left-s-line"></i>
                                                </button>
                                                <input type="number" readonly name="qty" class="form-control input-qty" value="${item.quantity}">
                                                <button class="btn qty-btn-plus" data-product-id="${item.id}">
                                                    <i class="ri-arrow-right-s-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col table-price">
                                        <h2 class="td-color">$${formatPrice(price)}</h2>
                                        ${originalPrice && originalPrice > price ? `<del>$${formatPrice(originalPrice)}</del>` : ''}
                                    </div>
                                    <div class="col">
                                        <h2 class="td-color">
                                            <a href="#!" class="icon remove-btn" data-product-id="${item.id}">
                                                <i class="ri-close-line"></i>
                                            </a>
                                        </h2>
                                    </div>
                                </div>
                            </td>
                            <td class="table-price">
                                <h2>$${formatPrice(price)}</h2>
                                ${originalPrice && originalPrice > price ? `
                                    <del>$${formatPrice(originalPrice)}</del>
                                    <h6 class="theme-color">Vous économisez : $${formatPrice(originalPrice - price)}</h6>
                                ` : ''}
                            </td>
                            <td>
                                <div class="qty-box">
                                    <div class="input-group qty-container">
                                        <button class="btn qty-btn-minus" data-product-id="${item.id}">
                                            <i class="ri-arrow-left-s-line"></i>
                                        </button>
                                        <input type="number" readonly name="qty" class="form-control input-qty" value="${item.quantity}">
                                        <button class="btn qty-btn-plus" data-product-id="${item.id}">
                                            <i class="ri-arrow-right-s-line"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <h2 class="td-color">$${formatPrice(price * item.quantity)}</h2>
                                </td>
                                <td>
                                    <a href="#!" class="icon remove-btn" data-product-id="${item.id}">
                                        <i class="ri-close-line"></i>
                                    </a>
                                </td>
                            </tr>
                    `;
                    tbody.append(itemHtml);
                    total += price * item.quantity;
                });
            }

            if ($('.cart-table tfoot h2').length) {
                $('.cart-table tfoot h2').text(`$${formatPrice(total)}`);
            }
            updateCartCount(cart.items ? cart.items.length : 0);
            attachCartEvents();
        }

        // Mettre à jour l'offcanvas du panier
        function updateCartOffcanvas(cart) {
            const cartOffcanvas = $('#cartOffcanvas .offcanvas-body');
            if (!cartOffcanvas.length) {
                console.error('L\'élément #cartOffcanvas n\'existe pas dans le DOM.');
                return;
            }

            const totalStr = cart.total.toString().replace(/,/g, '');
            const totalNum = parseFloat(totalStr);
            if (isNaN(totalNum)) {
                console.error('Total du panier non valide:', cart.total);
                return;
            }

            let cartHtml = `
                <div class="sidebar-title">
                    <a href="/cart/clear" class="clear-cart ${!cart.items || cart.items.length === 0 ? 'disabled' : ''}">Vider le panier</a>
                </div>
                <div class="cart-media">
                    <ul class="cart-product">
            `;

            if (!cart.items || cart.items.length === 0) {
                cartHtml += `
                    <li><p>Le panier est vide.</p></li>
                `;
            } else {
                cart.items.forEach(item => {
                    const priceStr = item.price.toString().replace(/,/g, '');
                    const price = parseFloat(priceStr);
                    if (isNaN(price)) {
                        console.error('Prix non valide pour l\'élément:', item);
                        return;
                    }

                    cartHtml += `
                        <li>
                            <div class="media">
                                <a href="${item.product_url}">
                                    <img src="${item.image_main}" class="img-fluid" alt="${item.name}">
                                </a>
                                <div class="media-body">
                                    <a href="${item.product_url}">
                                        <h4>${item.name}</h4>
                                    </a>
                                    <h4 class="quantity">
                                        <span>${item.quantity} x $${formatPrice(price)}</span>
                                    </h4>
                                    <div class="qty-box">
                                        <div class="input-group qty-container">
                                            <button class="btn qty-btn-minus" data-product-id="${item.id}">
                                                <i class="ri-subtract-line"></i>
                                            </button>
                                            <input type="number" readonly name="qty" class="form-control input-qty" value="${item.quantity}">
                                            <button class="btn qty-btn-plus" data-product-id="${item.id}">
                                                <i class="ri-add-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="close-circle">
                                        <button class="close_button delete-button" data-product-id="${item.id}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    `;
                });
            }

            cartHtml += `
                    </ul>
                    <ul class="cart_total">
                        <li>
                            <div class="total">
                                <h5>Sous-total : <span>$${formatPrice(totalNum)}</span></h5>
                            </div>
                        </li>
                        <li>
                            <div class="buttons">
                                <a href="/cart" class="btn view-cart ${!cart.items || cart.items.length === 0 ? 'disabled' : ''}">Voir le panier</a>
                                <a href="/checkout" class="btn checkout ${!cart.items || cart.items.length === 0 ? 'disabled' : ''}">Passer la commande</a>
                            </div>
                        </li>
                    </ul>
                </div>
            `;

            cartOffcanvas.html(cartHtml);
            updateCartCount(cart.items ? cart.items.length : 0);
            attachCartEvents();
        }

        // Gestion des événements du panier
        function attachCartEvents() {
            $('.qty-btn-minus').off('click').on('click', function () {
                const productId = $(this).data('product-id');
                const qtyInput = $(this).closest('.qty-box').find('.input-qty');
                let qty = parseInt(qtyInput.val());
                if (qty > 1) {
                    $.ajax({
                        url: '/cart/update',
                        method: 'POST',
                        data: {
                            product_id: productId,
                            quantity: qty - 1
                        },
                        success: function (response) {
                            updateCartTable(response.cart);
                            updateCartOffcanvas(response.cart);
                        },
                        error: function (xhr) {
                            console.error('Erreur lors de la mise à jour de la quantité:', xhr.responseText);
                            Toast.fire({
                                icon: "error",
                                title: "Erreur lors de la mise à jour de la quantité"
                            });
                        }
                    });
                }
            });

            $('.qty-btn-plus').off('click').on('click', function () {
                const productId = $(this).data('product-id');
                const qtyInput = $(this).closest('.qty-box').find('.input-qty');
                let qty = parseInt(qtyInput.val());
                $.ajax({
                    url: '/cart/update',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        quantity: qty + 1
                    },
                    success: function (response) {
                        updateCartTable(response.cart);
                        updateCartOffcanvas(response.cart);
                    },
                    error: function (xhr) {
                        console.error('Erreur lors de la mise à jour de la quantité:', xhr.responseText);
                        Toast.fire({
                            icon: "error",
                            title: "Erreur lors de la mise à jour de la quantité"
                        });
                    }
                });
            });

            $('.remove-btn, .delete-button').off('click').on('click', function (e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                Swal.fire({
                    title: "Êtes-vous sûr ?",
                    text: "Vous ne pourrez pas annuler cette action !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Oui, supprimer !",
                    cancelButtonText: "Annuler"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/cart/remove',
                            method: 'POST',
                            data: {
                                product_id: productId
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: "Supprimé !",
                                    text: "Le produit a été supprimé du panier.",
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                updateCartTable(response.cart);
                                updateCartOffcanvas(response.cart);
                            },
                            error: function (xhr) {
                                console.error('Erreur lors de la suppression du produit:', xhr.responseText);
                                Toast.fire({
                                    icon: "error",
                                    title: "Erreur lors de la suppression du produit"
                                });
                            }
                        });
                    }
                });
            });

            $('.clear-cart').off('click').on('click', function (e) {
                e.preventDefault();
                if ($(this).hasClass('disabled')) return;
                Swal.fire({
                    title: "Êtes-vous sûr ?",
                    text: "Vous ne pourrez pas annuler cette action !",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Oui, vider le panier !",
                    cancelButtonText: "Annuler"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $(this).attr('href'),
                            method: 'POST',
                            data: {},
                            success: function (response) {
                                Swal.fire({
                                    title: "Supprimé !",
                                    text: "Le panier a été vidé.",
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                updateCartTable(response.cart);
                                updateCartOffcanvas(response.cart);
                            },
                            error: function (xhr) {
                                console.error('Erreur lors du vidage du panier:', xhr.responseText);
                                Toast.fire({
                                    icon: "error",
                                    title: "Erreur lors du vidage du panier"
                                });
                            }
                        });
                    }
                });
            });
        }

        // Exposer les fonctions nécessaires pour être appelées ailleurs
        window.cartUtils = {
            initializeCartCount: initializeCartCount,
            updateCartTable: updateCartTable,
            updateCartOffcanvas: updateCartOffcanvas,
            attachCartEvents: attachCartEvents
        };
    });
})(jQuery);
