<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Importe les données depuis un dump SQL phpMyAdmin.
 *
 * Placez votre fichier dump SQL dans : database/seeders/dumps/dikitivi_data.sql
 * Le fichier doit contenir les INSERT dans l'ordre des dépendances (groups, countries,
 * statuses, types, users, roles, etc.).
 *
 * Pour exécuter : php artisan db:seed --class=DikitisiviDataSeeder
 */
class DikitisiviDataSeeder extends Seeder
{
    protected string $dumpPath;

    public function __construct()
    {
        $this->dumpPath = database_path('seeders/dumps/dikitivi_data.sql');
    }

    public function run(): void
    {
        if (! File::exists($this->dumpPath)) {
            $this->command?->warn('Fichier dump non trouvé : ' . $this->dumpPath);
            $this->command?->info('Créez le dossier database/seeders/dumps/ et placez-y dikitivi_data.sql');
            return;
        }

        $sql = File::get($this->dumpPath);
        $sql = preg_replace('/INSERT INTO `/', 'INSERT IGNORE INTO `', $sql);
        // Ignorer youtube_access_tokens (JSON complexe) - tokens OAuth à régénérer
        $sql = preg_replace('/INSERT IGNORE INTO `youtube_access_tokens`[\s\S]*?\);[\s]*\n/s', '', $sql);
        $sql = "SET FOREIGN_KEY_CHECKS=0;\n" . $sql . "\nSET FOREIGN_KEY_CHECKS=1;";

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $this->executeSqlStatements($sql);
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (\Throwable $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->command?->error('Erreur : ' . $e->getMessage());
            throw $e;
        }

        // Synchroniser name/guard_name pour les rôles importés (Spatie)
        DB::table('roles')
            ->where(function ($q) {
                $q->whereNull('name')->orWhere('name', '');
            })
            ->update([
                'name' => DB::raw('COALESCE(LOWER(REPLACE(role_name, " ", "_")), CONCAT("role_", id))'),
                'guard_name' => 'web',
            ]);

        $this->command?->info('Import SQL terminé.');
    }

    protected function executeSqlStatements(string $sql): void
    {
        // Nettoyer commentaires et instructions problématiques
        $sql = preg_replace('/--[^\n]*\n/', "\n", $sql);
        $sql = preg_replace('#/\*!?[\d]*.*?\*/#s', '', $sql);
        $sql = preg_replace('/SET SQL_MODE[^;]*;?/', '', $sql);
        $sql = preg_replace('/SET time_zone[^;]*;?/', '', $sql);
        $sql = preg_replace('/START TRANSACTION\s*;?/', '', $sql);
        $sql = preg_replace('/COMMIT\s*;?/', '', $sql);
        $sql = preg_replace('/\/\*!40101 SET[^*]*\*\//s', '', $sql);

        // Découper en requêtes (séparateur ; en fin de ligne, hors chaînes)
        $statements = [];
        $current = '';
        $inString = false;
        $delim = '';
        $len = strlen($sql);

        for ($i = 0; $i < $len; $i++) {
            $c = $sql[$i];
            if (!$inString) {
                if (($c === "'" || $c === '"') && ($i === 0 || $sql[$i - 1] !== '\\')) {
                    $inString = true;
                    $delim = $c;
                    $current .= $c;
                } elseif ($c === ';' && ($i + 1 >= $len || $sql[$i + 1] === "\n" || $sql[$i + 1] === "\r")) {
                    $current .= $c;
                    $stmt = trim($current);
                    if ($stmt && !preg_match('/^(SET|COMMIT|START)\s/i', $stmt)) {
                        $statements[] = $stmt;
                    }
                    $current = '';
                } else {
                    $current .= $c;
                }
            } else {
                $current .= $c;
                if ($c === $delim && ($i === 0 || $sql[$i - 1] !== '\\')) {
                    $inString = false;
                }
            }
        }
        if (trim($current)) {
            $statements[] = trim($current);
        }

        foreach ($statements as $stmt) {
            if (strlen($stmt) > 5) {
                DB::unprepared($stmt);
            }
        }
    }
}
