<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Item
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Serializer\Expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Serializer\Expose
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer", nullable=true)
     */
    private $typeId;

    /**
     * @var ItemType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ItemType", inversedBy="items")
     * @JoinColumn(name="type_id", referencedColumnName="id")
     *
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
     * @return ItemType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ItemType $type
     */
    public function setType(ItemType $type)
    {
        $this->type = $type;
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

    /**
     * @return int
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @param int $typeId
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
    }
}

