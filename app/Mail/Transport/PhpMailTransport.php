<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

/**
 * Transport e-mail via la fonction PHP mail().
 * Utile sur Hostinger quand SMTP échoue (auth 535) et que proc_open/sendmail est désactivé.
 */
class PhpMailTransport extends AbstractTransport
{
    /**
     * Envoie le message via mail().
     *
     * @param  SentMessage  $message  Message Symfony
     * @return void
     *
     * @throws \RuntimeException Si mail() échoue
     */
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $to = collect($email->getTo())
            ->map(fn (Address $address) => $address->toString())
            ->implode(', ');

        $subject = $email->getSubject() ?? '';

        $headers = $email->getPreparedHeaders();
        $headers->remove('Subject');
        $headers->remove('To');

        $body = $email->getHtmlBody() ?? $email->getTextBody() ?? '';
        $contentType = $email->getHtmlBody() !== null
            ? 'text/html; charset=UTF-8'
            : 'text/plain; charset=UTF-8';

        $headerLines = $headers->toString();
        if (! str_contains(strtolower($headerLines), 'content-type:')) {
            $headerLines .= "Content-Type: {$contentType}\r\n";
        }
        if (! str_contains(strtolower($headerLines), 'mime-version:')) {
            $headerLines .= "MIME-Version: 1.0\r\n";
        }

        $ok = @mail($to, $subject, $body, $headerLines);

        if (! $ok) {
            throw new \RuntimeException('Échec de l’envoi via PHP mail().');
        }
    }

    /**
     * Identifiant du transport.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'phpmail';
    }
}
