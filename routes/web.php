<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\EmissionsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\http\Controllers\PersonalEmissions;
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

Route::get('/favorieten', [FavoriteController::class, 'viewFavorites'])->name('favorites');
Route::post('/saveFavoriet', [FavoriteController::class, 'store'])->name('favorite.store');
Route::post('/submit-message', [ChatController::class, 'submitMessage'])->name('chat.submit');

Route::post('/uitstoot', [EmissionsController::class, 'viewEmissions'])->name('chat.emissions');

Route::get('/persoonlijkeUitstoot', [PersonalEmissions::class, 'personalEmissions'])->name('personal-emissions');
Route::post('/uitstootOpslaan', [PersonalEmissions::class, 'updateEmissions'])->name('update.personal-emissions');
Route::post('/berichten', [ChatController::class, 'store']);

Route::middleware('auth')->group(function () {
    Route::get('/profiel', [ProfileController::class, 'view'])->name('profile');
    Route::get('/profiel/informatie', [ProfileController::class, 'updateView'])->name('profile.update.view');
    Route::get('/profiel/wachtwoord', [ProfileController::class, 'updatePassword'])->name('profile.password.view');
    Route::get('/profiel/verwijderen', [ProfileController::class, 'deleteView'])->name('profile.delete.view');
    Route::patch('/profiel/informatie', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profiel', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
