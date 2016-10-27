<?php

namespace Application\Migrations;

use AppBundle\Entity\ItemType;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161027131842 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE item_type (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, plural_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_44EE13D25E237E06 ON item_type (name)');
        $this->addSql('DROP INDEX UNIQ_1F1B251E5E237E06');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item AS SELECT id, name, image, updated_on, type FROM item');
        $this->addSql('DROP TABLE item');
        $this->addSql('CREATE TABLE item (id INTEGER NOT NULL, type_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, image VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated_on INTEGER DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO item (id, name, image, updated_on, type_id) SELECT id, name, image, updated_on, type FROM __temp__item');
        $this->addSql('DROP TABLE __temp__item');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251E5E237E06 ON item (name)');
        $this->addSql('CREATE INDEX IDX_1F1B251EC54C8C93 ON item (type_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE item_type');
        $this->addSql('DROP INDEX UNIQ_1F1B251E5E237E06');
        $this->addSql('DROP INDEX IDX_1F1B251EC54C8C93');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item AS SELECT id, name, image, updated_on, type_id FROM item');
        $this->addSql('DROP TABLE item');
        $this->addSql('CREATE TABLE item (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_on INTEGER DEFAULT NULL, type INTEGER NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO item (id, name, image, updated_on, type) SELECT id, name, image, updated_on, type_id FROM __temp__item');
        $this->addSql('DROP TABLE __temp__item');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251E5E237E06 ON item (name)');
    }

    public function postUp(Schema $schema)
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $items = [
            1 => 'Vegetable',
            2 => 'Fruit',
            3 => 'Herb',
            4 => 'Nut'
        ];
        $pluralItems = [
            1 => 'Vegetables',
            2 => 'Fruits',
            3 => 'Herbs',
            4 => 'Nuts'
        ];

        foreach ($items as $id => $type) {
            $itemType = new ItemType();
            $itemType->setName($type);
            $itemType->setPluralName($pluralItems[$id]);
            $entityManager->persist($itemType);
            $entityManager->flush();
        }
    }
}
