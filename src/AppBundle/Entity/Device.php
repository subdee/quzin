<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="device")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeviceRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Device
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
     * @var string
     *
     * @ORM\Column(name="device_id", type="string", length=255, unique=true)
     */
    private $deviceId;

    /**
     * @var string
     *
     * @ORM\Column(name="registration_id", type="string", length=255)
     */
    private $registrationId;

    /**
     * @var int
     *
     * @ORM\Column(name="added_on", type="integer")
     */
    private $updatedOn;


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
        $this->setUpdatedOn(time());
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateSavedOn()
    {
        $this->setUpdatedOn(time());
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
     * @param integer $updatedOn
     *
     * @return Device
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get addedOn
     *
     * @return int
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @return string
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * @param string $deviceId
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;
    }
}

