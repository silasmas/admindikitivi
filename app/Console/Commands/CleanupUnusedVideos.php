<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Media; // adapte le nom si ton modèle est différent

class CleanupUnusedVideos extends Command
{
    protected $signature = 'videos:cleanup';
    protected $description = 'Supprime les vidéos S3 non référencées dans la base de données';

    public function handle()
    {
        $this->info("Recherche des vidéos non utilisées...");

        $usedFiles = Media::pluck('media_url')
            ->map(function ($url) {
                return basename(parse_url($url, PHP_URL_PATH)); // extrait le nom du fichier
            })
            ->filter()
            ->toArray();

        $allFiles = Storage::disk('s3')->files('videos');

        $toDelete = collect($allFiles)->filter(function ($path) use ($usedFiles) {
            return !in_array(basename($path), $usedFiles);
        });

        foreach ($toDelete as $file) {
            Storage::disk('s3')->delete($file);
            $this->line("🗑️ Supprimé : {$file}");
        }

        $this->info("Nettoyage terminé. " . $toDelete->count() . " fichier(s) supprimé(s).");
    }
}
