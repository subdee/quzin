<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
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
        return $this->render('AppBundle:default:index.html.twig', [
            'current' => $this->getWeather()['current'],
            'forecast' => $this->getWeather()['forecast'],
            'recipe' => $this->getRecipe(),
            'seasonalItems' => $this->getSeasonalItems()
        ]);
    }

    /**
     * @Route("/weather", name="weather")
     */
    public function weatherAction()
    {
        return $this->render('AppBundle:default:weather.html.twig', [
            'current' => $this->getWeather()['current'],
            'forecast' => $this->getWeather()['forecast'],
        ]);
    }

    /**
     * @Route("/dailyRecipe", name="dailyRecipe")
     */
    public function dailyRecipeAction()
    {
        return $this->render('AppBundle:default:dayrecipe.html.twig', [
            'recipe' => $this->getRecipe(),
        ]);
    }

    /**
     * @Route("/seasonal", name="seasonal")
     */
    public function seasonalAction()
    {
        return $this->render('AppBundle:default:seasonal.html.twig', [
            'seasonalItems' => $this->getSeasonalItems()
        ]);
    }

    private function getWeather()
    {
        /** @var Forecast $forecast */
        $forecast = $this->container->get('lcn.weather_forecast');
        $forecastCurrent = $forecast->getForCurrentHour(
            $this->getParameter('location.latitude'),
            $this->getParameter('location.longitude')
        );
        $forecastForDay = $forecast->getForToday(
            $this->getParameter('location.latitude'),
            $this->getParameter('location.longitude')
        );

        return ['current' => $forecastCurrent, 'forecast' => $forecastForDay];
    }

    private function getRecipe()
    {
        $recipeFinder = $this->container->get('recipefinder');
        return $recipeFinder->getRandom();
    }

    private function getSeasonalItems()
    {
        $seasonalItems = $this->getDoctrine()->getRepository('AppBundle:Seasonal')->findBy([
            'month' => date('n')
        ]);
        $items = [Item::TYPE_VEGETABLE => [], Item::TYPE_FRUIT => [], Item::TYPE_HERB => [], Item::TYPE_NUT => []];
        foreach ($seasonalItems as $seasonalItem) {
            $items[$seasonalItem->getItem()->getType()][] = $seasonalItem->getItem()->getName();
        }
        foreach ($items as $type => $subItems) {
            asort($subItems);
            $items[$type] = $subItems;
        }

        return $items;
    }
}
