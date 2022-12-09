<?php

namespace App\Controller\Api;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OfferController extends AbstractController
{
  /**
   * @Route("/api/offers", name="app_api_offers_browse", methods={"GET"})
   *
   * @param OfferRepository $offerRepository
   * @return JsonResponse
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
   *
   * @param Offer|null $offer
   * @return JsonResponse
   */
  public function read(Offer $offer = null): JsonResponse
  {
    if (!$offer) {
      return $this->json(["erreur" => "l'offre n\'a pas été trouvée"], HttpFoundationResponse::HTTP_NOT_FOUND);
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

  /**
   * @Route("/api/offers", name="app_api_offers_add", methods={"POST"})
   * 
   * @param Request $request
   * @param SerializerInterface $serializerInterface
   * @param ValidatorInterface $validatorInterface
   * @param EntityManagerInterface $entityManagerInterface
   * @return JsonResponse
   */

  public function add(
    Request $request,
    SerializerInterface $serializerInterface,
    ValidatorInterface $validatorInterface,
    EntityManagerInterface $doctrine
  ): JsonResponse
  {
    $jsonContent = $request->getContent();

    try {
      $newOffer = $serializerInterface->deserialize($jsonContent, Offer::class, 'json');
    } catch (\Exception $e) {
      return $this->json(
        ["erreur" => "Les données JSON envoyées n'ont pas pu être interprêtées"],
        HttpFoundationResponse::HTTP_BAD_REQUEST
      );
    }

    $errors = $validatorInterface->validate($newOffer);
    if (count($errors) > 0) {
      $errorsString = (string) $errors;
      return $this->json(
        $errorsString,
        HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
      );
    }

    $doctrine->persist($newOffer);
    $doctrine->flush();

    return $this->json(
      $newOffer,
      HttpFoundationResponse::HTTP_CREATED,
      [
        "Location" => $this->generateUrl("app_api_offers_read", ["id" => $newOffer->getId()])
      ],
      ["groups" => ["offer_read"]]
    );
  }

  /**
   * @Route("/api/offers/{id<\d+>}", name="app_api_offers_edit", methods={"PUT", "PATCH"})
   *
   * @param Offer|null $offer
   * @param Request $request
   * @param EntityManagerInterface $doctrine
   * @param ValidatorInterface $validatorInterface
   * @param SerializerInterface $serializerInterface
   * @return JsonResponse
   */
  public function edit(
    ?Offer $offer,
    Request $request,
    EntityManagerInterface $doctrine,
    ValidatorInterface $validatorInterface,
    SerializerInterface $serializerInterface
  ): JsonResponse
  {
    if (!$offer) {
      return $this->json(["erreur" => "Il n\'existe pas d'offre' pour cet ID"]);
    }

    $jsonContent = $request->getContent();

    try {
      $editedOffer = $serializerInterface->deserialize($jsonContent, Offer::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $offer]);
    } catch (\Exception $e) {
      return $this->json(
        ["erreur" => "Les données JSON envoyées n'ont pas pu être interprêtées"],
        HttpFoundationResponse::HTTP_BAD_REQUEST
      );
    }

    $errors = $validatorInterface->validate($editedOffer);
    if (count($errors) > 0) {
      $errorsString = (string) $errors;
      return $this->json(
        $errorsString,
        HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
      );
    }

    $offer->setUpdatedAt(new DateTime());
    $doctrine->flush();

    return $this->json(
      $offer,
      HttpFoundationResponse::HTTP_PARTIAL_CONTENT,
      [
        "Location" => $this->generateUrl("app_api_offers_read", ["id" => $offer->getId()])
      ],
      [
        "groups" =>
        [
          "offer_read"
        ]
      ]
    );
  }

  /**
   * @Route("/api/offers/{id<\d+>}", name="app_api_offers_delete", methods={"DELETE"})
   *
   * @param Offer|null $offer
   * @param EntityManagerInterface $doctrine
   * @return JsonResponse
   */
  public function delete(?Offer $offer, EntityManagerInterface $doctrine): JsonResponse
  {
    if (!$offer) {
      return $this->json(["erreur" => "Il n\'existe pas de souhait pour cet ID"]);
    }

    $doctrine->remove($offer);
    $doctrine->flush();

    return $this->json(
      ["validation" => "l'annonce " . $offer->getTitle() . " a bien été supprimée."],
      HttpFoundationResponse::HTTP_OK
    );
  }
}
