<?php
/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */

use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Résout l'URL de lecture d'une vidéo selon la source (YouTube, Vimeo, AWS S3, etc.)
 * @param string|null $mediaUrl L'URL ou le chemin stocké (YouTube, Vimeo, S3 full URL, ou path S3)
 * @param string|null $source La source déclarée : 'youtube', 'vimeo', 'aws', 's3', etc.
 * @return array{url: string|null, type: string} ['url' => url complète, 'type' => 'youtube'|'vimeo'|'direct']
 */
if (!function_exists('resolve_media_video')) {
    function resolve_media_video(?string $mediaUrl, ?string $source = null): array
    {
        if (empty($mediaUrl) || !is_string($mediaUrl)) {
            return ['url' => null, 'type' => 'direct'];
        }
        $mediaUrl = trim($mediaUrl);
        $source = strtolower(trim((string) $source));

        // Déjà une URL complète http(s)
        if (str_starts_with($mediaUrl, 'http://') || str_starts_with($mediaUrl, 'https://')) {
            if (str_contains($mediaUrl, 'youtube.com') || str_contains($mediaUrl, 'youtu.be')) {
                return ['url' => $mediaUrl, 'type' => 'youtube'];
            }
            if (str_contains($mediaUrl, 'vimeo.com')) {
                return ['url' => $mediaUrl, 'type' => 'vimeo'];
            }
            // S3 ou autre URL directe
            return ['url' => $mediaUrl, 'type' => 'direct'];
        }

        // Path relatif : probablement S3 (AWS)
        if ($source === 'aws' || $source === 's3' || $source === 'amazon' || empty($source)) {
            try {
                $fullUrl = Storage::disk('s3')->url($mediaUrl);
                return ['url' => $fullUrl, 'type' => 'direct'];
            } catch (\Throwable $e) {
                // Fallback : essayer comme storage public local
                $fullUrl = asset('storage/' . ltrim($mediaUrl, '/'));
                return ['url' => $fullUrl, 'type' => 'direct'];
            }
        }

        return ['url' => $mediaUrl, 'type' => 'direct'];
    }
}

// Get web URL
if (!function_exists('getWebURL')) {
    function getWebURL()
    {
        return (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    }
}
if (!function_exists('sexe')) {
    function sexe($info)
    {
        switch ($info) {
            case 'M':
                return 'Homme';
                break;
            case 'F':
                return 'Femme';
                break;

            default:
                return "Pas d'info du genre";
                break;
        }
    }
}

// pour enregistrer un fichier
if (!function_exists('uploadFile')) {
    function uploadFile(Request $request, Media $media, $fieldName, $storagePath)
    {
        if ($request->file($fieldName)) {
            // Supprimer l'ancien fichier s'il existe
            if ($media->$fieldName) {
                Storage::disk('public')->delete($media->$fieldName);
            }
// Générer un nom de fichier aléatoire
            $randomFileName = Str::random(10) . '.' . $request->file($fieldName)->extension();

// Stocker le fichier avec le nom généré
            $filePath = Storage::disk('public')->putFileAs(
                $storagePath,
                $request->file($fieldName),
                $randomFileName
            );
            // Mettre à jour l'URL dans le modèle
            $media->update([$fieldName => Storage::url($filePath)]);
        }
    }
}
// Get APIs URL
if (!function_exists('getApiURL')) {
    function getApiURL()
    {
        return 'https://api.dikitivi.com/api';
        // return 'https://apidikitivi.jptshienda.com/api';
    }
}

// Get API toke
if (!function_exists('getApiToken')) {
    function getApiToken()
    {
        return '';
    }
}
if (!function_exists('s')) {
    function s($nbr)
    {
        if (count($nbr) > 0) {
            return 's';
        } else {
            return '';
        }
    }
}

// Check if a value exists into an multidimensional array
if (!function_exists('inArrayR')) {
    function inArrayR($needle, $haystack, $key)
    {
        return in_array($needle, collect($haystack)->pluck($key)->toArray());
    }
}

// Month fully readable
if (!function_exists('explicitMonth')) {
    function explicitMonth($month)
    {
        setlocale(LC_ALL, app()->getLocale());

        return utf8_encode(strftime("%B", strtotime(date('F', mktime(0, 0, 0, $month, 10)))));
    }
}

// Date fully readable
if (!function_exists('explicitDate')) {
    function explicitDate($date)
    {
        setlocale(LC_ALL, app()->getLocale());

        return utf8_encode(Carbon::parse($date)->formatLocalized('%d %B %Y'));
    }
}

// Delete item from exploded array
if (!function_exists('deleteExplodedArrayItem')) {
    function deleteExplodedArrayItem($separator, $subject, $item)
    {
        $explodes = explode($separator, $subject);
        $clean_inventory = array();

        foreach ($explodes as $explode) {
            if (!isset($clean_inventory[$explode])) {
                $clean_inventory[$explode] = 0;
            }

            $clean_inventory[$explode]++;
        }

        // Item can be deleted
        unset($clean_inventory[$item]);

        $saved = array();

        foreach ($clean_inventory as $key => $quantity) {
            $saved = array_merge($saved, array_fill(0, $quantity, $key));
        }

        return implode($separator, $saved);
    }
}

// Add an item to exploded array
if (!function_exists('addItemsToExplodedArray')) {
    function addItemsToExplodedArray($separator, $subject, $items)
    {
        $explodes = explode($separator, $subject);
        $saved = array_merge($explodes, $items);

        return implode($separator, $saved);
    }
}

// Friendly username from names
if (!function_exists("friendlyUsername")) {
    function friendlyUsername($str)
    {
        // convert to entities
        $string = htmlentities($str, ENT_QUOTES, 'UTF-8');
        // regex to convert accented chars into their closest a-z ASCII equivelent
        $string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $string);
        // convert back from entities
        $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
        // any straggling characters that are not strict alphanumeric are replaced with an underscore
        $string = preg_replace('~[^0-9a-z]+~i', '_', $string);
        // trim / cleanup / all lowercase
        $string = trim($string, '-');
        $string = strtolower($string);

        return $string;
    }
}
