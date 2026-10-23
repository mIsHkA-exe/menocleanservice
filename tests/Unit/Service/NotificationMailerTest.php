<?php

namespace App\Tests\Unit\Service;

use App\Entity\DemandeDevis;
use App\Entity\MessageContact;
use App\Service\NotificationMailer;
use App\Tests\Double\LoggerEspion;
use App\Tests\Double\MailerEspion;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Notification par e-mail après l'enregistrement d'une demande.
 *
 * B06 : le visiteur est en Reply-To, jamais en expéditeur.
 * R09 : aucune erreur d'envoi ne remonte, quelle qu'elle soit.
 */
final class NotificationMailerTest extends TestCase
{
    private const EXPEDITEUR = 'site@example.com';
    private const DESTINATAIRE = 'contact@example.com';

    public function testDemandeDevisEnvoyeeAvecVisiteurEnReplyTo(): void
    {
        $mailer = new MailerEspion();
        $logger = new LoggerEspion();

        $resultat = $this->notificationMailer($mailer, $logger)->notifierDemandeDevis($this->demandeDevis());

        self::assertTrue($resultat);
        self::assertCount(1, $mailer->messages);
        self::assertSame([], $logger->enregistrements);

        $email = $mailer->messages[0];
        self::assertInstanceOf(TemplatedEmail::class, $email);
        self::assertSame(self::EXPEDITEUR, $email->getFrom()[0]->getAddress());
        self::assertSame(self::DESTINATAIRE, $email->getTo()[0]->getAddress());
        self::assertSame('jean.dupont@example.com', $email->getReplyTo()[0]->getAddress());
        self::assertSame('emails/demande_devis.html.twig', $email->getHtmlTemplate());
    }

    public function testMessageContactEnvoyeAvecVisiteurEnReplyTo(): void
    {
        $mailer = new MailerEspion();

        $resultat = $this->notificationMailer($mailer, new LoggerEspion())->notifierMessageContact($this->messageContact());

        self::assertTrue($resultat);
        self::assertCount(1, $mailer->messages);
        self::assertSame(self::EXPEDITEUR, $mailer->messages[0]->getFrom()[0]->getAddress());
        self::assertSame('marie.martin@example.com', $mailer->messages[0]->getReplyTo()[0]->getAddress());
    }

    public function testErreurDeTransportJournaliseeEnErreur(): void
    {
        $logger = new LoggerEspion();
        $mailer = new MailerEspion(new TransportException('Serveur SMTP injoignable'));

        $resultat = $this->notificationMailer($mailer, $logger)->notifierDemandeDevis($this->demandeDevis());

        self::assertFalse($resultat);
        self::assertSame([LogLevel::ERROR], $logger->niveaux());
    }

    public function testErreurInattendueInterceptee(): void
    {
        // Ex. : erreur de rendu du template d'e-mail, enveloppée par Messenger.
        $logger = new LoggerEspion();
        $mailer = new MailerEspion(new \RuntimeException('Erreur de rendu du template'));

        $resultat = $this->notificationMailer($mailer, $logger)->notifierMessageContact($this->messageContact());

        self::assertFalse($resultat);
        self::assertSame([LogLevel::CRITICAL], $logger->niveaux());
    }

    public function testAdresseRefuseeParMimeInterceptee(): void
    {
        // Le visiteur est validé par le formulaire ; si le composant Mime refuse
        // malgré tout son adresse, l'erreur survient en construisant l'e-mail.
        $demande = $this->demandeDevis()->setEmail("adresse\ninvalide@example.com");
        $mailer = new MailerEspion();
        $logger = new LoggerEspion();

        $resultat = $this->notificationMailer($mailer, $logger)->notifierDemandeDevis($demande);

        self::assertFalse($resultat);
        self::assertSame([], $mailer->messages);
        self::assertSame([LogLevel::CRITICAL], $logger->niveaux());
    }

    private function notificationMailer(MailerInterface $mailer, LoggerEspion $logger): NotificationMailer
    {
        return new NotificationMailer($mailer, $logger, self::EXPEDITEUR, self::DESTINATAIRE);
    }

    private function demandeDevis(): DemandeDevis
    {
        return (new DemandeDevis())
            ->setNom('Jean Dupont')
            ->setTelephone('06 12 34 56 78')
            ->setEmail('jean.dupont@example.com')
            ->setAdresseIntervention('12 rue de Paris, 78500 Sartrouville')
            ->setPrestationLibelle('Entretien de bureaux')
            ->setConsentement(true);
    }

    private function messageContact(): MessageContact
    {
        return (new MessageContact())
            ->setNom('Marie Martin')
            ->setEmail('marie.martin@example.com')
            ->setMessage('Bonjour, je souhaite un renseignement.')
            ->setConsentement(true);
    }
}
