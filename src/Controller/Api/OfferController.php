<?php

namespace App\Controller\Api;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

use Symfony\Component\Routing\Annotation\Route;


class OfferController extends AbstractController
{
  /**
   * @Route("/api/offers", name="app_api_offers_browse", methods={"GET"})
   */
  public function browse(OfferRepository $offerRepository): JsonResponse
  {

    return $this->json(
      $offerRepository->findAll(),
      HttpFoundationResponse::HTTP_OK,
      [],
      [
        "groups" =>
        [
          "browse_offer"
        ]
      ]
    );
  }

  /**
   * @Route("/api/offers/{id<\d+>}", name="app_api_offers_read", methods={"GET"})
   */
  public function read(Offer $offerRepository): JsonResponse
  {
      return $this->json(
        $offerRepository,
        HttpFoundationResponse::HTTP_OK,
        [],
        [
          "groups" =>
          [
            "read_offer"
          ]
        ]
      );
  }
}
