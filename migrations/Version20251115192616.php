<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251115192616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, data JSON NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8MB4 COLLATE `UTF8MB4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_referral (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, sending_provider_id INT NOT NULL, receiving_provider_id INT NOT NULL, sending_practitioner_id INT DEFAULT NULL, receiving_practitioner_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_4482C5A56B899279 (patient_id), INDEX IDX_4482C5A5F2A1A1C7 (sending_provider_id), INDEX IDX_4482C5A5F37DAA25 (receiving_provider_id), INDEX IDX_4482C5A58AF128D4 (sending_practitioner_id), INDEX IDX_4482C5A5A058F403 (receiving_practitioner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8MB4 COLLATE `UTF8MB4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, job_title VARCHAR(255) NOT NULL, specialty VARCHAR(255) DEFAULT NULL, license_number VARCHAR(30) NOT NULL, phone VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8MB4 COLLATE `UTF8MB4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practitioner_provider (practitioner_id INT NOT NULL, provider_id INT NOT NULL, INDEX IDX_3E7A554AD980C594 (practitioner_id), INDEX IDX_3E7A554AA53A8AA (provider_id), PRIMARY KEY(practitioner_id, provider_id)) DEFAULT CHARACTER SET UTF8MB4 COLLATE `UTF8MB4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, specialty VARCHAR(255) DEFAULT NULL, address_line1 VARCHAR(255) NOT NULL, address_line2 VARCHAR(255) DEFAULT NULL, city VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, zip INT NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8MB4 COLLATE `UTF8MB4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE patient_referral ADD CONSTRAINT FK_4482C5A56B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_referral ADD CONSTRAINT FK_4482C5A5F2A1A1C7 FOREIGN KEY (sending_provider_id) REFERENCES provider (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_referral ADD CONSTRAINT FK_4482C5A5F37DAA25 FOREIGN KEY (receiving_provider_id) REFERENCES provider (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_referral ADD CONSTRAINT FK_4482C5A58AF128D4 FOREIGN KEY (sending_practitioner_id) REFERENCES practitioner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_referral ADD CONSTRAINT FK_4482C5A5A058F403 FOREIGN KEY (receiving_practitioner_id) REFERENCES practitioner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_provider ADD CONSTRAINT FK_3E7A554AD980C594 FOREIGN KEY (practitioner_id) REFERENCES practitioner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practitioner_provider ADD CONSTRAINT FK_3E7A554AA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient_referral DROP FOREIGN KEY FK_4482C5A56B899279');
        $this->addSql('ALTER TABLE patient_referral DROP FOREIGN KEY FK_4482C5A5F2A1A1C7');
        $this->addSql('ALTER TABLE patient_referral DROP FOREIGN KEY FK_4482C5A5F37DAA25');
        $this->addSql('ALTER TABLE patient_referral DROP FOREIGN KEY FK_4482C5A58AF128D4');
        $this->addSql('ALTER TABLE patient_referral DROP FOREIGN KEY FK_4482C5A5A058F403');
        $this->addSql('ALTER TABLE practitioner_provider DROP FOREIGN KEY FK_3E7A554AD980C594');
        $this->addSql('ALTER TABLE practitioner_provider DROP FOREIGN KEY FK_3E7A554AA53A8AA');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE patient_referral');
        $this->addSql('DROP TABLE practitioner');
        $this->addSql('DROP TABLE practitioner_provider');
        $this->addSql('DROP TABLE provider');
    }
}
