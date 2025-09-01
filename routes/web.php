<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SumbaawaController;
use App\Http\Controllers\RechargeController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SousCategorieController;
use App\Http\Controllers\ImageController;
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

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Recharges
    Route::get('/recharges/create', [RechargeController::class, 'create'])->name('recharges.create');
    Route::post('/recharges/store', [RechargeController::class, 'store'])->name('recharges.store');
    Route::get('/recharges/{recharge}/edit', [RechargeController::class, 'edit'])->name('recharges.edit');
    Route::put('/recharges/{recharge}/update', [RechargeController::class, 'update'])->name('recharges.update');
    Route::delete('/recharges/{recharge}/destroy', [RechargeController::class, 'destroy'])->name('recharges.destroy');
    Route::get('/recharges/{recharge}/show', [RechargeController::class, 'show'])->name('recharges.show');
    Route::get('/recharges/index', [RechargeController::class, 'index'])->name('recharges.index');

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
    Route::put('/sous-categories/{sousCategorie}/update', [SousCategorieController::class, 'update'])->name('sousCategories.update');
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

    // Images
    Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
});

require __DIR__.'/auth.php';
