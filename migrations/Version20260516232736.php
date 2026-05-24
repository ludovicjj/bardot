<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260516232736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY `FK_5A8600B0C4663E4`');
        $this->addSql('DROP INDEX IDX_5A8600B0C4663E4 ON `option`');
        $this->addSql('ALTER TABLE `option` DROP page_id');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY `FK_C4E0A61FC4663E4`');
        $this->addSql('DROP INDEX IDX_C4E0A61FC4663E4 ON team');
        $this->addSql('ALTER TABLE team DROP page_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `option` ADD page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT `FK_5A8600B0C4663E4` FOREIGN KEY (page_id) REFERENCES page (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5A8600B0C4663E4 ON `option` (page_id)');
        $this->addSql('ALTER TABLE team ADD page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT `FK_C4E0A61FC4663E4` FOREIGN KEY (page_id) REFERENCES page (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C4E0A61FC4663E4 ON team (page_id)');
    }
}
