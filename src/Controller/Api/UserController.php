<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;


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
