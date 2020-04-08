<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200408120351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620C2924924');
        $this->addSql('DROP INDEX IDX_140AB620C2924924 ON page');
        $this->addSql('ALTER TABLE page ADD box_id INT DEFAULT NULL, CHANGE cpomic_id comic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620D663094A FOREIGN KEY (comic_id) REFERENCES comic (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620D8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
        $this->addSql('CREATE INDEX IDX_140AB620D663094A ON page (comic_id)');
        $this->addSql('CREATE INDEX IDX_140AB620D8177B3F ON page (box_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620D663094A');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620D8177B3F');
        $this->addSql('DROP INDEX IDX_140AB620D663094A ON page');
        $this->addSql('DROP INDEX IDX_140AB620D8177B3F ON page');
        $this->addSql('ALTER TABLE page ADD cpomic_id INT DEFAULT NULL, DROP comic_id, DROP box_id');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620C2924924 FOREIGN KEY (cpomic_id) REFERENCES comic (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_140AB620C2924924 ON page (cpomic_id)');
    }
}
