<?php
namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\aws;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    // public function uploadChunk(Request $request)
    // {
    //     $chunk = $request->file('chunk');
    //     $index = $request->input('index');
    //     $total = $request->input('total');
    //     $uploadId = $request->input('uploadId');
    //     $filename = str_replace(' ', '-', $request->input('filename'));

    //     $tempPath = storage_path("app/chunks/{$uploadId}");
    //     if (!is_dir($tempPath)) {
    //         mkdir($tempPath, 0755, true);
    //     }

    //     $chunk->move($tempPath, "chunk_{$index}");

    //     if ($this->allChunksUploaded($tempPath, $total)) {
    //         $finalPath = storage_path("app/public/videos/{$uploadId}-{$filename}");
    //         $output = fopen($finalPath, 'ab');

    //         for ($i = 0; $i < $total; $i++) {
    //             $chunkPath = "{$tempPath}/chunk_{$i}";
    //             $in = fopen($chunkPath, 'rb');
    //             stream_copy_to_stream($in, $output);
    //             fclose($in);
    //             unlink($chunkPath);
    //         }

    //         fclose($output);
    //         rmdir($tempPath); // Supprimer le dossier temporaire
    //     }

    //     // aws::create([
    //     //     // 'nom' => $validated['media_title'],
    //     //     // 'image' => $request->file('image')->store('aws_cover', 's3'),
    //     //     'video' => $request->file('video')->store('aws_video', 's3'),
    //     //     // 'description' => $validated['description'] ?? null,
    //     // ]);
    //     return response()->json(['success' => true]);
    // }

    // private function allChunksUploaded(string $dir, int $total): bool
    // {
    //     for ($i = 0; $i < $total; $i++) {
    //         if (!file_exists("{$dir}/chunk_{$i}")) {
    //             return false;
    //         }
    //     }
    //     return true;
    // }

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

//     public function finalizeUpload(Request $request)
//     {
//         $uploadId         = $request->input('uploadId');
//         $originalFilename = $request->input('filename');
//         $safeFilename     = str_replace(' ', '-', $originalFilename); // évite les espaces
//         $total            = (int) $request->input('total');

//         $tempPath = storage_path("app/chunks/{$uploadId}");

//         // 🔧 nom final : uploadId + originalFilename
//         $finalFilename = $uploadId . '-' . $safeFilename;

//         // 🔧 dossier de destination
//         // $destinationDir = storage_path('app/public/videos');
//         $destinationDir = storage_path("app/tmp");

//         if (! is_dir($destinationDir)) {
//             mkdir($destinationDir, 0755, true); // Crée le dossier s’il n’existe pas
//         }

//         $finalPath = $destinationDir . '/' . $finalFilename;

//         if (! $this->allChunksUploaded($tempPath, $total)) {
//             return response()->json(['error' => 'Chunks manquants'], 400);
//         }
//         $localTempPath = storage_path("app/tmp/{$finalFilename}");
//         $output = fopen($finalPath, 'ab');

//         for ($i = 0; $i < $total; $i++) {
//             $chunkPath = "{$tempPath}/chunk_{$i}";
//             $in        = fopen($chunkPath, 'rb');
//             stream_copy_to_stream($in, $output);
//             fclose($in);
//             unlink($chunkPath);
//         }

//         fclose($output);
//         rmdir($tempPath);
// // 🔁 ENVOI SUR S3
//         $s3Path = 'videos/' . $finalFilename;
//         Storage::disk('s3')->put($s3Path, file_get_contents($localTempPath), 'public');

//         unlink($localTempPath); // Supprimer local temporaire

//         return response()->json([
//             'path'   => Storage::disk('s3')->url($s3Path),
//             's3_key' => $s3Path,
//         ]);
//         // return response()->json([
//         //     'path' => '/storage/videos/' . $finalFilename,
//         // ]);
//     }

    public function finalizeUpload(Request $request)
    {
        $uploadId         = $request->input('uploadId');
        $originalFilename = $request->input('filename');
        $safeFilename     = str_replace(' ', '-', $originalFilename); // évite les espaces
        $total            = (int) $request->input('total');

        $tempPath = storage_path("app/chunks/{$uploadId}");

        // ✅ Corriger la concaténation
        $finalFilename = $uploadId;
        // $finalFilename = $uploadId . '-' . $safeFilename;

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
        }

        fclose($output);
        rmdir($tempPath);

        // ✅ Envoi sur S3
        $s3Path = 'videos/' . $finalFilename;

        Storage::disk('s3')->put($s3Path, file_get_contents($finalPath), [
            'visibility'  => 'public',
            'ContentType' => 'video/mp4', // important pour la lecture dans le navigateur
        ]);

        unlink($finalPath); // suppression du fichier local

        return response()->json([
            'path'   => Storage::disk('s3')->url($s3Path),
            's3_key' => $s3Path,
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
