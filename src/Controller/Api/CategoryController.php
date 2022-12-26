<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\OfferRepository;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;


class CategoryController extends AbstractController
{
    /**
     * Retieves a list of Offer affliliated to a Category
     * 
     * @Route("/api/categories/{id<\d+>}/offers", name="app_api_category_offers", methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Returns JSON containing the offers of a particular category",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Category::class, groups={"category_offers"}))
     *     )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="la catégorie n\'a pas été trouvée"
     * )
     * @param Category|null $category
     * @return jsonResponse
     */
    public function getCategoryOffers(?Category $category, CategoryRepository $categoryRepository): JsonResponse
    {
        if (!$category) {
            return $this->json(['erreur' => 'la categorie n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
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
     * @OA\Response(
     *     response="200",
     *     description="Returns JSON containing the wishes of a particular category",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Category::class, groups={"category_wishes"}))
     *     )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="la catégorie n\'a pas été trouvée"
     * )
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
     * 
     * @OA\Response(
     *     response="200",
     *     description="Returns JSON infp of all the active categories",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Category::class, groups={"category_browse"}))
     *     )
     * )
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

    /**
     * Retieves a list of Offer and Wish affliliated to a Category
     * 
     * @Route("/api/categories/{id<\d+>}/advertisements", name="app_api_categories_advertisements", methods={"GET"})
     *
     * @param Category|null $category
     * @return jsonResponse
     */
    public function getCategoryAdvertisements(?Category $category, OfferRepository  $offerRepository, WishRepository $wishRepository): JsonResponse
    {
        if (!$category) {
            return $this->json(['erreur' => 'la demande n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
   }   
        $activeWishes = $wishRepository->findActiveWishes($category->getId()); 
        $activeOffers = $offerRepository->findActiveOffers($category->getId());

        return $this->json(
            [
                'wishes' => $activeWishes,
                'offers' => $activeOffers
            ],
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    // "wish_read",
                    // "offer_read"
                    "category_advertisements"
                ]
            ]);
    }

    /**
     * Retrieves the top 5 categories with the most offers
     * @Route("/api/categories", name="app_api_top_categories", methods={"GET"})
     * 
     * 
     * @param CategoryRepository $categoryRepository
     * @return JsonResponse
     */
    public function getTrendingCategories(CategoryRepository $categoryRepository): JsonResponse
    {
        $topCategories = [];
        $categories = $categoryRepository->findAllActiveCategories();
        
        for ($i = 0; $i < 5; $i++) { 
            $randomCat = $categories[rand(0, count($categories)-1)];
            if(!in_array($randomCat, $topCategories)) {
                $topCategories[] = $randomCat;
            } else {
                $i--;
            }
        }

        return $this->json(
            $topCategories,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                'groups' => [
                    'category_browse'
                ]
            ]
         );
    }
}
