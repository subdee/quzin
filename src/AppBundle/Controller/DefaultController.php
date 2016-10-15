<?php

namespace AppBundle\Controller;

use Lcn\WeatherForecastBundle\Service\Forecast;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /** @var Forecast $forecast */
        $forecast = $this->container->get('lcn.weather_forecast');
        $forecastForDay = $forecast->getForToday(52.0674480,4.4031030);

        return $this->render('default/index.html.twig', [
            'forecast' => $forecastForDay,
        ]);
    }
}
