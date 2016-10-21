<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161021084357 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, title, link FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, link VARCHAR(255) NOT NULL, saved_on INTEGER NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO recipe (id, title, link) SELECT id, title, link FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__recipe AS SELECT id, title, link FROM recipe');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('CREATE TABLE recipe (id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO recipe (id, title, link) SELECT id, title, link FROM __temp__recipe');
        $this->addSql('DROP TABLE __temp__recipe');
    }
}
