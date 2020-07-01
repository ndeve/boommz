<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200701125818 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE persona CHANGE color color INT DEFAULT NULL, CHANGE sex sex VARCHAR(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE bubble ADD CONSTRAINT FK_EB20F1F7D8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
        $this->addSql('ALTER TABLE bubble ADD CONSTRAINT FK_EB20F1F7F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE bubble ADD CONSTRAINT FK_EB20F1F7F675F31B FOREIGN KEY (author_id) REFERENCES fos_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bubble DROP FOREIGN KEY FK_EB20F1F7D8177B3F');
        $this->addSql('ALTER TABLE bubble DROP FOREIGN KEY FK_EB20F1F7F5F88DB9');
        $this->addSql('ALTER TABLE bubble DROP FOREIGN KEY FK_EB20F1F7F675F31B');
        $this->addSql('ALTER TABLE persona CHANGE color color INT NOT NULL, CHANGE sex sex VARCHAR(1) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
