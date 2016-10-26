<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Item
{
    const TYPE_VEGETABLE = 1;
    const TYPE_FRUIT = 2;
    const TYPE_HERB = 3;
    const TYPE_NUT = 4;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="updated_on", type="integer", nullable=true)
     */
    private $updatedOn;

    /**
     * @var Seasonal
     *
     * @ORM\OneToOne(targetEntity="Seasonal", mappedBy="item")
     */
    private $seasonal;

    /**
     * @var UploadedFile
     */
    private $imageFile;

    public function typeText()
    {
        switch ($this->type) {
            case Item::TYPE_VEGETABLE:
                return 'Vegetable';
            case Item::TYPE_FRUIT:
                return 'Fruit';
            case Item::TYPE_HERB:
                return 'Herb';
            case Item::TYPE_NUT:
                return 'Nut';
            default:
                return 'Unknown';
        }
    }

    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire
     */
    public function refreshUpdated()
    {
        $this->updatedOn = time();
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
     * Set name
     *
     * @param string $name
     *
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Item
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return Seasonal
     */
    public function getSeasonal()
    {
        return $this->seasonal;
    }

    /**
     * @param Seasonal $seasonal
     */
    public function setSeasonal($seasonal)
    {
        $this->seasonal = $seasonal;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return ShoppingList
     */
    public function getShoppingList()
    {
        return $this->shoppingList;
    }

    /**
     * @param ShoppingList $shoppingList
     */
    public function setShoppingList($shoppingList)
    {
        $this->shoppingList = $shoppingList;
    }

    /**
     * @return int
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param int $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     * @return UploadedFile
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param UploadedFile|null $imageFile
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
    }
}

