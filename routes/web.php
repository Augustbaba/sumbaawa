<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SumbaawaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('front.pages.index');
})->name('index');

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
Route::get('/checkout', [SumbaawaController::class, 'checkout'])->name('checkout')->middleware(['auth']);
Route::post('/checkout/process', [SumbaawaController::class, 'checkoutProcess'])->name('checkout.process');
Route::get('/wishlist/add/{produit:slug}', [SumbaawaController::class, 'addWishlist'])->middleware(['auth'])->name('wishlist.add');
Route::get('/mes-favoris', [SumbaawaController::class, 'myWishlist'])->middleware(['auth'])->name('wishlist.my');
Route::post('/store-delivery-info', [SumbaawaController::class, 'storeDeliveryInfo'])->name('store_delivery_info');
Route::post('/clear-delivery-info', [SumbaawaController::class, 'clearDeliveryInfo'])->name('clear_delivery_info');
Route::get('/search', [SumbaawaController::class, 'search'])->name('search');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
