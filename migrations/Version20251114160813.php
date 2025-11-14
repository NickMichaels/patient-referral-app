<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114160813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patient_referral (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, sending_provider_id INT NOT NULL, receiving_provider_id INT NOT NULL, sending_practicioner_id INT DEFAULT NULL, receiving_practicioner_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4482C5A56B899279 (patient_id), UNIQUE INDEX UNIQ_4482C5A5F2A1A1C7 (sending_provider_id), UNIQUE INDEX UNIQ_4482C5A5F37DAA25 (receiving_provider_id), UNIQUE INDEX UNIQ_4482C5A58AF128D4 (sending_practicioner_id), UNIQUE INDEX UNIQ_4482C5A5A058F403 (receiving_practicioner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8MB4 COLLATE `UTF8MB4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE patient_referral ADD CONSTRAINT FK_4482C5A56B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE patient_referral ADD CONSTRAINT FK_4482C5A5F2A1A1C7 FOREIGN KEY (sending_provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE patient_referral ADD CONSTRAINT FK_4482C5A5F37DAA25 FOREIGN KEY (receiving_provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE patient_referral ADD CONSTRAINT FK_4482C5A58AF128D4 FOREIGN KEY (sending_practicioner_id) REFERENCES practicioner (id)');
        $this->addSql('ALTER TABLE patient_referral ADD CONSTRAINT FK_4482C5A5A058F403 FOREIGN KEY (receiving_practicioner_id) REFERENCES practicioner (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient_referral DROP FOREIGN KEY FK_4482C5A56B899279');
        $this->addSql('ALTER TABLE patient_referral DROP FOREIGN KEY FK_4482C5A5F2A1A1C7');
        $this->addSql('ALTER TABLE patient_referral DROP FOREIGN KEY FK_4482C5A5F37DAA25');
        $this->addSql('ALTER TABLE patient_referral DROP FOREIGN KEY FK_4482C5A58AF128D4');
        $this->addSql('ALTER TABLE patient_referral DROP FOREIGN KEY FK_4482C5A5A058F403');
        $this->addSql('DROP TABLE patient_referral');
    }
}
