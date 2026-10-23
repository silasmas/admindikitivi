<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AgentAccessResetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use RuntimeException;

/**
 * Page publique : saisie du code reçu par e-mail + création du nouveau mot de passe agent.
 */
class AgentAccessResetController extends Controller
{
    /**
     * Affiche le formulaire de réinitialisation des accès.
     *
     * @param  Request  $request  Requête (e-mail optionnel en query)
     * @return View
     */
    public function show(Request $request): View
    {
        return view('auth.reset-access', [
            'email' => old('email', $request->query('email')),
        ]);
    }

    /**
     * Valide le code et enregistre le nouveau mot de passe.
     *
     * @param  Request  $request  Données du formulaire
     * @param  AgentAccessResetService  $service  Service métier
     * @return RedirectResponse
     */
    public function store(Request $request, AgentAccessResetService $service): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'min:6', 'max:16'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'code.required' => 'Le code reçu par e-mail est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        try {
            $service->completeWithCode(
                $validated['email'],
                $validated['code'],
                $validated['password'],
            );
        } catch (RuntimeException $e) {
            return back()
                ->withInput($request->only('email', 'code'))
                ->withErrors(['code' => $e->getMessage()]);
        }

        return redirect()
            ->to('/admin/login')
            ->with('status', 'Mot de passe mis à jour. Vous pouvez vous connecter.');
    }
}
