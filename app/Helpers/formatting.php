<?php
/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

if (! function_exists("getRandomNumber")) {
    function getRandomNumber($n)
    {
        $characters   = '0123456789';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}
if (! function_exists('usersByRoleQuery')) {
    /**
     * Query users ayant un rôle (pivot Spatie model_has_roles).
     *
     * @param  string|null  $roleName  Filtre sur roles.role_name et/ou roles.name
     * @return \Illuminate\Database\Query\Builder
     */
    function usersByRoleQuery(?string $roleName = null)
    {
        $userModel = config('auth.providers.users.model', \App\Models\User::class);

        $query = DB::table('users')
            ->join('model_has_roles', function ($join) use ($userModel) {
                $join->on('users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.model_type', '=', $userModel);
            })
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id');

        if ($roleName !== null) {
            $query->where(function ($q) use ($roleName) {
                $q->where('roles.role_name', $roleName)
                    ->orWhere('roles.name', $roleName)
                    ->orWhere('roles.name', strtolower(str_replace(' ', '_', $roleName)));
            });
        }

        return $query;
    }
}

if (! function_exists('countUsersByRole')) {
    /**
     * Compte les utilisateurs pour un libellé de rôle donné.
     *
     * @param  string  $roleName  Nom métier (ex. Membre)
     * @return int
     */
    function countUsersByRole(string $roleName): int
    {
        return usersByRoleQuery($roleName)->count();
    }
}

if (! function_exists("formatIntegerNumber")) {
    function formatIntegerNumber($number)
    {
        if (Session::has('locale')) {
            $sessionLocale = Session::get('locale');

            if ($sessionLocale !== 'fr') {
                return number_format($number, 0, '.', ',');

            } else {
                return number_format($number, 0, ',', ' ');
            }
        } else {
            $appLocale = app()->getLocale();

            if ($appLocale !== 'fr') {
                return number_format($number, 0, '.', ',');

            } else {
                return number_format($number, 0, ',', ' ');
            }
        }
    }
}

if (! function_exists("formatDecimalNumber")) {
    function formatDecimalNumber($number)
    {
        if (Session::has('locale')) {
            $sessionLocale = Session::get('locale');

            if ($sessionLocale !== 'fr') {
                return number_format($number, 2, '.', ',');

            } else {
                return number_format($number, 2, ',', ' ');
            }
        } else {
            $appLocale = app()->getLocale();

            if ($appLocale !== 'fr') {
                return number_format($number, 2, '.', ',');

            } else {
                return number_format($number, 2, ',', ' ');
            }
        }
    }
}

if (! function_exists("timeAgo")) {
    function timeAgo($datetime, $full = false)
    {
        $now  = new DateTime;
        $ago  = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        if (Session::has('locale')) {
            $sessionLocale = Session::get('locale');

            switch ($sessionLocale) {
                case 'en':
                    $string = [
                        'y' => 'year',
                        'm' => 'month',
                        'w' => 'week',
                        'd' => 'day',
                        'h' => 'hour',
                        'i' => 'minute',
                        's' => 'second',
                    ];

                    foreach ($string as $k => &$v) {
                        if ($diff->$k) {
                            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');

                        } else {
                            unset($string[$k]);
                        }
                    }

                    if (! $full) {
                        $string = array_slice($string, 0, 1);
                    }

                    return $string ? implode(', ', $string) . ' ago' : 'just now';
                    break;

                default:
                    $string = [
                        'y' => 'an',
                        'm' => 'mois',
                        'w' => 'semaine',
                        'd' => 'jour',
                        'h' => 'heure',
                        'i' => 'minute',
                        's' => 'seconde',
                    ];

                    foreach ($string as $k => &$v) {
                        if ($diff->$k) {
                            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 && $v !== 'mois' ? 's' : '');

                        } else {
                            unset($string[$k]);
                        }
                    }

                    if (! $full) {
                        $string = array_slice($string, 0, 1);
                    }

                    return $string ? 'Il y a ' . implode(', ', $string) : 'en ce moment';
                    break;
            }

        } else {
            $appLocale = app()->getLocale();

            switch ($appLocale) {
                case 'en':
                    $string = [
                        'y' => 'year',
                        'm' => 'month',
                        'w' => 'week',
                        'd' => 'day',
                        'h' => 'hour',
                        'i' => 'minute',
                        's' => 'second',
                    ];

                    foreach ($string as $k => &$v) {
                        if ($diff->$k) {
                            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');

                        } else {
                            unset($string[$k]);
                        }
                    }

                    if (! $full) {
                        $string = array_slice($string, 0, 1);
                    }

                    return $string ? implode(', ', $string) . ' ago' : 'just now';
                    break;

                default:
                    $string = [
                        'y' => 'an',
                        'm' => 'mois',
                        'w' => 'semaine',
                        'd' => 'jour',
                        'h' => 'heure',
                        'i' => 'minute',
                        's' => 'seconde',
                    ];

                    foreach ($string as $k => &$v) {
                        if ($diff->$k) {
                            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 && $v !== 'mois' ? 's' : '');

                        } else {
                            unset($string[$k]);
                        }
                    }

                    if (! $full) {
                        $string = array_slice($string, 0, 1);
                    }

                    return $string ? 'Il y a ' . implode(', ', $string) : 'en ce moment';
                    break;
            }
        }
    }
}
