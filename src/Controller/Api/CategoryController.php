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
            ]
        );
    }

    /**
     * Retieves a list of Offer affliliated to a Category
     * 
     * @Route("/api/categories/{id<\d+>}/offers", name="app_api_category_offers", methods={"GET"})
     *
     * @param Category|null $category
     * @return jsonResponse
     */
    public function getCategoryOffers(?Category $category, CategoryRepository $categoryRepository): JsonResponse
    {
        if (!$category) {
            return $this->json(['erreur' => 'la demande n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
        }
        $categoryId = $category->getId();
        $offers = $categoryRepository->findAllOffers($categoryId);

        return $this->json(
            $offers,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "category_offers"
                ]
            ]
        );
    }



    /**
     * Retrieves a list of active categories
     * 
     * @Route("/api/categories/active", name="app_api_categories_active", methods={"GET"})
     */
    public function findAllActiveCategories(CategoryRepository $categoryRepository): JsonResponse
    {
        return $this->json(
            $categoryRepository->dql(),
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                "groups" => [
                    "category_browse"
                ]
            ]
        );
    }
}
