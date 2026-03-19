<?php

use App\Http\Controllers\AdminCommandeController;
use App\Http\Controllers\AlibabaImportController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SumbaawaController;
use App\Http\Controllers\RechargeController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ElongoPayController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SousCategorieController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\NihaoTravelController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaypalWithdrawalController;
use App\Http\Controllers\ShippingPaymentController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/




// Routes publiques (accessibles sans authentification)
Route::get('/', function () {
    return view('front.pages.index');
})->name('index');

// ElongoPay
Route::post('/nihao-travel/elongopay/capture', [NihaoTravelController::class, 'captureElongoPay'])
     ->name('nihao.travel.elongopay.capture');
Route::post('/shipping/elongopay/capture', [ShippingPaymentController::class, 'captureElongoPay'])
     ->name('shipping.elongopay.capture');
Route::post('/elongopay/capture', [ElongoPayController::class, 'capture'])->name('elongopay.capture');

Route::get('/categories/{categorie}/filter', [SumbaawaController::class, 'filter'])->name('categories.filter');
Route::get('/categories/{categorie:slug}/details', [SumbaawaController::class, 'categoriesSingle'])->name('categories.single');
Route::get('/categories-page', [SumbaawaController::class, 'categoriesPage'])->name('categories.page');
Route::get('/sous-categories/{sousCategorie:slug}/produits', [SumbaawaController::class, 'sousCategoriesDetail'])->name('sousCategories.single');
Route::get('/produits/details/{produit:slug}', [SumbaawaController::class, 'produitDetail'])->name('produits.single');
Route::post('/cart/add', [SumbaawaController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [SumbaawaController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [SumbaawaController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/clear', [SumbaawaController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart', [SumbaawaController::class, 'viewCart'])->name('cart.view');
Route::get('/cart/get', [SumbaawaController::class, 'getCart'])->name('cart.get');
Route::get('/checkout', [SumbaawaController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::post('/checkout/process', [SumbaawaController::class, 'checkoutProcess'])->name('checkout.process');
Route::get('/wishlist/add/{produit:slug}', [SumbaawaController::class, 'addWishlist'])->middleware(['auth'])->name('wishlist.add');
Route::get('/mes-favoris', [SumbaawaController::class, 'myWishlist'])->middleware(['auth'])->name('wishlist.my');
Route::post('/store-delivery-info', [SumbaawaController::class, 'storeDeliveryInfo'])->name('store_delivery_info');
Route::post('/clear-delivery-info', [SumbaawaController::class, 'clearDeliveryInfo'])->name('clear_delivery_info');
Route::get('/search', [SumbaawaController::class, 'search'])->name('search');
Route::get('/contact-us', [SumbaawaController::class, 'contact'])->name('contact');
Route::post('/contact-send', [SumbaawaController::class, 'contactSend'])->name('contact.send');

Route::get('/verify-email/{token}', [RegisteredUserController::class, 'verify'])->name('verify.email');

// Routes Nihao Travel
Route::get('/nihao-travel', [NihaoTravelController::class, 'index'])->name('nihao.travel');
Route::prefix('nihao-travel')->name('nihao.travel.')->group(function () {
    Route::post('/create-order', [NihaoTravelController::class, 'createPayPalOrder'])->name('create-order');
    Route::post('/capture-order', [NihaoTravelController::class, 'capturePayPalOrder'])->name('capture-order');
    Route::get('/confirmation', [NihaoTravelController::class, 'confirmation'])->name('confirmation');
    Route::get('/cancel', [NihaoTravelController::class, 'cancel'])->name('cancel');
    Route::get('/success', [NihaoTravelController::class, 'confirmation'])->name('success');
});

// Routes PayPal
Route::post('/checkout/paypal/create-order', [SumbaawaController::class, 'createPayPalOrder'])->name('paypal.create-order');
Route::post('/checkout/paypal/capture-order', [SumbaawaController::class, 'capturePayPalOrder'])->name('paypal.capture-order');
Route::get('/checkout/success', [SumbaawaController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/checkout/cancel', [SumbaawaController::class, 'checkoutCancel'])->name('checkout.cancel');

// Confirmation de commande
Route::get('/checkout/confirmation', [SumbaawaController::class, 'confirmation'])->name('order.confirmation');

// Routes du profil utilisateur
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::get('/edit-password', [ProfileController::class, 'editPassword'])->name('edit-password');
    Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    Route::delete('/delete-avatar', [ProfileController::class, 'deleteAvatar'])->name('delete-avatar');
});

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');


    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::put('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead'])->name('readAll');
        Route::get('/unread', [NotificationController::class, 'unread'])->name('unread');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // Recharges
    Route::get('/recharges/create', [RechargeController::class, 'create'])->name('recharges.create');
    Route::post('/recharges/store', [RechargeController::class, 'store'])->name('recharges.store');
    Route::get('/recharges/{recharge}/edit', [RechargeController::class, 'edit'])->name('recharges.edit');
    Route::put('/recharges/{recharge}/update', [RechargeController::class, 'update'])->name('recharges.update');
    Route::delete('/recharges/{recharge}/destroy', [RechargeController::class, 'destroy'])->name('recharges.destroy');
    Route::get('/recharges/{recharge}/show', [RechargeController::class, 'show'])->name('recharges.show');
    Route::get('/recharges/index', [RechargeController::class, 'index'])->name('recharges.index');

    Route::middleware('admin')->group(function () {
        // Catégories
        Route::get('/categories/create', [CategorieController::class, 'create'])->name('categories.create');
        Route::post('/categories/store', [CategorieController::class, 'store'])->name('categories.store');
        Route::get('/categories/{categorie}/edit', [CategorieController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{categorie}/update', [CategorieController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{categorie}/destroy', [CategorieController::class, 'destroy'])->name('categories.destroy');
        Route::get('/categories/{categorie}/show', [CategorieController::class, 'show'])->name('categories.show');
        Route::get('/categories/index', [CategorieController::class, 'index'])->name('categories.index');

        // Sous-catégories
        Route::get('/sous-categories/create', [SousCategorieController::class, 'create'])->name('sous-categories.create');
        Route::post('/sous-categories/store', [SousCategorieController::class, 'store'])->name('sous-categories.store');
        Route::get('/sous-categories/{sousCategorie}/edit', [SousCategorieController::class, 'edit'])->name('sous-categories.edit');
        Route::put('/sous-categories/{sousCategorie}/update', [SousCategorieController::class, 'update'])->name('sous-categories.update');
        Route::delete('/sous-categories/{sousCategorie}/destroy', [SousCategorieController::class, 'destroy'])->name('sous-categories.destroy');
        Route::get('/sous-categories/{sousCategorie}/show', [SousCategorieController::class, 'show'])->name('sous-categories.show');
        Route::get('/sous-categories/index', [SousCategorieController::class, 'index'])->name('sous-categories.index');

        // Produits
        Route::get('/produits/create', [ProduitController::class, 'create'])->name('produits.create');
        Route::post('/produits/store', [ProduitController::class, 'store'])->name('produits.store');
        Route::get('/produits/{produit}/edit', [ProduitController::class, 'edit'])->name('produits.edit');
        Route::put('/produits/{produit}/update', [ProduitController::class, 'update'])->name('produits.update');
        Route::delete('/produits/{produit}/destroy', [ProduitController::class, 'destroy'])->name('produits.destroy');
        Route::get('/produits/show/{produit}', [ProduitController::class, 'show'])->name('produits.show');
        Route::get('/produits/index', [ProduitController::class, 'index'])->name('produits.index');

        Route::resource('users', UserController::class)->names('users');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // Images
        Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');

        // Dans routes/web.php, groupe admin
        Route::get('/admin/paypal-balance', [PaypalWithdrawalController::class, 'index'])
            ->name('admin.paypal-balance.index');
        Route::post('/admin/paypal-balance/withdrawal', [PaypalWithdrawalController::class, 'store'])
            ->name('admin.paypal-balance.store');
        // Route::delete('/admin/paypal-balance/withdrawal/{id}', [PaypalWithdrawalController::class, 'destroy'])
        //     ->name('admin.paypal-balance.destroy');
    });

    Route::prefix('mes-commandes')->name('user.orders.')->group(function () {
        Route::get('/{code}', [DashboardController::class, 'show'])->name('show');
        Route::put('/{code}/received', [DashboardController::class, 'markAsReceived'])->name('received');
    });

    // Route::prefix('users')->name('users.')->group(function () {
    //     Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
    //     Route::get('/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('edit');
    //     Route::put('/{user}/update', [App\Http\Controllers\UserController::class, 'update'])->name('update');
    //     Route::delete('/{user}/destroy', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
    // });




    // Paiement frais de livraison
    Route::get('/pay-shipping/{code}', [ShippingPaymentController::class, 'showPayment'])->name('shipping.payment.show');
    Route::post('/shipping/paypal/create', [ShippingPaymentController::class, 'createPayPalOrder'])->name('shipping.paypal.create');
    Route::post('/shipping/paypal/capture', [ShippingPaymentController::class, 'capturePayPalOrder'])->name('shipping.paypal.capture');
    Route::get('/shipping-payment/success', [ShippingPaymentController::class, 'success'])->name('shipping.payment.success');
    Route::get('/shipping-payment/cancel/{id}', [ShippingPaymentController::class, 'cancel'])->name('shipping.payment.cancel');




});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Commandes
    Route::prefix('commandes')->name('commandes.')->group(function () {
        Route::get('/', [CommandeController::class, 'index'])->name('index');
        Route::get('/stats', [CommandeController::class, 'stats'])->name('stats');
        Route::get('/export', [CommandeController::class, 'export'])->name('export');
        Route::get('/{commande:code}', [CommandeController::class, 'show'])->name('show');
        Route::put('/{id}/status', [CommandeController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{id}/shipping-fees', [CommandeController::class, 'saveShippingFees'])->name('saveShippingFees');
    });
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Gestion des devises
    Route::prefix('currencies')->name('currencies.')->group(function () {
        Route::get('/', [CurrencyController::class, 'index'])->name('index');
        Route::get('/create', [CurrencyController::class, 'create'])->name('create');
        Route::post('/', [CurrencyController::class, 'store'])->name('store');
        Route::get('/{currency}', [CurrencyController::class, 'show'])->name('show');
        Route::get('/{currency}/edit', [CurrencyController::class, 'edit'])->name('edit');
        Route::put('/{currency}', [CurrencyController::class, 'update'])->name('update');
        Route::delete('/{currency}', [CurrencyController::class, 'destroy'])->name('destroy');

        // Actions supplémentaires
        Route::post('/{currency}/toggle-active', [CurrencyController::class, 'toggleActive'])->name('toggleActive');
        Route::post('/{currency}/set-default', [CurrencyController::class, 'setAsDefault'])->name('setDefault');
        Route::post('/update-positions', [CurrencyController::class, 'updatePositions'])->name('updatePositions');
        Route::get('/update-rates/form', [CurrencyController::class, 'showUpdateRatesForm'])->name('updateRatesForm');
        Route::post('/update-rates/manual', [CurrencyController::class, 'updateRatesManual'])->name('updateRatesManual');
        Route::post('/update-rates/auto', [CurrencyController::class, 'updateRates'])->name('updateRates');
    });
});

// Routes admin pour Nihao Travel
Route::prefix('admin/nihao-travel')->name('admin.nihao-travel.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [TravelController::class, 'index'])->name('index');
    Route::get('/export', [TravelController::class, 'export'])->name('export');
    Route::get('/{id}', [TravelController::class, 'show'])->name('show');
    Route::post('/{id}/update-status', [TravelController::class, 'updateStatus'])->name('update-status');
    Route::post('/{id}/update-payment-status', [TravelController::class, 'updatePaymentStatus'])->name('update-payment-status');
    Route::post('/{id}/add-note', [TravelController::class, 'addNote'])->name('add-note');
    Route::delete('/{id}', [TravelController::class, 'destroy'])->name('destroy');
});


Route::get('/api/format-currency/{amount}', [SumbaawaController::class, 'formatAmount'])->name('api.format.currency');
Route::get('/api/current-currency', [SumbaawaController::class, 'getCurrentCurrency'])->name('api.current.currency');
Route::post('/currency/switch', [SumbaawaController::class, 'switch'])->name('currency.switch');
Route::get('/currency/available', [SumbaawaController::class, 'available'])->name('currency.available');
Route::get('/import-alibaba', [AlibabaImportController::class, 'showForm'])->name('alibaba.form');
Route::post('/import-alibaba', [AlibabaImportController::class, 'import'])->name('alibaba.import');
Route::post('/import-alibaba/save', [AlibabaImportController::class, 'save'])->name('alibaba.save');

require __DIR__.'/auth.php';
