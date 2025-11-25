<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251125163127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment ADD patient_referral_id INT DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD cancellation_reason LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844CC99BF8 FOREIGN KEY (patient_referral_id) REFERENCES patient_referral (id)');
        $this->addSql('CREATE INDEX IDX_FE38F844CC99BF8 ON appointment (patient_referral_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844CC99BF8');
        $this->addSql('DROP INDEX IDX_FE38F844CC99BF8 ON appointment');
        $this->addSql('ALTER TABLE appointment DROP patient_referral_id, DROP description, DROP cancellation_reason');
    }
}
