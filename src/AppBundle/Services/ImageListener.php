<?php

namespace AppBundle\Services;

use AppBundle\Entity\Item;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Imagick;
use Symfony\Component\HttpKernel\KernelInterface;

class ImageListener
{
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!($entity instanceof Item)) {
            return;
        }

        $this->upload($entity);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!($entity instanceof Item)) {
            return;
        }

        $this->upload($entity);
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     * @param Item $entity
     */
    public function upload(Item $entity)
    {
        if (null === $entity->getImageFile()) {
            return;
        }

        $dir = $this->kernel->getRootDir() . '/../web/images/items';
        $name = $entity->getName() . '.' . $entity->getImageFile()->getClientOriginalExtension();

        $entity->getImageFile()->move($dir, $name);

        $imagick = new Imagick($dir . '/' . $name);
        $imagick->resizeImage(32, 32, Imagick::FILTER_LANCZOS2, 1);
        $imagick->writeImage($dir . '/' . $name);

        $entity->setImage($name);

        $entity->setImageFile(null);
    }
}