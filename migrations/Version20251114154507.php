<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114154507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE practicioner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, job_title VARCHAR(255) NOT NULL, specialty VARCHAR(255) DEFAULT NULL, license_number VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8MB4 COLLATE `UTF8MB4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practicioner_provider (practicioner_id INT NOT NULL, provider_id INT NOT NULL, INDEX IDX_3E7A554AD980C594 (practicioner_id), INDEX IDX_3E7A554AA53A8AA (provider_id), PRIMARY KEY(practicioner_id, provider_id)) DEFAULT CHARACTER SET UTF8MB4 COLLATE `UTF8MB4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE practicioner_provider ADD CONSTRAINT FK_3E7A554AD980C594 FOREIGN KEY (practicioner_id) REFERENCES practicioner (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE practicioner_provider ADD CONSTRAINT FK_3E7A554AA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE practicioner_provider DROP FOREIGN KEY FK_3E7A554AD980C594');
        $this->addSql('ALTER TABLE practicioner_provider DROP FOREIGN KEY FK_3E7A554AA53A8AA');
        $this->addSql('DROP TABLE practicioner');
        $this->addSql('DROP TABLE practicioner_provider');
    }
}
