<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * ShoppingList
 *
 * @ORM\Table(name="shopping_list")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShoppingListRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ShoppingList
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="item_id", type="integer")
     */
    private $itemId;

    /**
     * @var int
     *
     * @ORM\Column(name="added_on", type="integer")
     */
    private $addedOn;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_bought", type="boolean")
     */
    private $isBought;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item")
     * @JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $item;

    /**
     * Sets added on automatically
     */
    public function __construct()
    {
        $this->setAddedOn(time());
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateAddedOn()
    {
        $this->setAddedOn(time());
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set itemId
     *
     * @param integer $itemId
     *
     * @return ShoppingList
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * Get itemId
     *
     * @return int
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * Set addedOn
     *
     * @param integer $addedOn
     *
     * @return ShoppingList
     */
    public function setAddedOn($addedOn)
    {
        $this->addedOn = $addedOn;

        return $this;
    }

    /**
     * Get addedOn
     *
     * @return int
     */
    public function getAddedOn()
    {
        return $this->addedOn;
    }

    /**
     * Set isBought
     *
     * @param boolean $isBought
     *
     * @return ShoppingList
     */
    public function setIsBought($isBought)
    {
        $this->isBought = $isBought;

        return $this;
    }

    /**
     * Get isBought
     *
     * @return bool
     */
    public function getIsBought()
    {
        return $this->isBought;
    }

    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }
}

