<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260831172918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande_devis (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, societe VARCHAR(150) DEFAULT NULL, telephone VARCHAR(30) NOT NULL, email VARCHAR(180) NOT NULL, adresse_intervention VARCHAR(255) NOT NULL, prestation_libelle VARCHAR(150) NOT NULL, surface INT DEFAULT NULL, message LONGTEXT DEFAULT NULL, consentement TINYINT NOT NULL, statut VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, email_envoye TINYINT DEFAULT 0 NOT NULL, service_id INT DEFAULT NULL, INDEX IDX_7DF94602ED5CA9E6 (service_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE message_contact (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, email VARCHAR(180) NOT NULL, telephone VARCHAR(30) DEFAULT NULL, message LONGTEXT NOT NULL, consentement TINYINT NOT NULL, statut VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, email_envoye TINYINT DEFAULT 0 NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, icone VARCHAR(50) DEFAULT NULL, categorie VARCHAR(30) NOT NULL, slug VARCHAR(160) NOT NULL, position SMALLINT DEFAULT 0 NOT NULL, est_actif TINYINT DEFAULT 1 NOT NULL, UNIQUE INDEX UNIQ_E19D9AD2989D9B62 (slug), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE demande_devis ADD CONSTRAINT FK_7DF94602ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_devis DROP FOREIGN KEY FK_7DF94602ED5CA9E6');
        $this->addSql('DROP TABLE demande_devis');
        $this->addSql('DROP TABLE message_contact');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
