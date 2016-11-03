<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Device;
use AppBundle\Entity\Item;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DeviceController extends FOSRestController
{
    /**
     * @param Request $request
     * @return array
     *
     * @RequestParam(
     *     name="id",
     *     nullable=false
     * )
     */
    public function postItemsAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!$id) {
            throw new BadRequestHttpException('ID is required');
        }

        $device = $this->getDoctrine()->getRepository('AppBundle:Device')->findOneBy(['registration_id' => $id]);
        if ($device) {
            throw new BadRequestHttpException('ID already exists');
        }

        $device = new Device();
        $device->setRegistrationId($id);

        $entityMgr = $this->getDoctrine()->getManager();
        $entityMgr->persist($device);
        $entityMgr->flush();

        return ['success' => true];
    }

}