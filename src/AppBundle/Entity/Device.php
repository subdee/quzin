<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="device")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeviceRepository")
 */
class Device
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\HasLifecycleCallbacks
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="registration_id", type="string", length=255, unique=true)
     */
    private $registrationId;

    /**
     * @var int
     *
     * @ORM\Column(name="added_on", type="integer")
     */
    private $addedOn;


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
     * Sets saved on automatically
     */
    public function __construct()
    {
        $this->setAddedOn(time());
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateSavedOn()
    {
        $this->setAddedOn(time());
    }

    /**
     * Set registrationId
     *
     * @param string $registrationId
     *
     * @return Device
     */
    public function setRegistrationId($registrationId)
    {
        $this->registrationId = $registrationId;

        return $this;
    }

    /**
     * Get registrationId
     *
     * @return string
     */
    public function getRegistrationId()
    {
        return $this->registrationId;
    }

    /**
     * Set addedOn
     *
     * @param integer $addedOn
     *
     * @return Device
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
}

