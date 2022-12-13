<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{

    /**
     * Retieves a list of Offer and Wish affliliated to a Category
     * 
     * @Route("/api/categories/{id<\d+>}/advertisements", name="app_api_categories_advertisements", methods={"GET"})
     *
     * @param Category|null $category
     * @return jsonResponse
     */
    public function getCategoryAdvertisements(?Category $category): JsonResponse
    {
        if (!$category) {
            return $this->json(['erreur' => 'la demande n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
   }

        return $this->json(
            $category,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "category_advertisement_browse"
                ]
            ]);
    }



}
