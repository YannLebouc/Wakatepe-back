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
   * Retrieves a list of MainCategory
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
   * Retrieves a list of Category affiliated to a MainCategory
   * 
   * @Route("/api/maincategories/{id<\d+>}/categories", name="app_api_maincategory_categories", methods={"GET"})
   *
   * @param MainCategory|null $mainCategory
   * @return JsonResponse
   */
  public function getMainCategoryCategories(?MainCategory $mainCategory): JsonResponse
  {
    if (!$mainCategory) {
      return $this->json(['erreur' => 'la MainCategory demandée n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
    }
    return $this->json(
      $mainCategory,
      HttpFoundationResponse::HTTP_OK,
      [],
      [
        "groups" =>
        [
          "mainCategory_category_browse"
        ]
      ]
    );
  }
}
