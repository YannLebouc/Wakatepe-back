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
          "offer_browse"
        ]
      ]
    );
  }

  /**
   * @Route("/api/offers/{id<\d+>}", name="app_api_offers_read", methods={"GET"})
   */
  public function read(Offer $offer = null): JsonResponse
  {
    if (!$offer) {
      return $this->json(['erreur' => 'la demande n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
    }
    return $this->json(
      $offer,
      HttpFoundationResponse::HTTP_OK,
      [],
      [
        "groups" =>
        [
          "offer_read"
        ]
      ]
    );
  }
}
