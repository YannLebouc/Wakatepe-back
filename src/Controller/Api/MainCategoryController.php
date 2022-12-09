<?php

namespace App\Controller\Api;

use App\Entity\MainCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;



class MainCategoryController extends AbstractController
{
    /**
     * @Route("/api/maincategories/{id<\d+>}/categories", name="app_maincategories_categories", methods={"GET"})
     */
    public function mainCategoryCategoryBrowse(?MainCategory $mainCategory): JsonResponse
    {
        if (!$mainCategory) {
          return $this->json('la MainCategory demandée n\'a pas été trouvée', HttpFoundationResponse::HTTP_NOT_FOUND);
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