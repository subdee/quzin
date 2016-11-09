<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Item;
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
        $recipes = $this->getDoctrine()->getRepository('AppBundle:Recipe')->findAll();

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

}