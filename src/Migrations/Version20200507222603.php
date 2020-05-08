<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200507222603 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE persona_persona (persona_source INT NOT NULL, persona_target INT NOT NULL, INDEX IDX_5718E99114606A36 (persona_source), INDEX IDX_5718E991D853AB9 (persona_target), PRIMARY KEY(persona_source, persona_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE persona_persona ADD CONSTRAINT FK_5718E99114606A36 FOREIGN KEY (persona_source) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE persona_persona ADD CONSTRAINT FK_5718E991D853AB9 FOREIGN KEY (persona_target) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69BF5F88DB9');
        $this->addSql('DROP INDEX IDX_51E5B69BF5F88DB9 ON persona');
        $this->addSql('ALTER TABLE persona DROP persona_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE persona_persona');
        $this->addSql('ALTER TABLE persona ADD persona_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_51E5B69BF5F88DB9 ON persona (persona_id)');
    }
}
