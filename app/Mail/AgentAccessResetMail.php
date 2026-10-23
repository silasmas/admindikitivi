<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * E-mail envoyé à un agent pour réinitialiser son accès (code unique).
 */
class AgentAccessResetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  User  $agent  Destinataire
     * @param  string  $code  Code unique à saisir
     * @param  string  $resetUrl  URL de la page de création du mot de passe
     * @param  int  $expiresInHours  Durée de validité du code
     */
    public function __construct(
        public User $agent,
        public string $code,
        public string $resetUrl,
        public int $expiresInHours = 24,
    ) {
    }

    /**
     * Objet de l’e-mail.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réinitialisation de vos accès DikiTivi',
        );
    }

    /**
     * Contenu HTML de l’e-mail.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.agent-access-reset',
        );
    }
}
