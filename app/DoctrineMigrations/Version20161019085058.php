<?php

namespace Application\Migrations;

use AppBundle\Entity\Item;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161019085058 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE item ADD COLUMN type INTEGER NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_1F1B251E5E237E06');
        $this->addSql('CREATE TEMPORARY TABLE __temp__item AS SELECT id, name, image FROM item');
        $this->addSql('DROP TABLE item');
        $this->addSql('CREATE TABLE item (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO item (id, name, image) SELECT id, name, image FROM __temp__item');
        $this->addSql('DROP TABLE __temp__item');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251E5E237E06 ON item (name)');
    }

    public function postUp(Schema $schema)
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $veggies = [
            'πορτοκάλια' => Item::TYPE_FRUIT, 'λεμόνια' => Item::TYPE_VEGETABLE, 'μανταρίνια' => Item::TYPE_FRUIT,
            'γκρέιπ φρουτ' => Item::TYPE_FRUIT, 'μήλα' => Item::TYPE_FRUIT, 'άνηθος' => Item::TYPE_HERB,
            'μαϊντανός' => Item::TYPE_HERB, 'μπρόκολο' => Item::TYPE_VEGETABLE, 'παντζάρια' => Item::TYPE_VEGETABLE,
            'πράσα' => Item::TYPE_VEGETABLE, 'ραδίκια' => Item::TYPE_VEGETABLE, 'ράπα' => Item::TYPE_VEGETABLE,
            'λαχανάκια Βρυξελλών' => Item::TYPE_VEGETABLE, 'λάχανο' => Item::TYPE_VEGETABLE, 'σέλινο' => Item::TYPE_HERB,
            'σέσκουλο' => Item::TYPE_VEGETABLE, 'σπανάκι' => Item::TYPE_VEGETABLE, 'φινόκιο' => Item::TYPE_VEGETABLE,
            'αβοκάντο' => Item::TYPE_FRUIT, 'κουνουπίδι' => Item::TYPE_VEGETABLE, 'μαρούλι' => Item::TYPE_VEGETABLE,
            'καρότα' => Item::TYPE_VEGETABLE, 'σταφίδες' => Item::TYPE_FRUIT, 'καρύδια' => Item::TYPE_NUT, 'αμύγδαλα' => Item::TYPE_NUT,
            'κάστανα' => Item::TYPE_VEGETABLE, 'αγκινάρες' => Item::TYPE_VEGETABLE, 'αντίδια' => Item::TYPE_VEGETABLE, 'σπαράγγια' => Item::TYPE_VEGETABLE,
            'αρακάς' => Item::TYPE_VEGETABLE, 'ραπανάκια' => Item::TYPE_VEGETABLE, 'κολοκυθάκια' => Item::TYPE_VEGETABLE, 'κουκιά' => Item::TYPE_VEGETABLE,
            'κρεμμυδάκι' => Item::TYPE_HERB, 'φράουλες' => Item::TYPE_FRUIT, 'κεράσια' => Item::TYPE_FRUIT, 'βερίκοκα' => Item::TYPE_FRUIT,
            'μούσμουλα' => Item::TYPE_FRUIT, 'βλίτα' => Item::TYPE_VEGETABLE, 'φασολάκια' => Item::TYPE_VEGETABLE, 'μάραθος' => Item::TYPE_HERB,
            'πιπεριές' => Item::TYPE_VEGETABLE, 'καρπούζι' => Item::TYPE_FRUIT, 'βύσσινο' => Item::TYPE_FRUIT, 'πεπόνι' => Item::TYPE_FRUIT,
            'αχλάδια' => Item::TYPE_FRUIT, 'ντομάτες' => Item::TYPE_VEGETABLE, 'αγγούρια' => Item::TYPE_VEGETABLE, 'κρεμμύδια' => Item::TYPE_VEGETABLE,
            'σκόρδα' => Item::TYPE_VEGETABLE, 'πατάτες' => Item::TYPE_VEGETABLE, 'μελιτζάνες' => Item::TYPE_VEGETABLE, 'σύκα' => Item::TYPE_FRUIT,
            'ροδάκινα' => Item::TYPE_FRUIT, 'κολοκύθα' => Item::TYPE_VEGETABLE, 'μπάμιες' => Item::TYPE_VEGETABLE, 'δαμάσκηνα' => Item::TYPE_FRUIT,
            'φυστίκια Αιγίνης' => Item::TYPE_NUT, 'ακτινίδια' => Item::TYPE_FRUIT, 'κυδώνια' => Item::TYPE_FRUIT, 'λωτούς' => Item::TYPE_FRUIT,
            'ρόδια' => Item::TYPE_FRUIT, 'σταφύλια' => Item::TYPE_FRUIT, 'φουντούκια' => Item::TYPE_NUT, 'μπανάνες' => Item::TYPE_FRUIT
        ];

        foreach ($veggies as $name => $type) {
            /** @var Item $item */
            $item = $entityManager->getRepository('AppBundle:Item')->findOneBy(['name' => $name]);
            if ($item) {
                $item->setType($type);
                $entityManager->persist($item);
            }
        }
        $entityManager->flush();
    }
}
