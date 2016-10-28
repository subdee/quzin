<?php

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Get;
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
                'name' => $translator->trans($item->getItem()->getName()),
                'image' => $url
            ];
        }

        return $data;
    }

}