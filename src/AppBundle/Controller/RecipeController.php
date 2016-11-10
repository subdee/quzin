<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Item;
use AppBundle\Entity\Recipe;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RecipeController extends FOSRestController
{
    public function getRecipesAction()
    {
        $recipes = $this->getDoctrine()->getRepository('AppBundle:Recipe')->findBy([], ['savedOn' => 'desc']);

        $data = [];
        foreach ($recipes as $recipe) {
            $data[] = [
                'title' => $recipe->getTitle(),
                'link' => $recipe->getLink(),
                'savedOn' => $recipe->getSavedOn(),
            ];
        }

        return $data;
    }

    public function putRecipesAction(Request $request)
    {
        $title = $request->request->get('title');
        if (!$title) {
            throw new BadRequestHttpException('Title is required');
        }

        $url = $request->request->get('url');
        if (!$url) {
            throw new BadRequestHttpException('URL is required');
        }

        $recipe = $this->getDoctrine()->getRepository('AppBundle:Recipe')->findOneBy(['title' => $title]);

        $recipe = $recipe ?: new Recipe();
        $recipe->setTitle($title);
        $recipe->setLink($url);

        $entityMgr = $this->getDoctrine()->getManager();
        $entityMgr->persist($recipe);
        $entityMgr->flush();

        return ['success' => true];
    }

    public function getRecipeAction($term)
    {
        $data = [];
        if (!empty($term)) {
            $searchService = $this->get('fcm_search');
            $results = $searchService->search($term);
            foreach ($results as $recipe) {
                $data[] = [
                    'title' => $recipe->title,
                    'link' => $recipe->link
                ];
            }
        }

        return ['success' => true, 'results' => $data];
    }

}