<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
use App\Http\Controllers\VideoUploadController;
use App\Models\Media;
use Filament\Facades\Filament;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [BaseController::class, 'dashbord'])->middleware(['auth', 'verified']);

Route::get('/dashboard', [BaseController::class, 'dashbord'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'panel.access'])->group(function () {
    /**
     * Symlink storage : réservé au panel. Préférer `php artisan storage:link`.
     */
    Route::get('/symlink', function () {
        return view('symlink');
    })->name('generate_symlink');

    Route::post('/upload-video', function (Request $request) {
        $validated = $request->validate([
            'video' => ['required', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/webm', 'max:512000'],
        ]);

        $path = $validated['video']->store('videos', 'public');

        return response()->json(['path' => $path]);
    })->middleware('throttle:10,1')->name('video.upload');

    Route::post('/upload-video-chunk', [VideoUploadController::class, 'uploadChunk'])
        ->middleware('throttle:120,1')
        ->name('video.chunk.upload');
    Route::post('/finalize-video-upload', [VideoUploadController::class, 'finalizeUpload'])
        ->middleware('throttle:20,1')
        ->name('video.chunk.finalize');
    Route::get('/upload/progress', [VideoUploadController::class, 'progress'])
        ->middleware('throttle:60,1')
        ->name('video.chunk.progress');

    Route::get('/resolve-media-video-url', function (Request $request) {
        $mediaUrl = $request->query('media_url');
        $source = $request->query('source');
        $resolved = resolve_media_video($mediaUrl, $source);

        return response()->json(['url' => $resolved['url'], 'type' => $resolved['type']]);
    })->name('video.resolve-url');

    /**
     * Supprime une vidéo : préfixe videos/ uniquement + enregistrement Media lié.
     */
    Route::post('/delete-uploaded-video', function (Request $request) {
        $mediaUrl = $request->input('media_url');
        if (! $mediaUrl || ! is_string($mediaUrl)) {
            return response()->json(['deleted' => false, 'message' => 'media_url manquant'], 422);
        }
        $mediaUrl = trim($mediaUrl);

        $s3Key = null;
        if (str_starts_with($mediaUrl, 'http://') || str_starts_with($mediaUrl, 'https://')) {
            $path = parse_url($mediaUrl, PHP_URL_PATH);
            $s3Key = $path ? ltrim($path, '/') : null;
        } else {
            $s3Key = $mediaUrl;
        }

        if ($s3Key && (str_contains($s3Key, '..') || ! str_starts_with($s3Key, 'videos/'))) {
            return response()->json(['deleted' => false, 'message' => 'Clé S3 non autorisée'], 403);
        }

        $media = Media::where('media_url', $mediaUrl)->first();
        $user = $request->user();
        if ($media && $media->user_id && (int) $media->user_id !== (int) $user->id) {
            $panel = Filament::getPanel('admin');
            if (! $user->canAccessPanel($panel)) {
                return response()->json(['deleted' => false, 'message' => 'Non autorisé'], 403);
            }
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
    })->middleware('throttle:30,1')->name('video.chunk.delete');
});

Route::middleware(['auth', 'panel.access'])->group(function () {
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
    // Alias corrects + anciens noms (typos) pour ne pas casser les vues legacy
    Route::get('creatGroup', [GroupController::class, 'create'])->name('creatGroup');
    Route::get('createGroup', [GroupController::class, 'create'])->name('createGroup');
    Route::get('creatPays', [CountryController::class, 'create'])->name('creatPays');
    Route::get('createPays', [CountryController::class, 'create'])->name('createPays');
    Route::get('creatRole', [RoleController::class, 'create'])->name('creatRole');
    Route::get('createRole', [RoleController::class, 'create'])->name('createRole');

    Route::get('editeMedia/{id}', [MediaController::class, 'show'])->name('editeMedia');
    Route::get('editMedia/{id}', [MediaController::class, 'show'])->name('editMedia');
    Route::get('editType/{id}', [TypeController::class, 'show_type'])->name('editType');
    Route::get('editGroupe/{id}', [GroupController::class, 'show_Groupe'])->name('editGroupe');
    Route::get('editPays/{id}', [CountryController::class, 'show'])->name('editPays');
    Route::get('editRole/{id}', [RoleController::class, 'show'])->name('editRole');
    Route::get('editCat/{id}', [MediaController::class, 'show_cat'])->name('editCat');
    Route::get('editeUser/{id}', [UserController::class, 'show_Agent'])->name('editeUser');
    Route::get('editUser/{id}', [UserController::class, 'show_Agent'])->name('editUser');

    Route::delete('deleteMedia/{id}', [MediaController::class, 'destroy'])->name('deleteMedia');
    Route::delete('deleteCategorie/{id}', [MediaController::class, 'destroyCat'])->name('deleteCategorie');
    Route::delete('deleteType/{id}', [TypeController::class, 'destroyType'])->name('deleteType');
    Route::delete('deleteGroupe/{id}', [GroupController::class, 'destroy'])->name('deleteGroupe');
    Route::delete('deletePays/{id}', [CountryController::class, 'destroy'])->name('deletePays');
    Route::delete('deleteRole/{id}', [RoleController::class, 'destroy'])->name('deleteRole');
    Route::delete('deleteUser/{id}', [UserController::class, 'destroyAgent'])->name('deleteUser');

    Route::post('registerMedia', [MediaController::class, 'store'])->name('registerMedia');
    Route::post('addCat', [MediaController::class, 'store_cat'])->name('addCat');
    Route::post('addType', [TypeController::class, 'store_type'])->name('addType');
    Route::post('addGroupe', [GroupController::class, 'store'])->name('addGroupe');
    Route::post('addPays', [CountryController::class, 'store'])->name('addPays');
    Route::post('addRole', [RoleController::class, 'store'])->name('addRole');
    Route::post('creatUsers', [UserController::class, 'createAgent'])->name('creatUsers');
    Route::post('createUsers', [UserController::class, 'createAgent'])->name('createUsers');

    Route::post('updateMedia', [MediaController::class, 'update'])->name('updateMedia');
    Route::post('updateCat', [TypeController::class, 'update_categorie'])->name('updateCat');
    Route::post('updateType', [TypeController::class, 'update'])->name('updateType');
    Route::post('updateGroupe', [GroupController::class, 'update'])->name('updateGroupe');
    Route::post('updatePays', [CountryController::class, 'update'])->name('updatePays');
    Route::post('updateRole', [RoleController::class, 'update'])->name('updateRole');
    Route::post('updateUser', [UserController::class, 'updateAgent'])->name('updateUser');
});

require __DIR__ . '/auth.php';
