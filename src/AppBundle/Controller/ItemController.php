<?php

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;

class ItemController extends FOSRestController
{
    public function getItemsAction()
    {
        $data = $this->getDoctrine()->getRepository('AppBundle:Item')->findAll();
        $view = $this->view($data, 200)
            ->setTemplate('AppBundle:Items:getItems.html.twig')
            ->setTemplateVar('items');

        return $this->handleView($view);
    }

}