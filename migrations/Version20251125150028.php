<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251125150028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, practicioner_id INT NOT NULL, provider_id INT NOT NULL, patient_id INT NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_FE38F844D980C594 (practicioner_id), INDEX IDX_FE38F844A53A8AA (provider_id), INDEX IDX_FE38F8446B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8MB4 COLLATE `UTF8MB4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844D980C594 FOREIGN KEY (practicioner_id) REFERENCES practicioner (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8446B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844D980C594');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844A53A8AA');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8446B899279');
        $this->addSql('DROP TABLE appointment');
    }
}
