<?php

namespace App\Service;

use App\Entity\DemandeDevis;
use App\Entity\MessageContact;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\NotificationMailer;

class DemandeHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly NotificationMailer $notificationMailer,
    ) {
    }

    public function traiterDemandeDevis(DemandeDevis $demande): void
    {
        // 1. On enregistre D'ABORD : la demande ne peut plus être perdue.
        $this->entityManager->persist($demande);
        $this->entityManager->flush();

        // 2. On notifie ENSUITE, et on trace le résultat.
        $demande->setEmailEnvoye($this->notificationMailer->notifierDemandeDevis($demande));
        $this->entityManager->flush();
    }

    public function traiterMessageContact(MessageContact $message): void
    {
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $message->setEmailEnvoye($this->notificationMailer->notifierMessageContact($message));
        $this->entityManager->flush();
    }
}