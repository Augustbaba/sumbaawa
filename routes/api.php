<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CategoryProductsApiController;
use App\Http\Controllers\Api\CurrencyApiController;
use App\Http\Controllers\Api\DeliveryRefApiController;
use App\Http\Controllers\Api\ElongoPayMobileApiController;
use App\Http\Controllers\Api\OrderMobileApiController;
use App\Http\Controllers\Api\PayPalMobileApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\ShippingPaymentMobileApiController;
use App\Http\Controllers\Api\WalletMobileApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {

    // ── Routes publiques (pas de token requis) ─────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthApiController::class, 'register']);
        Route::post('login',    [AuthApiController::class, 'login']);
    });

    Route::get('currencies', [CurrencyApiController::class, 'index']);
    Route::get('/products/recent',  [ProductApiController::class, 'recent']);   // fourProducts()
    Route::get('/products/popular', [ProductApiController::class, 'popular']);  // fourProductsPopulars()
    Route::get('/categories', [CategoryApiController::class, 'index']);
    Route::get('/categories/slug/{slug}',  [CategoryApiController::class, 'showBySlug']);
    Route::get('/categories/{id}/products', [CategoryProductsApiController::class, 'index']);

    // ── Routes protégées (token Sanctum requis) ────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/delivery/refs', [DeliveryRefApiController::class, 'index']);
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthApiController::class, 'logout']);
            Route::get('me',      [AuthApiController::class, 'me']);
        });

        Route::patch('user/currency', [CurrencyApiController::class, 'updatePreferred']);

        Route::get('/orders', [OrderMobileApiController::class, 'index']);
        Route::get('/orders/{code}', [OrderMobileApiController::class, 'show']);
        Route::put('/orders/{code}/received', [OrderMobileApiController::class, 'markAsReceived']);
        Route::post('/shipping/paypal/create-order', [ShippingPaymentMobileApiController::class, 'createOrder']);
        Route::post('/shipping/paypal/capture-order', [ShippingPaymentMobileApiController::class, 'captureOrder']);

        Route::post('/paypal/create-order',  [PayPalMobileApiController::class, 'createOrder']);
        Route::post('/paypal/capture-order', [PayPalMobileApiController::class, 'captureOrder']);

        Route::post('/elongopay/capture', [ElongoPayMobileApiController::class, 'capture']);
        Route::post('/shipping/elongopay/capture', [ShippingPaymentMobileApiController::class, 'captureElongoPay']);

        Route::prefix('wallet')->group(function () {
            Route::get('balance',                    [WalletMobileApiController::class, 'balance']);
            Route::get('transactions',               [WalletMobileApiController::class, 'transactions']);
            Route::post('paypal/create-order',       [WalletMobileApiController::class, 'createPayPalOrder']);
            Route::post('paypal/capture-order',      [WalletMobileApiController::class, 'capturePayPalOrder']);
        });

        // Ajoutez ici vos futures routes protégées (produits, commandes, etc.)
        // Route::apiResource('products', ProductApiController::class);
    });

});
