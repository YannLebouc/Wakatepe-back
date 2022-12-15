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
   * Retrieves a list of MainCategory and their affiliated Category
   * 
   * @Route("/api/maincategories", name="app_api_maincategories", methods={"GET"})
   *
   * @param MainCategoryRepository $mainCategoryRepository
   * @return JsonResponse
   */
  public function browse(MainCategoryRepository $mainCategoryRepository): JsonResponse
  {
    return $this->json(
      $mainCategoryRepository->findAll(),
      HttpFoundationResponse::HTTP_OK,
      [],
      [
        "groups" =>
        [
          "mainCategory_browse"
        ]
      ]
    );
  }

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

}



