<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200408120956 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE box DROP FOREIGN KEY FK_8A9483AD663094A');
        $this->addSql('DROP INDEX IDX_8A9483AD663094A ON box');
        $this->addSql('ALTER TABLE box ADD pages_id INT DEFAULT NULL, DROP comic_id');
        $this->addSql('ALTER TABLE box ADD CONSTRAINT FK_8A9483A401ADD27 FOREIGN KEY (pages_id) REFERENCES page (id)');
        $this->addSql('CREATE INDEX IDX_8A9483A401ADD27 ON box (pages_id)');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620D8177B3F');
        $this->addSql('DROP INDEX IDX_140AB620D8177B3F ON page');
        $this->addSql('ALTER TABLE page DROP box_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE box DROP FOREIGN KEY FK_8A9483A401ADD27');
        $this->addSql('DROP INDEX IDX_8A9483A401ADD27 ON box');
        $this->addSql('ALTER TABLE box ADD comic_id INT NOT NULL, DROP pages_id');
        $this->addSql('ALTER TABLE box ADD CONSTRAINT FK_8A9483AD663094A FOREIGN KEY (comic_id) REFERENCES comic (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8A9483AD663094A ON box (comic_id)');
        $this->addSql('ALTER TABLE page ADD box_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620D8177B3F FOREIGN KEY (box_id) REFERENCES box (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_140AB620D8177B3F ON page (box_id)');
    }
}
