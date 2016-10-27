<?php

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;

class ShoppingListController extends FOSRestController
{
    /**
     * @Get("/shoppingList")
     */
    public function getShoppingListAction()
    {
        $data = $this->getDoctrine()->getRepository('AppBundle:ShoppingList')->findActiveItemsOrdered();
        $view = $this->view($data, 200)
            ->setTemplate('AppBundle:ShoppingList:getShoppingList.html.twig')
            ->setTemplateVar('shoppingList');

        return $this->handleView($view);
    }

}