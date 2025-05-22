<?php
namespace App\Http\Controllers;

use App\Models\aws;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class Test extends BaseController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'media_title' => 'required|string|max:255',
                                                         // autres validations...
            'media_url'   => 'nullable|string|max:2048', // chemin de la vidéo uploadée
        ]);

        aws::create([
            // 'nom' => $validated['media_title'],
            // 'image' => $request->file('image')->store('aws_cover', 's3'),
            'video' => $request->file('video')->store('aws_video', 's3'),
            // 'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Vidéo enregistrée avec succès ✅');
    }
    public function uploadChunk(Request $request)
    {
        $chunk    = $request->file('chunk');
        $index    = $request->input('index');
        $uploadId = $request->input('uploadId');

        $tempPath = storage_path("app/chunks/{$uploadId}");
        if (! is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        $chunk->move($tempPath, "chunk_{$index}");

        return response()->json(['success' => true]);
    }

    public function finalizeUploadold(Request $request)
    {
        $uploadId         = $request->input('uploadId');
        $originalFilename = $request->input('filename');
        $safeFilename     = str_replace(' ', '-', $originalFilename);
        $total            = (int) $request->input('total');

        $tempPath       = storage_path("app/chunks/{$uploadId}");
        $finalFilename  = $uploadId;
        $destinationDir = storage_path("app/tmp");

        if (! is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $finalPath = $destinationDir . '/' . $finalFilename;

        if (! $this->allChunksUploaded($tempPath, $total)) {
            return response()->json(['error' => 'Chunks manquants'], 400);
        }

        // Fusion des chunks
        $output = fopen($finalPath, 'ab');
        for ($i = 0; $i < $total; $i++) {
            $chunkPath = "{$tempPath}/chunk_{$i}";
            $in        = fopen($chunkPath, 'rb');
            stream_copy_to_stream($in, $output);
            fclose($in);
            unlink($chunkPath);

            /// ✅ Mise à jour du cache
            cache()->put("video_progress_{$uploadId}", (($i + 1) / $total) * 100, now()->addMinutes(5));
        }
        fclose($output);
        rmdir($tempPath);

        // Upload sur S3
        $s3VideoPath = 'videos/' . $finalFilename;

        Storage::disk('s3')->put($s3VideoPath, file_get_contents($finalPath), [
            'visibility'  => 'public',
            'ContentType' => 'video/mp4',
        ]);

        // Nettoyage local
        unlink($finalPath);

        return response()->json([
            'path' => Storage::disk('s3')->url($s3VideoPath),
        ]);
    }
    public function finalizeUploadold2(Request $request)
    {
        $uploadId         = $request->input('uploadId');
        $originalFilename = $request->input('filename');
        $safeFilename     = str_replace(' ', '-', $originalFilename);
        $total            = (int) $request->input('total');

        $tempPath       = storage_path("app/chunks/{$uploadId}");
        $finalFilename  = $uploadId . '-' . $safeFilename;
        $destinationDir = storage_path("app/tmp");

        if (! is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $finalPath = $destinationDir . '/' . $finalFilename;

        // 🔄 Vérification des chunks présents
        if (! $this->allChunksUploaded($tempPath, $total)) {
            return response()->json(['error' => 'Chunks manquants'], 400);
        }

        try {
            $output = fopen($finalPath, 'ab');

            for ($i = 0; $i < $total; $i++) {
                $chunkPath = "$tempPath/chunk_{$i}";
                $in        = fopen($chunkPath, 'rb');
                stream_copy_to_stream($in, $output);
                fclose($in);
                unlink($chunkPath);

                // 🌟 Mise à jour progression en cache (ex: Redis)
                Cache::put("video_progress_{$uploadId}", (($i + 1) / $total) * 100, now()->addMinutes(5));
            }

            fclose($output);
            rmdir($tempPath);

            $s3Path = 'videos/' . $finalFilename;
            Storage::disk('s3')->put($s3Path, file_get_contents($finalPath), [
                'visibility'  => 'public',
                'ContentType' => 'video/mp4',
            ]);

            unlink($finalPath);                          // 📉 Supprime local temp
            Cache::forget("video_progress_{$uploadId}"); // Nettoyage progression

            return response()->json([
                'path'   => Storage::disk('s3')->url($s3Path),
                's3_key' => $s3Path,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Exception: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function finalizeUploadold3(Request $request)
    {
        $uploadId         = $request->input('uploadId');
        $originalFilename = $request->input('filename');
        $safeFilename     = str_replace(' ', '-', $originalFilename);
        $total            = (int) $request->input('total');

        $tempPath       = storage_path("app/chunks/{$uploadId}");
        $finalFilename  = $uploadId;
        $destinationDir = storage_path("app/tmp");

        if (! is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $finalPath = $destinationDir . '/' . $finalFilename;

        if (! $this->allChunksUploaded($tempPath, $total)) {
            return response()->json(['error' => 'Chunks manquants'], 400);
        }

        $output = fopen($finalPath, 'ab');

        for ($i = 0; $i < $total; $i++) {
            $chunkPath = "{$tempPath}/chunk_{$i}";
            $in        = fopen($chunkPath, 'rb');
            stream_copy_to_stream($in, $output);
            fclose($in);
            unlink($chunkPath);

            // 💾 Stocker le progrès actuel dans le cache
            $percent = round((($i + 1) / $total) * 100);
            Cache::put("video_progress_{$uploadId}", $percent, now()->addMinutes(10));
        }

        fclose($output);
        rmdir($tempPath);

        // 📤 Envoi sur S3
        $s3Path = 'videos/' . $finalFilename;
        Storage::disk('s3')->put($s3Path, file_get_contents($finalPath), [
            'visibility'  => 'public',
            'ContentType' => 'video/mp4',
        ]);

        unlink($finalPath);

        // 🧹 Nettoyer la progression après envoi
        Cache::forget("video_progress_{$uploadId}");

        return response()->json([
            'path'   => Storage::disk('s3')->url($s3Path),
            's3_key' => $s3Path,
        ]);
    }

    public function finalizeUploadggg(Request $request)
    {
        $uploadId     = $request->input('uploadId');
        $filename     = $request->input('filename');
        $safeFilename = str_replace(' ', '-', $filename);
        $total        = (int) $request->input('total');

        $tempPath        = storage_path("app/chunks/{$uploadId}");
        $finalFilename   = $uploadId . '-' . $safeFilename;
        $destinationPath = storage_path("app/tmp");
        $finalPath       = "{$destinationPath}/{$finalFilename}";

        if (! is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if (! is_dir($tempPath)) {
            return response()->json(['error' => 'Dossier des chunks introuvable.'], 404);
        }

        $output = fopen($finalPath, 'ab');
        for ($i = 0; $i < $total; $i++) {
            $chunkPath = "{$tempPath}/chunk_{$i}";
            if (! file_exists($chunkPath)) {
                continue;
            }

            $in = fopen($chunkPath, 'rb');
            stream_copy_to_stream($in, $output);
            fclose($in);
            unlink($chunkPath);

            // ✅ Mise à jour du cache pour progression réelle
            $progress = (($i + 1) / $total) * 100;
            Cache::put("video_progress_{$uploadId}", $progress, now()->addMinutes(10));
        }

        fclose($output);
        @rmdir($tempPath);

        // 📦 Envoi sur S3
        $s3Path = "videos/{$finalFilename}";
        Storage::disk('s3')->put($s3Path, file_get_contents($finalPath), [
            'visibility'  => 'public',
            'ContentType' => 'video/mp4',
        ]);
        unlink($finalPath);
        // ✅ Nettoyage cache une fois terminé
        Cache::forget("video_progress_{$uploadId}");

        return response()->json([
            'path'   => Storage::disk('s3')->url($s3Path),
            's3_key' => $s3Path,
        ]);
    }
    public function finalizeUpload(Request $request)
{
    $uploadId = $request->input('uploadId');
    $filename = $request->input('filename');
    $total    = (int) $request->input('total');

    if (! $uploadId || ! $filename || ! $total) {
        return response()->json(['error' => 'Paramètres manquants.'], 422);
    }

    // 🔐 Nettoyage et préparation du nom de fichier
    $originalExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $baseName = pathinfo($filename, PATHINFO_FILENAME);
    $safeBaseName = Str::slug($baseName);

    $baseFinalFilename = $uploadId . '-' . $safeBaseName;
    $finalFilename = $baseFinalFilename . '.' . $originalExtension;
    $s3Path = "videos/{$finalFilename}";

    // ✅ Si le fichier existe déjà, on ajoute un suffixe -1, -2, etc.
    $suffix = 1;
    while (Storage::disk('s3')->exists($s3Path)) {
        $finalFilename = $baseFinalFilename . '-' . $suffix . '.' . $originalExtension;
        $s3Path = "videos/{$finalFilename}";
        $suffix++;
    }

    $tempPath = storage_path("app/chunks/{$uploadId}");
    $destinationPath = storage_path("app/tmp");
    $finalPath = "{$destinationPath}/{$finalFilename}";

    if (! is_dir($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    if (! is_dir($tempPath)) {
        return response()->json(['error' => 'Dossier des chunks introuvable.'], 404);
    }

    // 🧩 Fusion des chunks
    $output = fopen($finalPath, 'ab');
    for ($i = 0; $i < $total; $i++) {
        $chunkPath = "{$tempPath}/chunk_{$i}";
        if (! file_exists($chunkPath)) {
            return response()->json(['error' => "Chunk #{$i} manquant."], 400);
        }

        $in = fopen($chunkPath, 'rb');
        stream_copy_to_stream($in, $output);
        fclose($in);
        unlink($chunkPath);

        Cache::put("video_progress_{$uploadId}", (($i + 1) / $total) * 100, now()->addMinutes(10));
    }
    fclose($output);
    @rmdir($tempPath);

    // 📦 Envoi vers S3
    $contentType = match ($originalExtension) {
        'mp4'  => 'video/mp4',
        'mov'  => 'video/quicktime',
        default => 'application/octet-stream',
    };

    try {
        Storage::disk('s3')->put($s3Path, file_get_contents($finalPath), [
            'visibility'  => 'public',
            'ContentType' => $contentType,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de l\'upload S3', 'details' => $e->getMessage()], 500);
    }

    unlink($finalPath);
    Cache::forget("video_progress_{$uploadId}");

    return response()->json([
        'path'   => Storage::disk('s3')->url($s3Path),
        's3_key' => $s3Path,
    ]);
}

    public function progressoldd(Request $request)
    {
        $uploadId = $request->query('uploadId');
        Log::info('🔍 Requête de suivi pour', ['uploadId' => $uploadId]);

        $progress = Cache::get("video_progress_{$uploadId}", 0);
        Log::info('➡️ Progression actuelle', ['progress' => $progress]);

        return response()->json([
            'progress' => $progress,
        ]);
    }
    public function progress(Request $request)
{
    $uploadId = $request->query('uploadId');

    $progress = Cache::get("video_progress_{$uploadId}", 0);
    return response()->json([
        'progress' => round($progress, 2),
    ]);
}


    private function allChunksUploaded(string $dir, int $total): bool
    {
        for ($i = 0; $i < $total; $i++) {
            if (! file_exists("{$dir}/chunk_{$i}")) {
                return false;
            }
        }
        return true;
    }

}
