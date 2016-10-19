<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seasonal
 *
 * @ORM\Table(name="seasonal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SeasonalRepository")
 */
class Seasonal
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
     * @ORM\Column(name="month", type="integer")
     */
    private $month;

    /**
     * @var int
     *
     * @ORM\Column(name="item_id", type="integer")
     */
    private $itemId;

    /**
     * @var Item
     *
     * @ORM\OneToOne(targetEntity="Item", inversedBy="seasonal", fetch="EAGER")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $item;


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
     * Set month
     *
     * @param integer $month
     *
     * @return Seasonal
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set itemId
     *
     * @param integer $itemId
     *
     * @return Seasonal
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

