<?php

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;

class ShoppingListController extends FOSRestController
{
    public function getShoppingListAction()
    {
        $data = $this->getDoctrine()->getRepository('AppBundle:ShoppingList')->findActiveItemsOrdered();
        $view = $this->view($data, 200)
            ->setTemplate('AppBundle:ShoppingList:getShoppingList.html.twig')
            ->setTemplateVar('shoppingList');

        return $this->handleView($view);
    }

}