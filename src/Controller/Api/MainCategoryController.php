<?php

namespace App\Controller\Api;

use App\Entity\MainCategory;
use App\Repository\MainCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;



class MainCategoryController extends AbstractController
{

    /**
     * Retrieves a list of MainCategory and their affiliated active Category
     * 
     * @Route("/api/maincategories/categories", name="app_api_maincategories_categories", methods={"GET"})
     *
     * @param MainCategoryRepository $mainCategoryRepository
     * @return JsonResponse
     */
    public function getAllMainCategoriesCategories(MainCategoryRepository $mainCategoryRepository): JsonResponse
    {
        return $this->json(
            $mainCategoryRepository->findAllActiveCategories(),
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "mainCategories_categories"
                ]
            ]
        );
    }


    /**
     * Retrieves a list of active Category and their active advertisements affiliated to a MainCategory
     * 
     * @Route("/api/maincategories/{id<\d+>}/categories/advertisements", name="app_api_maincategory_categories_ advertisements", methods={"GET"})
     *
     * @param MainCategory|null $mainCategory
     * @return JsonResponse
     */
    public function getMainCategoryCategoriesAdvertisements(?MainCategory $mainCategory, MainCategoryRepository $mainCategoryRepository): JsonResponse
    {
        if (!$mainCategory) {
            return $this->json(['erreur' => 'la MainCategory demandée n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
        }

        $MainCategoryId = $mainCategory->getId();
        $adverts = $mainCategoryRepository->findAllActiveAdvertisements($MainCategoryId);

        return $this->json(
            $adverts,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "mainCategory_categories_advertisements"
                ]
            ]
        );
    }


    /** retrieves all the categories belonging to a maincategorie
     * @Route("/api/maincategories/{id<\d+>}/categories", name="app_api_maincat_categories", methods={"GET"})
     */
    public function getCategoriesFromMainCategory(?MainCategory $mainCategory, MainCategoryRepository $mainCategoryRepository): JsonResponse
    {
        if (!$mainCategory) {
            return $this->json(['erreur' => 'la MainCategory demandée n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
        }

        $categories = $mainCategoryRepository->findAllCategoriesByMainCat($mainCategory->getId());
        
        return $this->json(
            $categories,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                "groups" => [
                    "maincat_categories"
                ]
            ]
        );
    }
}
