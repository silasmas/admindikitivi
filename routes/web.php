<?php

use App\Http\Controllers\BaseController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
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
    return view('welcome');
})->middleware(['auth', 'verified']);

Route::get('/dashboard', function () {
    return view('pages.home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('media', [BaseController::class, 'index'])->name('media');
    Route::get('film', [BaseController::class, 'index'])->name('film');
    Route::get('client', [BaseController::class, 'client'])->name('client');
    Route::get('serie', [BaseController::class, 'serie'])->name('serie');

    Route::get('createMedia', [MediaController::class, 'create'])->name('createMedia');
    Route::get('editeMedia/{id}', [MediaController::class, 'show'])->name('editeMedia');
    Route::get('deleteMedia/{id}', [MediaController::class, 'destroy'])->name('deleteMedia');
    Route::post('registerMedia', [MediaController::class, 'store'])->name('registerMedia');
    Route::post('updateMedia', [MediaController::class, 'update'])->name('updateMedia');

});

require __DIR__ . '/auth.php';
