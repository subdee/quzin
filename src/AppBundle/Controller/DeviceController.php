<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Device;
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
    public function putDevicesAction(Request $request)
    {
        $id = $request->request->get('id');
        if (!$id) {
            throw new BadRequestHttpException('ID is required');
        }

        $registrationId = $request->request->get('registrationId');
        if (!$id) {
            throw new BadRequestHttpException('Registration ID is required');
        }

        $device = $this->getDoctrine()->getRepository('AppBundle:Device')->findOneBy(['deviceId' => $id]);

        $device = $device ?: new Device();
        $device->setRegistrationId($registrationId);

        $entityMgr = $this->getDoctrine()->getManager();
        $entityMgr->persist($device);
        $entityMgr->flush();

        return ['success' => true];
    }

}