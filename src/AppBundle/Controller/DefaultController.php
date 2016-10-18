<?php

namespace AppBundle\Controller;

use Lcn\WeatherForecastBundle\Service\Forecast;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        /** @var Forecast $forecast */
        $forecast = $this->container->get('lcn.weather_forecast');
        $forecastCurrent = $forecast->getForCurrentHour(52.067448, 4.403103);
        $forecastForDay = $forecast->getForToday(52.067448, 4.403103);

        $recipeFinder = $this->container->get('recipefinder');
        $recipe = $recipeFinder->getRandom();

        return $this->render('default/index.html.twig', [
            'current' => $forecastCurrent,
            'forecast' => $forecastForDay,
            'recipe' => $recipe
        ]);
    }

    /**
     * @Route("/weather", name="weather")
     */
    public function weatherAction()
    {
        /** @var Forecast $forecast */
        $forecast = $this->container->get('lcn.weather_forecast');
        $forecastCurrent = $forecast->getForCurrentHour(52.067448, 4.403103);
        $forecastForDay = $forecast->getForToday(52.067448, 4.403103);

        return $this->render('default/weather.html.twig', [
            'current' => $forecastCurrent,
            'forecast' => $forecastForDay
        ]);
    }

    /**
     * @Route("/dailyRecipe", name="dailyRecipe")
     */
    public function dailyRecipeAction()
    {
        $recipeFinder = $this->container->get('recipefinder');
        $recipe = $recipeFinder->getRandom();

        return $this->render('default/dayrecipe.html.twig', [
            'recipe' => $recipe
        ]);
    }
}
