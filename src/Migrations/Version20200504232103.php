<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200504232103 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comic DROP FOREIGN KEY FK_5B7EA5AAB95A7009');
        $this->addSql('DROP INDEX IDX_5B7EA5AAB95A7009 ON comic');
        $this->addSql('ALTER TABLE comic CHANGE id_contest_id comic_contest_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comic ADD CONSTRAINT FK_5B7EA5AA1315D428 FOREIGN KEY (comic_contest_id) REFERENCES comic (id)');
        $this->addSql('CREATE INDEX IDX_5B7EA5AA1315D428 ON comic (comic_contest_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comic DROP FOREIGN KEY FK_5B7EA5AA1315D428');
        $this->addSql('DROP INDEX IDX_5B7EA5AA1315D428 ON comic');
        $this->addSql('ALTER TABLE comic CHANGE comic_contest_id id_contest_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comic ADD CONSTRAINT FK_5B7EA5AAB95A7009 FOREIGN KEY (id_contest_id) REFERENCES comic (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5B7EA5AAB95A7009 ON comic (id_contest_id)');
    }
}
