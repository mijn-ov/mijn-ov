<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoritesController;
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

Route::get('/', [ChatController::class, 'viewChat'])->name('chat');

Route::get('/favorieten', [FavoritesController::class, 'viewFavorites'])->name('favorites');
Route::post('/saveFavoriet', [FavoritesController::class, 'store'])->name('favorite.store');
Route::post('/submit-message', [ChatController::class, 'submitMessage'])->name('chat.submit');

Route::get('/uitstoot', [ChatController::class, 'viewEmissions'])->name('chat.emissions');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
