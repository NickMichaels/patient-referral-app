<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251125014344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE practitioner_schedule (id INT AUTO_INCREMENT NOT NULL, practitioner_id INT NOT NULL, shift_start TIME NOT NULL, shift_end TIME NOT NULL, day_of_week VARCHAR(255) NOT NULL, INDEX IDX_F686372DD980C594 (practitioner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8MB4 COLLATE `UTF8MB4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE practitioner_schedule ADD CONSTRAINT FK_F686372DD980C594 FOREIGN KEY (practitioner_id) REFERENCES practitioner (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE practitioner_schedule DROP FOREIGN KEY FK_F686372DD980C594');
        $this->addSql('DROP TABLE practitioner_schedule');
    }
}
