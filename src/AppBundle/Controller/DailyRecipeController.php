<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Device;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use Lcn\WeatherForecastBundle\Service\Forecast;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DailyRecipeController extends FOSRestController
{
    /**
     * @Get("/dailyrecipe")
     */
    public function getDailyRecipeAction()
    {
        $recipeFinder = $this->container->get('recipefinder');
        $recipe = $recipeFinder->getRandom();
        $recipe['isFavorite'] = false;

        $saved = $this->getDoctrine()->getRepository('AppBundle:Recipe')->findOneBy([
            'title' => $recipe['title']
        ]);
        if ($saved !== null) {
            $recipe['isFavorite'] = true;
        }

        return $recipe;
    }

}