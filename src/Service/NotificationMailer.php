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
        return $this->envoyer('demande de devis', $demande->getId(), fn (): TemplatedEmail => (new TemplatedEmail())
            ->from(new Address($this->fromEmail, 'MENO CLEAN SERVICES'))
            ->to($this->contactEmail)
            ->replyTo(new Address($demande->getEmail(), $demande->getNom()))
            ->subject('Nouvelle demande de devis — ' . $demande->getNom())
            ->htmlTemplate('emails/demande_devis.html.twig')
            ->context(['demande' => $demande]));
    }

    public function notifierMessageContact(MessageContact $message): bool
    {
        return $this->envoyer('message de contact', $message->getId(), fn (): TemplatedEmail => (new TemplatedEmail())
            ->from(new Address($this->fromEmail, 'MENO CLEAN SERVICES'))
            ->to($this->contactEmail)
            ->replyTo(new Address($message->getEmail(), $message->getNom()))
            ->subject('Nouveau message — ' . $message->getNom())
            ->htmlTemplate('emails/message_contact.html.twig')
            ->context(['message' => $message]));
    }

    /**
     * La demande est déjà enregistrée quand cette méthode est appelée (B01) :
     * aucune erreur ne doit remonter jusqu'au visiteur sous forme de page 500,
     * sinon il renverrait le formulaire et créerait un doublon (R09).
     *
     * La construction de l'e-mail est faite DANS le bloc try : une adresse
     * acceptée par le formulaire mais refusée par le composant Mime
     * (RfcComplianceException) est ainsi interceptée elle aussi.
     *
     * @param \Closure(): TemplatedEmail $construire
     */
    private function envoyer(string $type, ?int $id, \Closure $construire): bool
    {
        try {
            $this->mailer->send($construire());

            return true;
        } catch (TransportExceptionInterface $exception) {
            // Serveur SMTP injoignable, identifiants refusés, etc.
            $this->logger->error("Échec de l'envoi de la notification ({$type} #{$id}) : erreur de transport.", [
                'exception' => $exception,
            ]);

            return false;
        } catch (\Throwable $exception) {
            // Toute autre erreur : rendu du template, adresse refusée, Messenger…
            // C'est un défaut à corriger, d'où le niveau "critical".
            $this->logger->critical("Échec de l'envoi de la notification ({$type} #{$id}) : erreur inattendue.", [
                'exception' => $exception,
            ]);

            return false;
        }
    }
}
