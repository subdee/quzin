<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Item;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ItemTypeController extends FOSRestController
{

    /**
     * @Get("/itemTypes")
     *
     * @param Request $request
     * @return array
     */
    public function getItemTypesAction(Request $request)
    {
        $translator = $this->container->get('translator');
        $types = $this->getDoctrine()->getRepository('AppBundle:Item')->findAllGroupedByType();

        $data = [];
        foreach ($types as $type => $items) {
            $typeItems = [];
            foreach ($items as $item) {
                $url = $request->getSchemeAndHttpHost() . '/images/logo-gray.png';
                if ($item->getImage() !== null) {
                    $url = $request->getSchemeAndHttpHost() . '/images/items/' . $item->getImage();
                }
                $typeItems[] = [
                    'id' => $item->getId(),
                    'name' => $translator->trans($item->getName()),
                    'image' => $url
                ];
            }
            $data[] = [
                'name' => $translator->trans($type),
                'items' => $typeItems
            ];
        }

        return $data;
    }

}