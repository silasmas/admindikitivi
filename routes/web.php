<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Filament\Resources\AwsResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\Test;
use App\Models\Media;

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

Route::get('/', [BaseController::class, 'dashbord'])->middleware(['auth', 'verified']);

Route::get('/dashboard', [BaseController::class, 'dashbord'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/symlink', function () {
    return view('symlink');
})->name('generate_symlink');
Route::post('/upload-video', function (Request $request) {
    if ($request->hasFile('video')) {
        $file = $request->file('video');
        $path = $file->store('videos', 'public'); // ou sur S3 selon ta config
        return response()->json(['path' => $path]);
    }
    return response()->json(['error' => 'Fichier manquant'], 400);
});


Route::middleware('auth')->group(function () {
    Route::post('/upload-video-chunk', [Test::class, 'uploadChunk'])->name('video.chunk.upload');
    Route::post('/finalize-video-upload', [Test::class, 'finalizeUpload'])->name('video.chunk.finalize');
    Route::get('/upload/progress', [Test::class, 'progress'])->name('video.chunk.progress');
});

Route::middleware('auth')->post('/delete-uploaded-video', function (Request $request) {
    $mediaUrl = $request->input('media_url');
    if (! $mediaUrl || ! is_string($mediaUrl)) {
        return response()->json(['deleted' => false, 'message' => 'media_url manquant'], 422);
    }
    $mediaUrl = trim($mediaUrl);

    // Dériver la clé S3 : soit URL complète (path après le host), soit chemin relatif déjà stocké
    $s3Key = null;
    if (str_starts_with($mediaUrl, 'http://') || str_starts_with($mediaUrl, 'https://')) {
        $path = parse_url($mediaUrl, PHP_URL_PATH);
        // Clé S3 = path sans le slash initial (ex. /videos/xxx.mp4 → videos/xxx.mp4)
        $s3Key = $path ? ltrim($path, '/') : null;
    } else {
        $s3Key = $mediaUrl;
    }

    $deletedFromS3 = false;
    if ($s3Key && Storage::disk('s3')->exists($s3Key)) {
        Storage::disk('s3')->delete($s3Key);
        $deletedFromS3 = true;
    }

    $updated = Media::where('media_url', $mediaUrl)->update(['media_url' => null]);

    return response()->json([
        'deleted' => true,
        'deleted_from_s3' => $deletedFromS3,
        'updated_in_db' => $updated > 0,
    ]);
})->name('video.chunk.delete');

Route::middleware('auth')->group(function () {
    Route::get('media', [BaseController::class, 'index'])->name('media');
    Route::get('film', [BaseController::class, 'index'])->name('film');
    Route::get('client', [BaseController::class, 'client'])->name('client');
    Route::get('categories', [BaseController::class, 'categories'])->name('categories');
    Route::get('types', [BaseController::class, 'types'])->name('types');
    Route::get('groupes', [GroupController::class, 'index'])->name('groupes');
    Route::get('pays', [CountryController::class, 'index'])->name('pays');
    Route::get('roles', [RoleController::class, 'index'])->name('roles');
    Route::get('gifted', [DonationController::class, 'index'])->name('gifted');
    Route::get('users', [UserController::class, 'indexAgent'])->name('users');

    Route::get('createMedia', [MediaController::class, 'create'])->name('createMedia');
    Route::get('creatGroup', [GroupController::class, 'create'])->name('creatGroup');
    Route::get('creatPays', [CountryController::class, 'create'])->name('creatPays');
    Route::get('creatRole', [RoleController::class, 'create'])->name('creatRole');

    Route::get('editeMedia/{id}', [MediaController::class, 'show'])->name('editeMedia');
    Route::get('editType/{id}', [TypeController::class, 'show_type'])->name('editType');
    Route::get('editGroupe/{id}', [GroupController::class, 'show_Groupe'])->name('editGroupe');
    Route::get('editPays/{id}', [CountryController::class, 'show'])->name('editPays');
    Route::get('editRole/{id}', [RoleController::class, 'show'])->name('editRole');
    Route::get('editCat/{id}', [MediaController::class, 'show_cat'])->name('editCat');
    Route::get('editeUser/{id}', [UserController::class, 'show_Agent'])->name('editeUser');

    Route::get('deleteMedia/{id}', [MediaController::class, 'destroy'])->name('deleteMedia');
    Route::get('deleteCategorie/{id}', [MediaController::class, 'destroyCat'])->name('deleteCategorie');
    Route::get('deleteType/{id}', [TypeController::class, 'destroyType'])->name('deleteType');
    Route::get('deleteGroupe/{id}', [GroupController::class, 'destroy'])->name('deleteGroupe');
    Route::get('deletePays/{id}', [CountryController::class, 'destroy'])->name('deletePays');
    Route::get('deleteRole/{id}', [RoleController::class, 'destroy'])->name('deleteRole');
    Route::get('deleteUser/{id}', [UserController::class, 'destroyAgent'])->name('deleteUser');

    Route::post('registerMedia', [MediaController::class, 'store'])->name('registerMedia');
    Route::post('addCat', [MediaController::class, 'store_cat'])->name('addCat');
    Route::post('addType', [TypeController::class, 'store_type'])->name('addType');
    Route::post('addGroupe', [GroupController::class, 'store'])->name('addGroupe');
    Route::post('addPays', [CountryController::class, 'store'])->name('addPays');
    Route::post('addRole', [RoleController::class, 'store'])->name('addRole');
    Route::post('creatUsers', [UserController::class, 'createAgent'])->name('creatUsers');

    Route::post('updateMedia', [MediaController::class, 'update'])->name('updateMedia');
    Route::post('updateCat', [TypeController::class, 'update_categorie'])->name('updateCat');
    Route::post('updateType', [TypeController::class, 'update'])->name('updateType');
    Route::post('updateGroupe', [GroupController::class, 'update'])->name('updateGroupe');
    Route::post('updatePays', [CountryController::class, 'update'])->name('updatePays');
    Route::post('updateRole', [RoleController::class, 'update'])->name('updateRole');
    Route::post('updateUser', [UserController::class, 'updateAgent'])->name('updateUser');
});

require __DIR__ . '/auth.php';
