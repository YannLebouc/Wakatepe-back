<?php

namespace App\Controller\Api;

use App\Entity\Wish;
use App\Repository\WishRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @OA\Tag(name="O'troc API : Wish")
 * @Security(name="bearerAuth")
 */
class WishController extends AbstractController
{
    /**
     * Retrieves a list of the active wishes
     * @Route("/api/wishes", name="app_api_wishes_browse", methods={"GET"})     *
     * @OA\Response(
     *     response="200",
     *     description="Retrieves the active wishes",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Wish::class, groups={"wish_browse"}))
     *     )
     * )     
     */
    public function browse(WishRepository $wishRepository): JsonResponse
    {
        return $this->json(
            $wishRepository->findAll(),
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                'groups' => 
                [
                    'wish_browse'
                ]
            ]
        );
    }

    /**
     * Retrieves the informations about a single wish thanks to its ID
     * @Route("/api/wishes/{id<\d+>}", name="app_api_wishes_read", methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Returns JSON info of the wish",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Wish::class, groups={"wish_read"}))
     *     )
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="la demande n'a pas été trouvée"
     * )     
     */
    public function read(?Wish $wish): JsonResponse
    {
        if (!$wish) {
            return $this->json(["erreur" => "la demande n\'a pas été trouvée"], HttpFoundationResponse::HTTP_NOT_FOUND);
        }
        return $this->json(
            $wish,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                'groups' => 
                [
                    'wish_read'
                ]
            ]
        );
    }

    /**
     * Adds a new wish 
     * @Route("/api/wishes", name="app_api_wishes_add", methods={"POST"})
     * 
     * @OA\Response(
     *     response="201",
     *     description="creates a new wish",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Wish::class, groups={"wish_read"}))
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
     *     @Model(type=Wish::class, groups={"nelmio_add_wish"}),
     * )    
     * 
     * @param Request $request
     * @param SerializerInterface $serializerInterface
     * @param ValidatorInterface $validatorInterface
     * @param EntityManagerInterface $entityManagerInterface
     * @return Response
     */

    public function add(
        Request $request,
        SerializerInterface $serializerInterface,
        ValidatorInterface $validatorInterface,
        EntityManagerInterface $doctrine
    )
    {
        $jsonContent = $request->getContent();

        try {
            $newWish = $serializerInterface->deserialize($jsonContent, Wish::class, 'json');
        } catch (\Exception $e) {
            return $this->json(
                ["erreur" => "Les données JSON envoyées n'ont pas pu être interprêtées"],
                HttpFoundationResponse::HTTP_BAD_REQUEST
            );
        }

        $errors = $validatorInterface->validate($newWish);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return $this->json(
                $errorsString,
                HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        
        $newWish->setUser($this->getUser());

        $doctrine->persist($newWish);
        $doctrine->flush();

        return $this->json(
            $newWish,
            HttpFoundationResponse::HTTP_CREATED,
            [
                "Location" => $this->generateUrl("app_api_wishes_read", ["id" => $newWish->getId()])
            ],
            ["groups" => ["wish_read"]]
        );
    }

    /**
     * Edits an offer 
     * @Route("/api/wishes/{id<\d+>}", name="app_api_wishes_edit", methods={"PUT", "PATCH"})
     *
     * @OA\Response(
     *     response="206",
     *     description="Wish was edited",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Wish::class, groups={"wish_read"}))
     *     )
     * )
     * 
     * @OA\Response(
     *     response=400,
     *     description="Les données JSON envoyées n'ont pas pu être interprêtées"
     * )
     * @OA\Response(
     *     response=404,
     *     description="Il n'existe pas de souhait pour cet ID"
     * )
     * @OA\Response(
     *     response=422,
     *     description="Renvoie un tableau d'erreurs en fonction des validations demandées pour les champs"
     * )
     * @OA\RequestBody(
     *     @Model(type=Wish::class, groups={"nelmio_add_wish"}),
     * )   
     * 
     * @param Wish|null $wish
     * @param Request $request
     * @param EntityManagerInterface $doctrine
     * @param ValidatorInterface $validatorInterface
     * @param SerializerInterface $serializerInterface
     * @return JSON
     */
    public function edit(
        ?Wish $wish,
        Request $request,
        EntityManagerInterface $doctrine,
        ValidatorInterface $validatorInterface,
        SerializerInterface $serializerInterface
    )
    {
       if (!$wish) {
        return $this->json(["erreur" => "Il n'existe pas de souhait pour cet ID"]);
       }

       $jsonContent = $request->getContent();
       
       try {
           $editedWish = $serializerInterface->deserialize($jsonContent, Wish::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $wish]);
       } catch (\Exception $e) {
            return $this->json(
                ["erreur" => "Les données envoyées n'ont pas pu être interprêtées"],
                HttpFoundationResponse::HTTP_BAD_REQUEST
            );
       }

       $errors = $validatorInterface->validate($editedWish);
       if (count($errors) > 0) {
           $errorsString = (string) $errors;
           return $this->json(
               $errorsString,
               HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
           );
       }

       $wish->setIsReported(null);
       $wish->setUpdatedAt(new DateTime());
       $doctrine->flush();

       return $this->json(
        $wish,
        HttpFoundationResponse::HTTP_PARTIAL_CONTENT,
        [
            "Location" => $this->generateUrl("app_api_wishes_read", ["id" => $wish->getId()])
        ],
        [
            "groups" => 
            [
                "wish_read"
            ]
        ]
    );
    }

    /**
     * Deletes an offer
     * @Route("/api/wishes/{id<\d+>}", name="app_api_wishes_delete", methods={"DELETE"})
     * 
     * @OA\Response(
     *     response="200",
     *     description="Returns JSON confirming that the wish has been deleted",
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Il n'existe pas de souhait pour cet ID"
     * )
     */
    public function delete(?Wish $wish, Request $request, EntityManagerInterface $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        if (!$wish) {
            return $this->json(['erreur' => 'Il n\'existe pas de souhait pour cet ID']);
           }

        $doctrine->remove($wish);
        $doctrine->flush();

        return $this->json(
            'l\'annonce "' . $wish->getTitle() . '" a bien été supprimée.',
            HttpFoundationResponse::HTTP_OK
        );
    }

    /** Retrieves all the wishes containing a keyword in their title
     * 
     * @Route("/api/wishes/results", name="app_api_wishes_research", methods={"POST"})
     */
    public function wishesResearch(Request $request, WishRepository $wishRepository): JsonResponse
    {   
        
        // Nom de la clé "search"

        // Je veux récupérer l'input envoyé (string)
        $requestContent = $request->getContent();
        // exploser la string et aller chercher les offres dont le titre contient les morceaux de string
        $keywords = explode(" ", $requestContent);
        $wishes = [];
        foreach ($keywords as $keyword) {
            if (strlen($keyword) > 2) {
                $results = $wishRepository->getSearchedWishes($keyword);
                $wishes[] = $results;
            }
        }
        
        return $this->json(
            $wishes,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                'groups' => [
                    'wish_browse'
                ]
            ]
        );
    }

    /** Allows a user to set a wish active status to true or false
    * 
    * @Route("/api/wishes/{id<\d+>}/active", name="app_api_wishes_active", methods={"PUT", "PATCH"})
    * Undocumented function
    *
    * @param Wish|null $wish
    * @param EntityManagerInterface $doctrine
    * @return JsonResponse
    */
    public function isActive(?Wish $wish, EntityManagerInterface $doctrine): JsonResponse
    {   
        if (!$wish) {
            return $this->json(["erreur" => "Il n\'existe pas d'offre' pour cet ID"]);
        }

        $isActive = !($wish->isIsActive());

        $wish->setIsActive($isActive);
        $doctrine->flush();

        return $this->json(['success' => 'Modification prise en compte'], HttpFoundationResponse::HTTP_PARTIAL_CONTENT);
    }

    /** Allows a user to set a wish reported status to true or false
     * 
     * @Route("/api/wishes/{id<\d+>}/reported", name="app_api_wishes_reported", methods={"PUT", "PATCH"})
     * Undocumented function
     *
     * @param Wish|null $wish
     * @param EntityManagerInterface $doctrine
     * @return JsonResponse
     */
    public function isReported(?Wish $wish, EntityManagerInterface $doctrine): JsonResponse
    {   
        if (!$wish) {
            return $this->json(["erreur" => "Il n\'existe pas d'offre' pour cet ID"]);
        }

        $wish->setIsReported(true);
        $doctrine->flush();

        return $this->json(['success' => 'Modification prise en compte'], HttpFoundationResponse::HTTP_PARTIAL_CONTENT);
    }
}
