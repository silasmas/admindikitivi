<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProfileController;

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
    Route::get('categories', [BaseController::class, 'categories'])->name('categories');
    Route::get('types', [BaseController::class, 'types'])->name('types');
    Route::get('groupes', [GroupController::class, 'index'])->name('groupes');
    Route::get('pays', [CountryController::class, 'index'])->name('pays');
    Route::get('roles', [RoleController::class, 'index'])->name('roles');

    Route::get('createMedia', [MediaController::class, 'create'])->name('createMedia');
    Route::get('creatGroup', [GroupController::class, 'create'])->name('creatGroup');
    Route::get('creatPays', [CountryController::class, 'create'])->name('creatPays');
    Route::get('creatRole', [RoleController::class, 'create'])->name('creatRole');

    Route::get('editeMedia/{id}', [MediaController::class, 'show'])->name('editeMedia');
    Route::get('editType/{id}', [TypeController::class, 'show_type'])->name('editType');
    Route::get('editGroupe/{id}', [GroupController::class, 'show_Groupe'])->name('editGroupe');
    Route::get('editPays/{id}', [CountryController::class, 'show'])->name('editPays');
    Route::get('editRole/{id}', [RoleController::class, 'show'])->name('editRole');
    Route::get('editCat/{id}', [MediaController::class, 'show_cat'])->name('editRole');

    Route::get('deleteMedia/{id}', [MediaController::class, 'destroy'])->name('deleteMedia');
    Route::get('deleteCategorie/{id}', [MediaController::class, 'destroyCat'])->name('deleteCategorie');
    Route::get('deleteType/{id}', [TypeController::class, 'destroyType'])->name('deleteType');
    Route::get('deleteGroupe/{id}', [GroupController::class, 'destroy'])->name('deleteGroupe');
    Route::get('deletePays/{id}', [CountryController::class, 'destroy'])->name('deletePays');
    Route::get('deleteRole/{id}', [RoleController::class, 'destroy'])->name('deleteRole');


    Route::post('registerMedia', [MediaController::class, 'store'])->name('registerMedia');
    Route::post('addCat', [MediaController::class, 'store_cat'])->name('addCat');
    Route::post('addType', [TypeController::class, 'store_type'])->name('addType');
    Route::post('addGroupe', [GroupController::class, 'store'])->name('addGroupe');
    Route::post('addPays', [CountryController::class, 'store'])->name('addPays');
    Route::post('addRole', [RoleController::class, 'store'])->name('addRole');
    
    Route::post('updateMedia', [MediaController::class, 'update'])->name('updateMedia');
    Route::post('updateCat', [TypeController::class, 'update_categorie'])->name('updateCat');
    Route::post('updateType', [TypeController::class, 'update'])->name('updateType');
    Route::post('updateGroupe', [GroupController::class, 'update'])->name('updateGroupe');
    Route::post('updatePays', [CountryController::class, 'update'])->name('updatePays');
    Route::post('updateRole', [RoleController::class, 'update'])->name('updateRole');

});

require __DIR__ . '/auth.php';
