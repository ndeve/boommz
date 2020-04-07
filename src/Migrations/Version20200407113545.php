<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407113545 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, public TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona_user (persona_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_42F0DF4BF5F88DB9 (persona_id), INDEX IDX_42F0DF4BA76ED395 (user_id), PRIMARY KEY(persona_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bubble (id INT AUTO_INCREMENT NOT NULL, box_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, style VARCHAR(5) DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_EB20F1F7D8177B3F (box_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE persona_user ADD CONSTRAINT FK_42F0DF4BF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE persona_user ADD CONSTRAINT FK_42F0DF4BA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bubble ADD CONSTRAINT FK_EB20F1F7D8177B3F FOREIGN KEY (box_id) REFERENCES box (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE persona_user DROP FOREIGN KEY FK_42F0DF4BF5F88DB9');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE persona_user');
        $this->addSql('DROP TABLE bubble');
    }
}
