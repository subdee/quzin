<?php

namespace Application\Migrations;

use AppBundle\Entity\Item;
use AppBundle\Entity\Seasonal;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161019070257 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE item (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251E5E237E06 ON item (name)');
        $this->addSql('CREATE TABLE seasonal (id INTEGER NOT NULL, item_id INTEGER NOT NULL, month INTEGER NOT NULL, PRIMARY KEY(id))');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE seasonal');
    }

    public function postUp(Schema $schema)
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $veggies = [
            'πορτοκάλια' => [1, 2, 3, 4, 5, 6, 10, 11, 12], 'λεμόνια' => [1, 2, 3, 4, 9, 10, 11, 12], 'μανταρίνια' => [1, 2, 3, 4, 10, 11, 12],
            'γκρέιπ φρουτ' => [1, 2, 3, 8, 11, 12], 'μήλα' => [1, 7, 8, 9, 10, 11, 12], 'άνηθος' => [1, 2, 3, 4, 7, 8, 10, 11, 12],
            'μαϊντανός' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'μπρόκολο' => [1, 2, 3, 4, 9, 10, 11, 12], 'παντζάρια' => [1, 2, 11, 12],
            'πράσα' => [1, 2, 3, 4, 10, 11, 12], 'ραδίκια' => [1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12], 'ράπα' => [1, 2, 3, 4, 9, 10, 11, 12],
            'λαχανάκια Βρυξελλών' => [1, 2, 3, 4, 11, 12], 'λάχανο' => [1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12], 'σέλινο' => [1, 2, 3, 5, 6, 7, 8, 9, 10, 11, 12],
            'σέσκουλο' => [1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12], 'σπανάκι' => [1, 2, 3, 4, 5, 6, 10, 11, 12], 'φινόκιο' => [1, 2, 3, 4, 5, 6, 9, 10, 11, 12],
            'αβοκάντο' => [1, 2, 3, 4, 5, 6, 11, 12], 'κουνουπίδι' => [1, 2, 3, 4, 9, 10, 11, 12], 'μαρούλι' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'καρότα' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], 'σταφίδες' => [1, 2, 10, 11, 12], 'καρύδια' => [1, 2, 9, 10], 'αμύγδαλα' => [1, 2, 10, 11, 12],
            'κάστανα' => [1, 2, 11, 12], 'αγκινάρες' => [2, 3, 4, 5, 11, 12], 'αντίδια' => [2, 3, 7, 8, 9, 10, 11, 12], 'σπαράγγια' => [3, 4, 5, 6],
            'αρακάς' => [3, 4, 5, 6, 7], 'ραπανάκια' => [3, 5, 6, 7, 8, 9, 10], 'κολοκυθάκια' => [4, 5, 6, 7, 8, 9, 10], 'κουκιά' => [4, 5, 6],
            'κρεμμυδάκι' => [2, 3, 4, 11, 12], 'φράουλες' => [4, 5, 6, 7, 8], 'κεράσια' => [5, 6, 7], 'βερίκοκα' => [5, 6, 7],
            'μούσμουλα' => [5], 'βλίτα' => [5, 6, 7, 8, 9, 10], 'φασολάκια' => [4, 5, 6, 7, 8, 9, 10, 11], 'μάραθος' => [3, 7, 8, 9, 10],
            'πιπεριές' => [5, 6, 7, 8, 9, 10], 'καρπούζι' => [6, 7, 8, 9], 'βύσσινο' => [6, 7, 8], 'πεπόνι' => [5, 6, 7, 8, 9],
            'αχλάδια' => [7, 8, 9, 10, 11, 12], 'ντομάτες' => [5, 6, 7, 8, 9, 10], 'αγγούρια' => [5, 6, 7, 8, 9, 10], 'κρεμμύδια' => [4, 5, 6, 7, 8, 9],
            'σκόρδα' => [5, 6, 7, 8], 'πατάτες' => [6, 7, 8, 9, 11, 12], 'μελιτζάνες' => [5, 6, 7, 8, 9, 10], 'σύκα' => [7, 8, 9],
            'ροδάκινα' => [5, 6, 7, 8, 9, 10], 'κολοκύθα' => [7, 8, 9, 10, 11], 'μπάμιες' => [7, 8, 9], 'δαμάσκηνα' => [7, 8, 9],
            'φυστίκια Αιγίνης' => [9], 'ακτινίδια' => [9, 10, 11], 'κυδώνια' => [9, 10], 'λωτούς' => [9, 10, 11, 12],
            'ρόδια' => [9, 10, 11], 'σταφύλια' => [7, 8, 9, 10, 11], 'φουντούκια' => [11, 12], 'μπανάνες' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
        ];

        foreach ($veggies as $name => $months) {
            $item = new Item();
            $item->setName($name);
            $entityManager->persist($item);

            foreach ($months as $month) {
                $seasonal = new Seasonal();
                $seasonal->setItem($item);
                $seasonal->setMonth($month);
                $entityManager->persist($seasonal);
            }

            $entityManager->flush();
        }
    }
}
