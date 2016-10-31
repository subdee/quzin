<?php

namespace AppBundle\Controller;


use AppBundle\Entity\ShoppingList;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ShoppingListController extends FOSRestController
{
    /**
     * @Get("/shoppingList")
     */
    public function getShoppingListAction(Request $request)
    {
        $translator = $this->container->get('translator');
        $items = $this->getDoctrine()->getRepository('AppBundle:ShoppingList')->findActiveItemsOrdered();

        $data = [];
        foreach ($items as $item) {
            $url = $request->getSchemeAndHttpHost() . '/images/logo-gray.png';
            if ($item->getItem()->getImage() !== null) {
                $url = $request->getSchemeAndHttpHost() . '/images/items/' . $item->getItem()->getImage();
            }
            $data[] = [
                'id' => $item->getId(),
                'name' => $translator->trans($item->getItem()->getName()),
                'image' => $url
            ];
        }

        return $data;
    }

    /**
     * @Post("/shoppingList/{id}")
     */
    public function postShoppingListAction($id)
    {
        $item = $this->getDoctrine()->getRepository('AppBundle:Item')->findOneBy(['id' => $id]);
        if (!$item) {
            return ['success' => false];
        }
        $shoppingList = $this->getDoctrine()->getRepository('AppBundle:ShoppingList')->findItem($item);
        if ($shoppingList !== null) {
            return ['success' => true];
        }

        $entManager = $this->getDoctrine()->getManager();

        $shoppingList = new ShoppingList();
        $shoppingList->setItem($item);
        $shoppingList->setIsBought(false);
        $entManager->persist($shoppingList);
        $entManager->flush();

        return ['success' => true];
    }

    /**
     * @Delete("/shoppingList/{id}")
     */
    public function deleteShoppingListAction($id)
    {
        $shoppingItem = $this->getDoctrine()->getRepository('AppBundle:ShoppingList')->findOneBy(['id' => $id]);
        if (!$shoppingItem) {
            return ['success' => false];
        }

        $entManager = $this->getDoctrine()->getManager();

        $shoppingItem->setIsBought(true);
        $entManager->persist($shoppingItem);
        $entManager->flush();

        return ['success' => true];
    }
}