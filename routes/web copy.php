<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SumbaawaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RechargeController;
use App\Http\Controllers\CategorieController;

use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SousCategorieController;
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



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Liste des recharges (GET)
    //Route::get('/recharges', [RechargeController::class, 'index'])->name('recharges.index');
    
    // Affichage d'une recharge spécifique (GET)
    //Route::get('/recharges/{recharge}', [RechargeController::class, 'show'])->name('recharges.show');
    

     Route::get('/recharges/create', [RechargeController::class, 'create'])->name('recharges.create');
   //  Route::post('/recharges', [RechargeController::class, 'store'])->name('recharges.store');
    //Route::get('/recharges/{recharge}/edit', [RechargeController::class, 'edit'])->name('recharges.edit');
    //Route::put('/recharges/{recharge}', [RechargeController::class, 'update'])->name('recharges.update');
    //Route::delete('/recharges/{recharge}', [RechargeController::class, 'destroy'])->name('recharges.destroy');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/categories/create', [CategorieController::class, 'create'])->name('categories.create');
    Route::get('/categories/index', [CategorieController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategorieController::class, 'store'])->name('categories.store');
});
Route::resource('categories', \App\Http\Controllers\CategorieController::class);
Route::middleware(['auth'])->group(function () {
    Route::get('/subcategories/create', [SousCategorieController::class, 'create'])->name('subcategories.create');
    Route::post('/subcategories', [SousCategorieController::class, 'store'])->name('subcategories.store');
});






Route::resource('sous-categories', \App\Http\Controllers\SousCategorieController::class);
Route::resource('categories', \App\Http\Controllers\CategorieController::class);
Route::middleware(['auth'])->group(function () {
    Route::get('/products/create', [ProduitController::class, 'create'])->name('products.create');
    Route::post('/products', [ProduitController::class, 'store'])->name('products.store');
Route::resource('produits', \App\Http\Controllers\ProduitController::class);
Route::resource('sous-categories', \App\Http\Controllers\SousCategorieController::class);
Route::resource('categories', \App\Http\Controllers\CategorieController::class);
Route::delete('images/{image}', [App\Http\Controllers\ImageController::class, 'destroy'])->name('images.destroy');




});


   // Affichage d'une images secondaires
Route::middleware(['auth'])->group(function () {
    
    Route::delete('images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
});
require __DIR__.'/auth.php';
