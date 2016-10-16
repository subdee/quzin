<?php

namespace AppBundle\Controller;

use FeedIo\Feed\ItemInterface;
use Lcn\WeatherForecastBundle\Service\Forecast;
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
        $forecastForDay = $forecast->getForCurrentHour(52.067448, 4.403103);

        $feedIo = $this->container->get('feedio');
        $feed = $feedIo->read('http://feeds.feedburner.com/sintages/kirios-piato', null,
            new \DateTime('1900-01-01'))->getFeed();
        $items = [];
        foreach ($feed as $item) {
            $items[] = $item;
        }

        /** @var ItemInterface $recipe */
        $idx = mt_rand(0, count($items) - 1);
        $recipe = $items[$idx];

        return $this->render('default/index.html.twig', [
            'forecast' => $forecastForDay,
            'recipe' => $recipe
        ]);
    }
}
