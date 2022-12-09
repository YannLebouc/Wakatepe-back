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
                'groups' => 
                [
                    'wish_browse'
                ]
            ]
        );
    }

    /**
     * @Route("/api/wishes/{id<\d+>}", name="app_api_wishes_read", methods={"GET"})
     */
    public function read(?Wish $wish): JsonResponse
    {
        if (!$wish) {
            return $this->json(['erreur' => 'la demande n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
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
     * @Route("/api/wishes", name="app_api_wishes_add", methods={"POST"})
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
                "Les données JSON envoyées n'ont pas pu être interprêtées",
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
        
        // $newDate = new DateTime();
        // $dateTimeTransformer = new DateTimeToStringTransformer();
        // $newWish->setCreatedAt($dateTimeTransformer->transform($newDate));

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
     * @Route("/api/wishes/{id<\d+>}", name="app_api_wishes_edit", methods={"PUT", "PATCH"})
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
        return $this->json('Il n\'existe pas de souhait pour cet ID');
       }

       $jsonContent = $request->getContent();
       
       try {
           $editedWish = $serializerInterface->deserialize($jsonContent, Wish::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $wish]);
       } catch (\Exception $e) {
            return $this->json(
                "Les données envoyées n'ont pas pu être interprêtées",
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
     * @Route("/api/wishes/{id<\d+>}", name="app_api_wishes_delete", methods={"DELETE"})
     */
    public function delete(?Wish $wish, Request $request, EntityManagerInterface $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        if (!$wish) {
            return $this->json('Il n\'existe pas de souhait pour cet ID');
           }

        $doctrine->remove($wish);
        $doctrine->flush();

        return $this->json(
            'l\'annonce "' . $wish->getTitle() . '" a bien été supprimée.',
            HttpFoundationResponse::HTTP_OK
        );
    }
}
