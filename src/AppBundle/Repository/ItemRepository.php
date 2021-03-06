<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Item;

/**
 * ItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllGroupedByType()
    {
        $list = [];

        /** @var Item[] $items */
        $items = $this->findBy([], ['typeId' => 'asc']);
        foreach ($items as $item) {
            $list[$item->getType()->getPluralName()][] = $item;
        }

        return $list;
    }
}
