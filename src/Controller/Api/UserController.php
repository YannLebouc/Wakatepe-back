<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\OfferRepository;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    /**
     * Retrieves a list of the offers belonging to the connected user thanks to the related jwttoken
     *
     * @Route("/api/users/current/offers", name="app_api_current_user_offers", methods={"GET"})
     * 
     * @param Security $security
     * @param OfferRepository $offerRepository
     * @return JsonResponse
     */
    public function getMyOffers(Security $security, OfferRepository $offerRepository): JsonResponse
    {
        // $user = $this->get('security.token_storage')->getToken()->getUser();
        // deprecated version

        if (!$security->getToken()) {
            return $this->json(["erreur" => "Le token fournit n\'est pas valide ou il n\'existe pas"], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }
        $token = $security->getToken();

        if (!$token->getUser()) {
            return $this->json(["erreur" => "Il y a eu un problème lors de la récupération de votre profil"], HttpFoundationResponse::HTTP_NOT_FOUND);
        }
        $user = $token->getUser();

        $offers = $offerRepository->findBy(['user' => $user]);

        return $this->json(
            $offers,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'current_user_offers'
                ]
            ]
        );
    }

    /**
     * Retrieves a list of the wishes belonging to the connected user thanks to the related jwttoken
     *
     * @Route("/api/users/current/wishes", name="app_api_current_user_wishes", methods={"GET"})
     * 
     * @param Security $security
     * @param WishRepository $wishRepository
     * @return JsonResponse
     */
    public function getMyWishes(Security $security, WishRepository $wishRepository): JsonResponse
    {
        // $user = $this->get('security.token_storage')->getToken()->getUser();
        // deprecated version

        if (!$security->getToken()) {
            return $this->json(["erreur" => "Le token fournit n\'est pas valide ou il n\'existe pas"], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }
        $token = $security->getToken();

        if (!$token->getUser()) {
            return $this->json(["erreur" => "Il y a eu un problème lors de la récupération de votre profil"], HttpFoundationResponse::HTTP_NOT_FOUND);
        }
        $user = $token->getUser();

        $wishes = $wishRepository->findBy(['user' => $user]);

        return $this->json(
            $wishes,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'current_user_wishes'
                ]
            ]
        );
    }


    /**
     * @Route("/api/users/{id<\d+>}/offers", name="app_api_users_offers", methods={"GET"})
     */
    public function userOfferBrowse(?User $user): JsonResponse
    {
        if (!$user) {
            return $this->json(["erreur" => "la demande n\'a pas été trouvée"], HttpFoundationResponse::HTTP_NOT_FOUND);
        }
        dd($user);
        return $this->json(
            $user,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "user_offer_browse"
                ]
            ]
        );
    }


    /**
     * @Route("/api/users", name="app_api_users_add", methods={"POST"})
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
        EntityManagerInterface $doctrine,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $jsonContent = $request->getContent();

        try {
            $newUser = $serializerInterface->deserialize($jsonContent, User::class, 'json');
        } catch (\Exception $e) {
            return $this->json(
                ["erreur" => "Les données JSON envoyées n'ont pas pu être interprêtées"],
                HttpFoundationResponse::HTTP_BAD_REQUEST
            );
        }

        $errors = $validatorInterface->validate($newUser);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return $this->json(
                $errorsString,
                HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $newUser->setPicture('https://upload.wikimedia.org/wikipedia/commons/1/1e/Michel_Sardou_2014.jpg');
        $hashedPassword = $passwordHasher->hashPassword($newUser, $newUser->getPassword());
        $newUser->setPassword($hashedPassword);

        $doctrine->persist($newUser);
        $doctrine->flush();

        return $this->json(
            $newUser,
            HttpFoundationResponse::HTTP_CREATED,
            [
                // "Location" => $this->generateUrl("app_api_wishes_read", ["id" => $newUser->getId()])
            ],
            ["groups" => ["users_read"]]
        );
    }

    /**
     * @Route("/api/users/{id<\d+>}", name="app_api_users_read", methods={"GET"})
     */
    public function read(?User $user): JsonResponse
    {
        if (!$user) {
            return $this->json(["erreur" => "L'utilisateur recherché n'existe pas"], HttpFoundationResponse::HTTP_NOT_FOUND);
        }

        return $this->json(
            $user,
            HttpFoundationResponse::HTTP_OK,
            [],
            [
                "groups" =>
                [
                    "user_offer_browse"
                ]
            ]
        );
    }
}
