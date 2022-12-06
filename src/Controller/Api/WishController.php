<?php

namespace App\Controller\Api;

use App\Entity\Wish;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/api/wishes", name="app_api_wishes_browse", methods={"GET"})
     */
    public function browse(WishRepository $wishRepository): JsonResponse
    {
        return $this->json(
            $wishRepository->findAll(),
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                'groups' => 'wish_browse'
            ]
        );
    }

    /**
     * @Route("/api/wishes/{id<\d+>}", name="app_api_wishes_read", methods={"GET"})
     */
    public function read(Wish $wish): JsonResponse
    {
        if (!$wish) {
            return $this->json(['erreur' => 'la demande n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
        }
        return $this->json(
            $wish,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                'groups' => 'wish_read'
            ]
        );
    }
}
