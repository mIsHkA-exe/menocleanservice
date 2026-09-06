<?php

namespace App\Service;

use App\Entity\DemandeDevis;
use App\Entity\MessageContact;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class NotificationMailer
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger,
        private readonly string $fromEmail,
        private readonly string $contactEmail,
    ) {
    }

    public function notifierDemandeDevis(DemandeDevis $demande): bool
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->fromEmail, 'MENO CLEAN SERVICES'))
            ->to($this->contactEmail)
            ->replyTo(new Address($demande->getEmail(), $demande->getNom()))
            ->subject('Nouvelle demande de devis — ' . $demande->getNom())
            ->htmlTemplate('emails/demande_devis.html.twig')
            ->context(['demande' => $demande]);

        return $this->envoyer($email, 'demande de devis', $demande->getId());
    }

    public function notifierMessageContact(MessageContact $message): bool
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->fromEmail, 'MENO CLEAN SERVICES'))
            ->to($this->contactEmail)
            ->replyTo(new Address($message->getEmail(), $message->getNom()))
            ->subject('Nouveau message — ' . $message->getNom())
            ->htmlTemplate('emails/message_contact.html.twig')
            ->context(['message' => $message]);

        return $this->envoyer($email, 'message de contact', $message->getId());
    }

    private function envoyer(TemplatedEmail $email, string $type, ?int $id): bool
    {
        try {
            $this->mailer->send($email);

            return true;
        } catch (TransportExceptionInterface $exception) {
            // L'utilisateur ne doit JAMAIS voir ce détail technique.
            $this->logger->error("Échec de l'envoi de la notification ({$type} #{$id})", [
                'exception' => $exception,
            ]);

            return false;
        }
    }
}