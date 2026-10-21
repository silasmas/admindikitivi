<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Autorise uniquement les utilisateurs ayant accès au panel Filament admin.
 */
class EnsurePanelAccess
{
    /**
     * Vérifie canAccessPanel avant de laisser passer la requête.
     *
     * @param  Request  $request  Requête HTTP courante
     * @param  Closure  $next  Handler suivant
     * @param  string  $panelId  ID du panel Filament (défaut admin)
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $panelId = 'admin'): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        $panel = Filament::getPanel($panelId);

        if (! $user->canAccessPanel($panel)) {
            abort(403, 'Accès administration requis.');
        }

        return $next($request);
    }
}
