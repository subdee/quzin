<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Device;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use Lcn\WeatherForecastBundle\Service\Forecast;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class WeatherController extends FOSRestController
{
    /**
     * @Get("/weather")
     */
    public function getWeatherAction()
    {
        /** @var Forecast $forecastService */
        $forecastService = $this->container->get('lcn.weather_forecast');
        $current = $forecastService->getForCurrentHour(
            $this->getParameter('location.latitude'),
            $this->getParameter('location.longitude')
        );
        $forecast = $forecastService->getForToday(
            $this->getParameter('location.latitude'),
            $this->getParameter('location.longitude')
        );

        return [
            'iconType' => $forecast->getIcon(),
            'forecast' => $forecast->getSummary(),
            'temperature' => $current->getTemperature()
        ];
    }

}