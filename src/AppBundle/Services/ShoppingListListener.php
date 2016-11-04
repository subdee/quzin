<?php

namespace AppBundle\Services;


use AppBundle\Entity\ShoppingList;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Subdee\FcmBundle\Services\PushNotificationService;
use Symfony\Component\Translation\TranslatorInterface;

class ShoppingListListener
{
    /** @var PushNotificationService */
    private $pushService;
    /** @var TranslatorInterface */
    private $translator;

    public function __construct(PushNotificationService $pushService, TranslatorInterface $translator)
    {
        $this->pushService = $pushService;
        $this->translator = $translator;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ShoppingList) {
            return;
        }

        $entityManager = $args->getEntityManager();
        $result = $entityManager->getRepository('AppBundle:Device')
            ->createQueryBuilder('dev')
            ->select('dev.registrationId')
            ->getQuery()
            ->getScalarResult();
        $devices = array_map('current', $result);

        $this->pushService->sendNotification(
            $this->translator->trans('New shopping list item!'),
            $this->translator->trans('%itemName% has been added to your shopping list', [
                '%itemName%' => $entity->getItem()->getName()
            ]),
            $devices
        );
    }
}