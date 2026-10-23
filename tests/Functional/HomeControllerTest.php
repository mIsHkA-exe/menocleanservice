<?php

namespace App\Tests\Functional;

use App\Entity\DemandeDevis;
use App\Entity\MessageContact;
use App\Entity\Service;
use App\Enum\CategorieService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Form;

/**
 * Page d'accueil et ses deux formulaires, sans JavaScript.
 *
 * Prérequis : base de test créée et migrée (voir la fiche sync-backend-v1.md).
 * Chaque test vide les trois tables puis recrée un service actif.
 *
 * B01 : demande enregistrée et e-mail tracé · B05 / I07 : 303 en succès, 422 en échec
 * I04 : piège anti-robot · I15 : paramètre ?categorie= accepté
 * C09 : messages par défaut en français · C10 : longueurs limitées
 * U11 : protection CSRF sans état, envoi sans JavaScript
 */
final class HomeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private int $serviceId;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $em = $this->entityManager();
        $em->createQuery('DELETE FROM ' . DemandeDevis::class)->execute();
        $em->createQuery('DELETE FROM ' . MessageContact::class)->execute();
        $em->createQuery('DELETE FROM ' . Service::class)->execute();

        $service = (new Service())
            ->setNom('Entretien de bureaux')
            ->setDescription('Service de test.')
            ->setIcone('icon-briefcase')
            ->setCategorie(CategorieService::Bureaux)
            ->setSlug('entretien-bureaux')
            ->setPosition(10);
        $em->persist($service);
        $em->flush();

        $this->serviceId = (int) $service->getId();
    }

    public function testAccueilAfficheLesDeuxFormulaires(): void
    {
        $crawler = $this->client->request('GET', '/');

        self::assertResponseStatusCodeSame(200);
        self::assertCount(1, $crawler->filter('form[name="demande_devis"]'));
        self::assertCount(1, $crawler->filter('form[name="message_contact"]'));
    }

    /**
     * I15 : le paramètre est lu par Twig ; le serveur ne le rejette jamais.
     */
    #[DataProvider('adressesAvecCategorie')]
    public function testParametreCategorieAccepte(string $adresse): void
    {
        $this->client->request('GET', $adresse);

        self::assertResponseStatusCodeSame(200);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function adressesAvecCategorie(): iterable
    {
        yield 'catégorie connue' => ['/?categorie=bureaux'];
        yield 'catégorie inconnue' => ['/?categorie=inconnue'];
        yield 'tableau' => ['/?categorie[]=bureaux'];
    }

    public function testDemandeDevisValideEnregistreeEtRedirigee(): void
    {
        $this->client->submit($this->formulaireDevis());

        self::assertResponseRedirects('/#devis', 303);

        $demandes = $this->demandesDevis();
        self::assertCount(1, $demandes);
        self::assertSame('Entretien de bureaux', $demandes[0]->getPrestationLibelle());
        self::assertTrue($demandes[0]->isEmailEnvoye());

        $this->client->followRedirect();
        self::assertStringContainsString('Votre demande a bien été enregistrée', (string) $this->client->getResponse()->getContent());
    }

    public function testDemandeDevisIncompleteRenvoie422(): void
    {
        $this->client->submit($this->formulaireDevis(['demande_devis[nom]' => '']));

        self::assertResponseStatusCodeSame(422);
        self::assertCount(0, $this->demandesDevis());
    }

    public function testPiegeAntiRobotRefuseLaDemande(): void
    {
        $this->client->submit($this->formulaireDevis(['demande_devis[siteWeb]' => 'https://spam.example.com']));

        self::assertResponseStatusCodeSame(422);
        self::assertCount(0, $this->demandesDevis());
    }

    public function testAdresseInterventionTropLongueRefusee(): void
    {
        $this->client->submit($this->formulaireDevis(['demande_devis[adresseIntervention]' => str_repeat('a', 256)]));

        self::assertResponseStatusCodeSame(422);
        self::assertStringContainsString('ne doit pas dépasser 255 caractères', (string) $this->client->getResponse()->getContent());
        self::assertCount(0, $this->demandesDevis());
    }

    public function testMessagesParDefautEnFrancais(): void
    {
        // "Length(min: 2)" sur le nom n'a pas de message personnalisé.
        $this->client->submit($this->formulaireDevis(['demande_devis[nom]' => 'A']));

        self::assertResponseStatusCodeSame(422);
        $contenu = (string) $this->client->getResponse()->getContent();
        self::assertStringContainsString('Cette chaîne est trop courte', $contenu);
        self::assertStringNotContainsString('This value is too short', $contenu);
    }

    public function testMessageContactValideEnregistreEtRedirige(): void
    {
        $this->client->submit($this->formulaireContact());

        self::assertResponseRedirects('/#contact', 303);

        $messages = $this->messagesContact();
        self::assertCount(1, $messages);
        self::assertTrue($messages[0]->isEmailEnvoye());
    }

    public function testEmailContactTropLongRefuse(): void
    {
        $email = str_repeat('a', 175) . '@exemple.fr'; // 186 caractères
        $this->client->submit($this->formulaireContact(['message_contact[email]' => $email]));

        self::assertResponseStatusCodeSame(422);
        self::assertStringContainsString('ne doit pas dépasser 180 caractères', (string) $this->client->getResponse()->getContent());
        self::assertCount(0, $this->messagesContact());
    }

    /**
     * Sans JavaScript, le navigateur envoie l'origine de la page (en-têtes
     * Origin / Referer) : c'est elle qui valide la protection CSRF sans état.
     * Les autres tests passent par ce chemin (Referer posé par le client de test).
     * Sans aucune origine ni jeton double-soumission, l'envoi est refusé.
     */
    public function testEnvoiSansOrigineRefuseParLaProtectionCsrf(): void
    {
        $valeurs = $this->formulaireDevis()->getPhpValues();

        $this->client->restart(); // plus d'historique, donc plus de Referer
        $this->client->request('POST', '/', $valeurs);

        self::assertResponseStatusCodeSame(422);
        self::assertCount(0, $this->demandesDevis());
    }

    /**
     * @param array<string, string> $remplacements
     */
    private function formulaireDevis(array $remplacements = []): Form
    {
        $crawler = $this->client->request('GET', '/');
        $formulaire = $crawler->filter('form[name="demande_devis"]')->form();

        $formulaire->setValues(array_merge([
            'demande_devis[nom]' => 'Jean Dupont',
            'demande_devis[societe]' => 'Exemple SARL',
            'demande_devis[telephone]' => '06 12 34 56 78',
            'demande_devis[email]' => 'jean.dupont@example.com',
            'demande_devis[adresseIntervention]' => '12 rue de Paris, 78500 Sartrouville',
            'demande_devis[service]' => (string) $this->serviceId,
            'demande_devis[surface]' => '120',
            'demande_devis[message]' => 'Entretien hebdomadaire de nos bureaux.',
        ], $remplacements));
        $formulaire['demande_devis[consentement]']->tick();

        return $formulaire;
    }

    /**
     * @param array<string, string> $remplacements
     */
    private function formulaireContact(array $remplacements = []): Form
    {
        $crawler = $this->client->request('GET', '/');
        $formulaire = $crawler->filter('form[name="message_contact"]')->form();

        $formulaire->setValues(array_merge([
            'message_contact[nom]' => 'Marie Martin',
            'message_contact[email]' => 'marie.martin@example.com',
            'message_contact[telephone]' => '01 23 45 67 89',
            'message_contact[message]' => 'Bonjour, je souhaite un renseignement.',
        ], $remplacements));
        $formulaire['message_contact[consentement]']->tick();

        return $formulaire;
    }

    /**
     * @return list<DemandeDevis>
     */
    private function demandesDevis(): array
    {
        return $this->entityManager()->getRepository(DemandeDevis::class)->findAll();
    }

    /**
     * @return list<MessageContact>
     */
    private function messagesContact(): array
    {
        return $this->entityManager()->getRepository(MessageContact::class)->findAll();
    }

    private function entityManager(): EntityManagerInterface
    {
        $em = static::getContainer()->get('doctrine')->getManager();
        self::assertInstanceOf(EntityManagerInterface::class, $em);
        $em->clear();

        return $em;
    }
}
