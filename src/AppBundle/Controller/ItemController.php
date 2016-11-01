<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Item;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    /**
     * @param Request $request
     * @return array
     *
     * @RequestParam(
     *     name="name",
     *     nullable=false
     * )
     */
    public function postItemsAction(Request $request)
    {
        $name = $request->request->get('name');
        if (!$name) {
            throw new BadRequestHttpException('Name is required');
        }

        $unknownType = $this->getDoctrine()->getRepository('AppBundle:ItemType')->findUnknownType();
        if (!$unknownType) {
            throw new HttpException(500, 'Error finding unknown type');
        }

        $item = new Item();
        $item->setName($name);
        $item->setType($unknownType);

        $entityMgr = $this->getDoctrine()->getManager();
        $entityMgr->persist($item);
        $entityMgr->flush();

        return ['success' => true, 'id' => $item->getId()];
    }

}