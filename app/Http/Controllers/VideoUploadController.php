<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Upload vidéo par morceaux (chunks) puis assemblage et envoi S3.
 */
class VideoUploadController extends BaseController
{
    /** Taille max d’un chunk (Ko pour la règle Laravel max). */
    private const MAX_CHUNK_KB = 10240;

    /** Taille totale max assemblée (octets) — 512 Mo. */
    private const MAX_TOTAL_BYTES = 512 * 1024 * 1024;

    /** Extensions vidéo acceptées. */
    private const ALLOWED_EXTENSIONS = ['mp4', 'mov', 'webm', 'avi'];

    /**
     * Reçoit un chunk vidéo et le stocke temporairement.
     *
     * @param  Request  $request  chunk, index, uploadId (+ total optionnel)
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadChunk(Request $request)
    {
        $validated = $request->validate([
            'chunk' => ['required', 'file', 'max:' . self::MAX_CHUNK_KB],
            'index' => ['required', 'integer', 'min:0', 'max:9999'],
            'uploadId' => ['required', 'string', 'max:80', 'regex:/^[A-Za-z0-9._-]+$/'],
            'total' => ['nullable', 'integer', 'min:1', 'max:2000'],
            'filename' => ['nullable', 'string', 'max:255'],
        ]);

        $uploadId = $validated['uploadId'];
        $index = (int) $validated['index'];
        $chunk = $validated['chunk'];

        $bytesKey = "upload_bytes_{$uploadId}";
        $runningTotal = (int) Cache::get($bytesKey, 0) + (int) $chunk->getSize();

        if ($runningTotal > self::MAX_TOTAL_BYTES) {
            return response()->json([
                'success' => false,
                'error' => 'Taille totale d’upload dépassée (max 512 Mo).',
            ], 413);
        }

        Cache::put($bytesKey, $runningTotal, now()->addHours(2));

        $tempPath = storage_path("app/chunks/{$uploadId}");
        if (! is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        $chunk->move($tempPath, "chunk_{$index}");

        return response()->json(['success' => true]);
    }

    /**
     * Assemble les chunks et envoie le fichier final sur S3.
     *
     * @param  Request  $request  uploadId, filename, total
     * @return \Illuminate\Http\JsonResponse
     */
    public function finalizeUpload(Request $request)
    {
        set_time_limit(0);
        ignore_user_abort(true);

        $uploadId = null;
        $filename = null;
        $total = null;
        $stream = null;

        try {
            $validated = $request->validate([
                'uploadId' => ['required', 'string', 'max:80', 'regex:/^[A-Za-z0-9._-]+$/'],
                'filename' => ['required', 'string', 'max:255'],
                'total' => ['required', 'integer', 'min:1', 'max:2000'],
            ]);

            $uploadId = $validated['uploadId'];
            $filename = $validated['filename'];
            $total = (int) $validated['total'];

            $originalExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (! in_array($originalExtension, self::ALLOWED_EXTENSIONS, true)) {
                return response()->json([
                    'error' => 'Extension non autorisée.',
                    'allowed' => self::ALLOWED_EXTENSIONS,
                ], 422);
            }

            $safeBaseName = Str::slug(pathinfo($filename, PATHINFO_FILENAME)) ?: 'video';
            $baseFinalFilename = $uploadId . '-' . $safeBaseName;
            $finalFilename = $baseFinalFilename . '.' . $originalExtension;
            $s3Path = "videos/{$finalFilename}";

            $suffix = 1;
            while (Storage::disk('s3')->exists($s3Path)) {
                $finalFilename = $baseFinalFilename . '-' . $suffix . '.' . $originalExtension;
                $s3Path = "videos/{$finalFilename}";
                $suffix++;
            }

            $destinationPath = storage_path('app/tmp');
            $finalPath = "{$destinationPath}/{$finalFilename}";

            if (! is_dir($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $isRetry = file_exists($finalPath);
            $tempPath = storage_path("app/chunks/{$uploadId}");

            if (! $isRetry) {
                if (! is_dir($tempPath)) {
                    return response()->json([
                        'error' => 'Dossier des chunks introuvable. Relancez l’upload des morceaux.',
                    ], 404);
                }

                $output = fopen($finalPath, 'wb');
                for ($i = 0; $i < $total; $i++) {
                    $chunkPath = "{$tempPath}/chunk_{$i}";
                    if (! file_exists($chunkPath)) {
                        fclose($output);
                        @unlink($finalPath);

                        return response()->json([
                            'error' => "Chunk #{$i} manquant.",
                            'details' => 'Un ou plusieurs morceaux ont été perdus. Relancez l’upload depuis le début.',
                        ], 400);
                    }

                    $in = fopen($chunkPath, 'rb');
                    stream_copy_to_stream($in, $output);
                    fclose($in);
                    unlink($chunkPath);

                    Cache::put("video_progress_{$uploadId}", (($i + 1) / $total) * 100, now()->addMinutes(10));
                }
                fclose($output);
                @rmdir($tempPath);
            }

            if (file_exists($finalPath) && filesize($finalPath) > self::MAX_TOTAL_BYTES) {
                @unlink($finalPath);

                return response()->json(['error' => 'Fichier assemblé trop volumineux.'], 413);
            }

            $contentType = match ($originalExtension) {
                'mp4' => 'video/mp4',
                'mov' => 'video/quicktime',
                'webm' => 'video/webm',
                'avi' => 'video/x-msvideo',
                default => 'application/octet-stream',
            };

            $stream = fopen($finalPath, 'rb');
            if ($stream === false) {
                return response()->json([
                    'error' => 'Impossible d’ouvrir le fichier assemblé.',
                    'retry_finalize' => true,
                ], 500);
            }

            $visibility = config('filesystems.disks.s3.visibility', 'private');

            Storage::disk('s3')->put($s3Path, $stream, [
                'visibility' => $visibility,
                'ContentType' => $contentType,
            ]);

            if (is_resource($stream)) {
                fclose($stream);
                $stream = null;
            }

            $fileSize = file_exists($finalPath) ? filesize($finalPath) : null;
            @unlink($finalPath);
            Cache::forget("video_progress_{$uploadId}");
            Cache::forget("upload_bytes_{$uploadId}");

            // Stocker la clé relative en DB ; l’URL signée sert à la prévisualisation immédiate
            return response()->json([
                'path' => $s3Path,
                'preview_url' => s3_media_url($s3Path),
                's3_key' => $s3Path,
                'file_size' => $fileSize,
            ]);
        } catch (\Throwable $e) {
            if (is_resource($stream)) {
                fclose($stream);
            }

            Log::error('Finalize video upload failed', [
                'uploadId' => $uploadId,
                'message' => $e->getMessage(),
                'exceptionClass' => get_class($e),
            ]);

            $payload = [
                'error' => 'Erreur lors de la finalisation.',
                'retry_finalize' => true,
                'uploadId' => $uploadId,
            ];

            if (config('app.debug')) {
                $payload['details'] = $e->getMessage();
            }

            return response()->json($payload, 500);
        }
    }

    /**
     * Retourne la progression d’assemblage d’un upload (0–100).
     *
     * @param  Request  $request  Query uploadId
     * @return \Illuminate\Http\JsonResponse
     */
    public function progress(Request $request)
    {
        $validated = $request->validate([
            'uploadId' => ['required', 'string', 'max:80', 'regex:/^[A-Za-z0-9._-]+$/'],
        ]);

        $progress = Cache::get("video_progress_{$validated['uploadId']}", 0);

        return response()->json([
            'progress' => round((float) $progress, 2),
        ]);
    }
}
