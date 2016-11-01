<?php

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class SeasonalController extends FOSRestController
{
    public function getSeasonalsAction(Request $request)
    {
        $translator = $this->container->get('translator');
        $seasonalItems = $this->getDoctrine()->getRepository('AppBundle:Seasonal')->findBy([
            'month' => date('n')
        ]);

        $items = [];
        $tempItems = [];
        foreach ($seasonalItems as $seasonalItem) {
            $url = $request->getSchemeAndHttpHost() . '/images/logo-gray.png';
            if ($seasonalItem->getItem()->getImage() !== null) {
                $url = $request->getSchemeAndHttpHost() . '/images/items/' . $seasonalItem->getItem()->getImage();
            }

            $tempItems[$translator->trans($seasonalItem->getItem()->getType()->getPluralName())][] = [
                'id' => $seasonalItem->getItem()->getId(),
                'name' => $seasonalItem->getItem()->getName(),
                'image' => $url
            ];
        }
        $i = 0;
        foreach ($tempItems as $type => $subItems) {
            $items[$i]['name'] = $type;
            $items[$i]['items'] = $subItems;
            $i++;
        }

        return $items;
    }

}