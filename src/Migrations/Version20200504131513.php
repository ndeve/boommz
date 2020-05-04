<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200504131513 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comic ADD id_contest_id INT DEFAULT NULL, ADD challenge_description LONGTEXT DEFAULT NULL, ADD challenged TINYINT(1) NOT NULL, ADD contest_description LONGTEXT DEFAULT NULL, ADD contest TINYINT(1) NOT NULL, ADD end_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE comic ADD CONSTRAINT FK_5B7EA5AAB95A7009 FOREIGN KEY (id_contest_id) REFERENCES comic (id)');
        $this->addSql('CREATE INDEX IDX_5B7EA5AAB95A7009 ON comic (id_contest_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comic DROP FOREIGN KEY FK_5B7EA5AAB95A7009');
        $this->addSql('DROP INDEX IDX_5B7EA5AAB95A7009 ON comic');
        $this->addSql('ALTER TABLE comic DROP id_contest_id, DROP challenge_description, DROP challenged, DROP contest_description, DROP contest, DROP end_date');
    }
}
