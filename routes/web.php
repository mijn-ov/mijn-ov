<?php

use App\Http\Controllers\AgendaController;
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
Route::get('/chat/{id}', [ChatController::class, 'loadHistory'])->name('chat.history');


Route::get('/favorieten', [FavoriteController::class, 'viewFavorites'])->name('favorites');
Route::post('/saveFavoriet', [FavoriteController::class, 'store'])->name('favorite.store');

Route::post('/submit-message', [ChatController::class, 'submitMessage'])->name('chat.submit');


Route::post('/uitstoot', [EmissionsController::class, 'viewEmissions'])->name('chat.emissions');

Route::get('/uitstoot/{id}', [ChatController::class, 'viewEmissions'])->name('chat.emissions');

Route::get('/map/{id}', [ChatController::class, 'viewMap'])->name('chat.map');

Route::get('/verklaring/{id}', [ChatController::class, 'viewExplanation'])->name('chat.view-explanation');

Route::get('/agenda/toevoegen/{id}', [ChatController::class, 'viewAgenda'])->name('chat.agenda');
Route::post('/agenda/toevoegen', [ChatController::class, 'addToAgenda'])->name('chat.agenda.add');

Route::get('/agenda', [AgendaController::class, 'redirect'])->name('agenda');
Route::get('/agenda/{day}', [AgendaController::class, 'view'])->name('agenda.view');

Route::get('/agenda/maak/nieuw', [AgendaController::class, 'create'])->name('agenda.create');
Route::post('/agenda/maak/nieuw', [AgendaController::class, 'createSave'])->name('agenda.create.save');

Route::get('/agenda/bewerk/{id}', [AgendaController::class, 'edit'])->name('agenda.edit');
Route::post('/agenda/bewerk/{id}', [AgendaController::class, 'save'])->name('agenda.edit.save');
Route::get('/agenda/delete/{id}', [AgendaController::class, 'delete'])->name('agenda.delete');

Route::post('/agenda/edit/time', [AgendaController::class, 'editTime'])->name('agenda.edit.time');



Route::get('/persoonlijkeUitstoot', [PersonalEmissions::class, 'personalEmissions'])->name('personal-emissions');
Route::post('/uitstootOpslaan', [PersonalEmissions::class, 'updateEmissions'])->name('update.personal-emissions');
Route::post('/berichten', [ChatController::class, 'store']);
Route::post('/berichten-create', [ChatController::class, 'create']);
Route::post('/berichten-update/{id}', [ChatController::class, 'update']);

Route::middleware('auth')->group(function () {
    Route::get('/geschiedenis', [ProfileController::class, 'history'])->name('profile.history');

    Route::get('/profiel', [ProfileController::class, 'view'])->name('profile');
    Route::get('/profiel/informatie', [ProfileController::class, 'updateView'])->name('profile.update.view');
    Route::get('/profiel/wachtwoord', [ProfileController::class, 'updatePassword'])->name('profile.password.view');
    Route::get('/profiel/verwijderen', [ProfileController::class, 'deleteView'])->name('profile.delete.view');
    Route::patch('/profiel/informatie', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profiel', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
