<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Entity\Recipe;
use Lcn\WeatherForecastBundle\Service\Forecast;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
            'seasonalItems' => $this->getSeasonalItems(),
            'recipeCount' => $this->getRecipeCount()
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

    /**
     * @Route("/recipeList", name="recipeList")
     */
    public function recipeListAction()
    {
        return $this->render('AppBundle:default:recipelist.html.twig', [
            'recipeCount' => $this->getRecipeCount()
        ]);
    }

    /**
     * @Route("/saveRecipe", name="saveRecipe")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveRecipeAction(Request $request)
    {
        $title = $request->request->get('title');
        $link = $request->request->get('link');

        if ($title !== '' && $link !== '') {
            $recipe = new Recipe();
            $recipe->setTitle(urldecode($title));
            $recipe->setLink($link);

            $em = $this->getDoctrine()->getManager();
            $em->persist($recipe);
            $em->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }

    /**
     * @Route("/recipeListItems", name="recipeListItems")
     */
    public function recipeListItemsAction()
    {
        /** @var Recipe[] $recipes */
        $recipes = $this->getDoctrine()->getRepository('AppBundle:Recipe')->findBy([], ['savedOn' => 'desc']);

        return $this->render('@App/default/recipelistitems.html.twig', ['recipes' => $recipes]);
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

    private function getRecipeCount()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Recipe');
        $query = $repo->createQueryBuilder('recipe');
        $query->select('COUNT(recipe)');

        return $query->getQuery()->getSingleScalarResult();
    }
}
