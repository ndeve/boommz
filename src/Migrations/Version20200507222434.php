<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200507222434 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69B49CA8337');
        $this->addSql('DROP INDEX IDX_51E5B69B49CA8337 ON persona');
        $this->addSql('ALTER TABLE persona CHANGE friends_id persona_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('CREATE INDEX IDX_51E5B69BF5F88DB9 ON persona (persona_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69BF5F88DB9');
        $this->addSql('DROP INDEX IDX_51E5B69BF5F88DB9 ON persona');
        $this->addSql('ALTER TABLE persona CHANGE persona_id friends_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69B49CA8337 FOREIGN KEY (friends_id) REFERENCES persona (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_51E5B69B49CA8337 ON persona (friends_id)');
    }
}
