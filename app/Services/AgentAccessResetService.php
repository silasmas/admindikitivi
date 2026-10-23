<?php

namespace App\Services;

use App\Mail\AgentAccessResetMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Réinitialise les accès d’un agent : code unique + e-mail pour créer un nouveau mot de passe.
 */
class AgentAccessResetService
{
    /** Durée de validité du code en heures. */
    public const CODE_TTL_HOURS = 24;

    /**
     * Génère un code, invalide l’ancien mot de passe et envoie l’e-mail à l’agent.
     *
     * @param  User  $agent  Agent cible (doit avoir un e-mail)
     * @return PasswordReset  Enregistrement créé
     *
     * @throws RuntimeException Si l’e-mail est absent ou l’envoi échoue
     */
    public function resetAndNotify(User $agent): PasswordReset
    {
        $email = trim((string) $agent->email);

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Cet agent n’a pas d’adresse e-mail valide.');
        }

        $code = $this->generateUniqueCode();

        $passwordReset = DB::transaction(function () use ($agent, $email, $code) {
            PasswordReset::query()
                ->where('email', $email)
                ->delete();

            $passwordReset = PasswordReset::create([
                'email' => $email,
                'phone' => $agent->phone,
                'token' => $code,
                // Placeholder obligatoire (colonne non nullable) — non utilisé comme mot de passe
                'former_password' => 'pending-reset',
            ]);

            // Invalide l’ancien mot de passe : connexion impossible jusqu’à la création du nouveau
            $agent->forceFill([
                'password' => Str::password(32),
                'remember_token' => null,
            ])->save();

            if (method_exists($agent, 'tokens')) {
                $agent->tokens()->delete();
            }

            return $passwordReset;
        });

        $resetUrl = route('access.reset.show', [
            'email' => $email,
        ]);

        try {
            Mail::to($email)->send(new AgentAccessResetMail(
                agent: $agent,
                code: $code,
                resetUrl: $resetUrl,
                expiresInHours: self::CODE_TTL_HOURS,
            ));
        } catch (\Throwable $mailError) {
            report($mailError);

            throw new RuntimeException(
                'Le code a été généré mais l’e-mail n’a pas pu être envoyé. '
                . 'Code à transmettre manuellement à l’agent : ' . $code
                . ' — Lien : ' . $resetUrl,
                0,
                $mailError
            );
        }

        return $passwordReset;
    }

    /**
     * Valide le code et définit le nouveau mot de passe.
     *
     * @param  string  $email  E-mail de l’agent
     * @param  string  $code  Code unique reçu par e-mail
     * @param  string  $newPassword  Nouveau mot de passe en clair
     * @return User  Agent mis à jour
     *
     * @throws RuntimeException Si le code est invalide ou expiré
     */
    public function completeWithCode(string $email, string $code, string $newPassword): User
    {
        $email = trim($email);
        $code = strtoupper(trim($code));

        $passwordReset = PasswordReset::query()
            ->where('email', $email)
            ->whereRaw('UPPER(token) = ?', [$code])
            ->first();

        if ($passwordReset === null) {
            throw new RuntimeException('Code invalide ou déjà utilisé.');
        }

        if ($passwordReset->updated_at === null
            || $passwordReset->updated_at->lt(now()->subHours(self::CODE_TTL_HOURS))
        ) {
            $passwordReset->delete();
            throw new RuntimeException('Ce code a expiré. Demandez une nouvelle réinitialisation.');
        }

        $user = User::query()->where('email', $email)->first();

        if ($user === null) {
            throw new RuntimeException('Aucun compte associé à cet e-mail.');
        }

        DB::transaction(function () use ($user, $newPassword, $passwordReset) {
            $user->forceFill([
                'password' => $newPassword,
                'remember_token' => Str::random(60),
            ])->save();

            $passwordReset->delete();
        });

        return $user->fresh();
    }

    /**
     * Génère un code alphanumérique unique (8 caractères).
     *
     * @return string
     */
    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (PasswordReset::query()->where('token', $code)->exists());

        return $code;
    }
}
