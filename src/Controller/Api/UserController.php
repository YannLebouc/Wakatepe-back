<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
 /**
   * @Route("/api/users/{id<\d+>}/offers", name="app_api_users_offers")
   */
  public function userOfferBrowse(?User $user): JsonResponse
  {
    if (!$user) {
      return $this->json(['erreur' => 'la demande n\'a pas été trouvée'], HttpFoundationResponse::HTTP_NOT_FOUND);
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
   * @Route("/api/users/current/offers", name="app_api_current_user_offers", methods={"GET"})
   */
  public function getMyOffers(Security $security, OfferRepository $offerRepository): JsonResponse
  {
    // $user = $this->get('security.token_storage')->getToken()->getUser();
    // deprecated version

    if (!$security->getToken()) {
      return $this->json('Le token fournit n\'est pas valide ou il n\'existe pas', HttpFoundationResponse::HTTP_BAD_REQUEST);
    }
    $token = $security->getToken();

    if (!$token->getUser()) {
      return $this->json('Il y a eu un problème lors de la récupération de votre profil', HttpFoundationResponse::HTTP_NOT_FOUND);
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

  // /**
  //  * @Route("/api/users/{id<\d+>}", name="app_api_users_read")
  //  */
  // public function read(User $user): JsonResponse
  // {
  //   return $this->json(
  //     $user,
  //     HttpFoundationResponse::HTTP_OK,
  //     [],
  //     [
  //       "groups" =>
  //       [
  //         "user_offer_browse"
  //       ]
  //     ]
  //   );
  // }

}
