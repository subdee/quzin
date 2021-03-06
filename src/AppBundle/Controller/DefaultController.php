<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\RecipeSearch;
use AppBundle\Entity\ShoppingList;
use Lcn\WeatherForecastBundle\Service\Forecast;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:default:index.html.twig');
    }

    /**
     * @Route("/weatherdate", name="weatherdate")
     */
    public function weatherDateAction()
    {
        $date = new \DateTime();

        $formatter = \IntlDateFormatter::create(
            $this->getParameter('locale'),
            \IntlDateFormatter::MEDIUM,
            \IntlDateFormatter::NONE
        );

        return new Response($formatter->format($date->getTimestamp()));
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

    /**
     * @Route("/shoppingList", name="shoppingList")
     */
    public function shoppingListAction()
    {
        return $this->render('@App/default/shoppinglist.html.twig', ['shoppingListCount' => $this->getShoppingListCount()]);
    }

    /**
     * @Route("/shoppingListItems", name="shoppingListItems")
     */
    public function shoppingListItemsAction()
    {
        /** @var ShoppingList[] $shoppingList */
        $shoppingList = $this->getDoctrine()->getRepository('AppBundle:ShoppingList')->findActiveItemsOrdered();

        return $this->render('@App/default/shoppinglistitems.html.twig', ['shoppingList' => $shoppingList]);
    }

    /**
     * @Route("/itemsList", name="itemsList")
     */
    public function itemsListAction()
    {
        /** @var Item[] $items */
        $types = $this->getDoctrine()->getRepository('AppBundle:Item')->findAllGroupedByType();
        $list = $this->getDoctrine()->getRepository('AppBundle:ShoppingList')->findActiveItemsOrdered();
        $shoppingList = [];
        foreach ($list as $shoppingItem) {
            $shoppingList[] = $shoppingItem->getItem()->getId();
        }

        return $this->render('@App/default/shoppinglistadd.html.twig', ['types' => $types, 'shoppingList' => $shoppingList]);
    }

    /**
     * @Route("/addItemToShoppingList/{id}", name="addItemToShoppingList", options={"expose"=true})
     */
    public function addItemToShoppingListAction($id)
    {
        $item = $this->getDoctrine()->getRepository('AppBundle:Item')->findOneBy(['id' => $id]);
        if (!$item) {
            return new JsonResponse(['success' => false]);
        }

        $shoppingList = new ShoppingList();
        $shoppingList->setItem($item);
        $shoppingList->setIsBought(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($shoppingList);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    /**
     * @Route("/recipeSearch", name="recipeSearch")
     * @return Response
     */
    public function recipeSearchAction()
    {
        return $this->render('@App/default/recipesearch.html.twig');
    }

    /**
     * @Route("/searchRecipe", name="searchRecipe")
     * @param Request $request
     * @return Response
     */
    public function searchRecipeAction(Request $request)
    {
        $results = [];
        if ($request->request->has('query')) {
            $query = $request->request->get('query');

            $searchService = $this->get('fcm_search');
            $results = $searchService->search($query);
        }

        return new JsonResponse([
            'success' => true,
            'content' => $this->renderView('@App/default/recipesearchresults.html.twig', ['results' => $results])
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
        $items = [];
        foreach ($seasonalItems as $seasonalItem) {
            $items[$seasonalItem->getItem()->getType()->getPluralName()][] = $seasonalItem->getItem()->getName();
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

    private function getShoppingListCount()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:ShoppingList');
        $query = $repo->createQueryBuilder('shopping_list')
            ->select('COUNT(shopping_list)')
            ->where('shopping_list.isBought = 0');

        return $query->getQuery()->getSingleScalarResult();
    }
}
