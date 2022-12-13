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
     * @Route("/api/categories/{id<\d+>}/advertisements", name="app_api_category_advertisements", methods={"GET"})
     *
     * @param Category|null $category
     * @return jsonResponse
     */
    public function getCategoryAdvertisements(?Category $category, CategoryRepository $categoryRepository): JsonResponse
    {
        if (!$category) {
            return $this->json(['erreur' => 'la demande n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
        }
        $categoryId = $category->getId();
        $advertisements = $categoryRepository->findAllAdvertisements($categoryId);

        return $this->json(
            $advertisements,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "category_wishes",
                    "category_offers",
                    "category_advertisements"
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
     * Retieves a list of Wish affliliated to a Category
     * 
     * @Route("/api/categories/{id<\d+>}/wishes", name="app_api_category_wishes", methods={"GET"})
     *
     * @param Category|null $category
     * @return jsonResponse
     */
    public function getCategoryWishes(?Category $category, CategoryRepository $categoryRepository): JsonResponse
    {
        if (!$category) {
            return $this->json(['erreur' => 'la demande n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
        }
        $categoryId = $category->getId();
        $wishes = $categoryRepository->findAllWishes($categoryId);

        return $this->json(
            $wishes,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "category_wishes"
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
            $categoryRepository->findAllActiveCategories(),
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
