<?php

namespace App\Controller\Api;

use App\Entity\Offer;
use App\Entity\User;
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
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @OA\Tag(name="O'troc API : Offer")
 * @Security(name="bearerAuth")
 */
class OfferController extends AbstractController
{
    /**
     * Retrieves a list of the active offers
     * @Route("/api/offers", name="app_api_offers_browse", methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Retrieves the active offers",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Offer::class, groups={"offer_browse"}))
     *     )
     * )
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
     * Retrieves the informations about a single offer thanks to its ID
     * @Route("/api/offers/{id<\d+>}", name="app_api_offers_read", methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Returns JSON info of the offer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Offer::class, groups={"offer_read"}))
     *     )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="l'offre n\'a pas été trouvée"
     * )
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
     * Adds a new offer 
     * @Route("/api/offers", name="app_api_offers_add", methods={"POST"})
     * 
     * @OA\Response(
     *     response="201",
     *     description="creates a new offer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Offer::class, groups={"offer_read"}))
     *     )
     * )
     * 
     * @OA\Response(
     *     response=400,
     *     description="Les données JSON envoyées n'ont pas pu être interprêtées"
     * )
     * @OA\Response(
     *     response=422,
     *     description="Renvoie un tableau d'erreurs en fonction des validations demandées pour les champs"
     * )
     * @OA\RequestBody(
     *     @Model(type=Offer::class, groups={"nelmio_add_offer"}),
     * )     
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
    ): JsonResponse {
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

        $newOffer->setCreatedAt(new DateTime());
        $newOffer->setUser($this->getUser());

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
     * Edits an offer 
     * @Route("/api/offers/{id<\d+>}", name="app_api_offers_edit", methods={"PUT", "PATCH"})
     *
     * @OA\Response(
     *     response="201",
     *     description="creates a new offer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Offer::class, groups={"offer_read"}))
     *     )
     * )
     * 
     * @OA\Response(
     *     response=400,
     *     description="Les données JSON envoyées n'ont pas pu être interprêtées"
     * )
     * @OA\Response(
     *     response=404,
     *     description="Il n\'existe pas d'offre' pour cet ID"
     * )
     * @OA\Response(
     *     response=422,
     *     description="Renvoie un tableau d'erreurs en fonction des validations demandées pour les champs"
     * )
     * @OA\RequestBody(
     *     @Model(type=Offer::class, groups={"nelmio_add_offer"}),
     * )     
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
    ): JsonResponse {
        if (!$offer) {
            return $this->json(["erreur" => "Il n\'existe pas d'offre' pour cet ID"], HttpFoundationResponse::HTTP_NOT_FOUND);
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
     * Deletes an offer
     * @Route("/api/offers/{id<\d+>}", name="app_api_offers_delete", methods={"DELETE"})
     * 
     * @OA\Response(
     *     response="200",
     *     description="Returns JSON confirming that the offer has been deleted",
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Il n\'existe pas d'offre' pour cet ID"
     * )
     * @param Offer|null $offer
     * @param EntityManagerInterface $doctrine
     * @return JsonResponse
     */
    public function delete(?Offer $offer, EntityManagerInterface $doctrine): JsonResponse
    {
        if (!$offer) {
            return $this->json(["erreur" => "Il n\'existe pas d'offre' pour cet ID"]);
        }

        $doctrine->remove($offer);
        $doctrine->flush();

        return $this->json(
            ["validation" => "l'annonce " . $offer->getTitle() . " a bien été supprimée."],
            HttpFoundationResponse::HTTP_OK
        );
    }

    /**
     * @Route("/api/offers/results", name="app_api_offers_research")
     */
    public function offersResearch(): JsonResponse
    {   
        // Nom de la clé "search"
        // Je veux récupérer l'input envoyé (string)
        // exploser la string et aller chercher les offres dont le titre contient les morceaux de string
        

        return $this->json(['property'=>'value'],200);
    }
}
