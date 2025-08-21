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

Route::get('/categories/{categorie:slug}/details', [SumbaawaController::class, 'categoriesSingle'])->name('categories.single');
Route::get('/categories-page', [SumbaawaController::class, 'categoriesPage'])->name('categories.page');
Route::get('/produits/details/{produit:slug}', [SumbaawaController::class, 'produitDetail'])->name('produits.single');
Route::get('/sous-categories/{sousCategorie:slug}/produits', [SumbaawaController::class, 'sousCategoriesDetail'])->name('sousCategories.single');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

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
Route::middleware(['auth'])->group(function () {
    Route::get('/subcategories/create', [SousCategorieController::class, 'create'])->name('subcategories.create');
    Route::post('/subcategories', [SousCategorieController::class, 'store'])->name('subcategories.store');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/products/create', [ProduitController::class, 'create'])->name('products.create');
    Route::post('/products', [ProduitController::class, 'store'])->name('products.store');
});


   // Affichage d'une images secondaires
Route::middleware(['auth'])->group(function () {
    Route::resource('produits', ProduitController::class);
    Route::delete('images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
});
require __DIR__.'/auth.php';
