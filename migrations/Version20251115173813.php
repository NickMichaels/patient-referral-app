<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251115173813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient_referral DROP INDEX UNIQ_4482C5A56B899279, ADD INDEX IDX_4482C5A56B899279 (patient_id)');
        $this->addSql('ALTER TABLE patient_referral DROP INDEX UNIQ_4482C5A5F2A1A1C7, ADD INDEX IDX_4482C5A5F2A1A1C7 (sending_provider_id)');
        $this->addSql('ALTER TABLE patient_referral DROP INDEX UNIQ_4482C5A5F37DAA25, ADD INDEX IDX_4482C5A5F37DAA25 (receiving_provider_id)');
        $this->addSql('ALTER TABLE patient_referral DROP INDEX UNIQ_4482C5A58AF128D4, ADD INDEX IDX_4482C5A58AF128D4 (sending_practicioner_id)');
        $this->addSql('ALTER TABLE patient_referral DROP INDEX UNIQ_4482C5A5A058F403, ADD INDEX IDX_4482C5A5A058F403 (receiving_practicioner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient_referral DROP INDEX IDX_4482C5A56B899279, ADD UNIQUE INDEX UNIQ_4482C5A56B899279 (patient_id)');
        $this->addSql('ALTER TABLE patient_referral DROP INDEX IDX_4482C5A5F2A1A1C7, ADD UNIQUE INDEX UNIQ_4482C5A5F2A1A1C7 (sending_provider_id)');
        $this->addSql('ALTER TABLE patient_referral DROP INDEX IDX_4482C5A5F37DAA25, ADD UNIQUE INDEX UNIQ_4482C5A5F37DAA25 (receiving_provider_id)');
        $this->addSql('ALTER TABLE patient_referral DROP INDEX IDX_4482C5A58AF128D4, ADD UNIQUE INDEX UNIQ_4482C5A58AF128D4 (sending_practicioner_id)');
        $this->addSql('ALTER TABLE patient_referral DROP INDEX IDX_4482C5A5A058F403, ADD UNIQUE INDEX UNIQ_4482C5A5A058F403 (receiving_practicioner_id)');
    }
}
