<?php

namespace App\Controller\Backoffice;

use App\Repository\OfferRepository;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportedController extends AbstractController
{
    /**
     * @Route("/backoffice/reported", name="app_backoffice_reported")
     */
    public function index(Request $request, OfferRepository $offerRepository, WishRepository $wishRepository): Response
    {
        // return $this->render('backoffice/reported/index.html.twig', [
        //     'controller_name' => 'ReportedController',
        // ]);
        return $this->render('backoffice/reported/index.html.twig', [
            'offers' => $offerRepository->reportedOffers(),
            'wishes' => $wishRepository->reportedWishes(),
        ]);
    }
}
