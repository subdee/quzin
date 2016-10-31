<?php

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends FOSRestController
{
    public function getItemsAction(Request $request)
    {
        $translator = $this->container->get('translator');
        $items = $this->getDoctrine()->getRepository('AppBundle:Item')->findAll();

        $data = [];
        foreach ($items as $item) {
            $url = $request->getSchemeAndHttpHost() . '/images/logo-gray.png';
            if ($item->getImage() !== null) {
                $url = $request->getSchemeAndHttpHost() . '/images/items/' . $item->getImage();
            }
            $data[] = [
                'id' => $item->getId(),
                'name' => $translator->trans($item->getName()),
                'image' => $url
            ];
        }

        return $data;
    }

}