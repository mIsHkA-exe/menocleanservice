<?php

namespace App\Tests\Double;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

/**
 * Mailer de test : garde les messages, ou lève l'exception demandée.
 */
final class MailerEspion implements MailerInterface
{
    /** @var list<RawMessage> */
    public array $messages = [];

    public function __construct(private readonly ?\Throwable $erreur = null)
    {
    }

    public function send(RawMessage $message, ?Envelope $envelope = null): void
    {
        if (null !== $this->erreur) {
            throw $this->erreur;
        }

        $this->messages[] = $message;
    }
}
