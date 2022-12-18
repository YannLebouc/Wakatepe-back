<?php

namespace App\Controller\Backoffice;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Form\OfferTypeCustom;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/offer")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/", name="app_backoffice_offer_index", methods={"GET"})
     */
    public function index(OfferRepository $offerRepository): Response
    {
        return $this->render('backoffice/offer/index.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_backoffice_offer_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OfferRepository $offerRepository): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offerRepository->add($offer, true);

            return $this->redirectToRoute('app_backoffice_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/offer/new.html.twig', [
            'offer' => $offer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_offer_show", methods={"GET"})
     */
    public function show(Offer $offer): Response
    {
        return $this->render('backoffice/offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_offer_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Offer $offer, OfferRepository $offerRepository): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offerRepository->add($offer, true);

            return $this->redirectToRoute('app_backoffice_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/editcustom", name="app_backoffice_offer_editcustom", methods={"GET", "POST"})
     */
    public function editCustom(Request $request, Offer $offer, OfferRepository $offerRepository): Response
    {
        $form = $this->createForm(OfferTypeCustom::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $offerRepository->add($offer, true);

                $this->addFlash('success', 'L\'offre a bien été modifiée');

                return $this->redirectToRoute('app_backoffice_offer_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'L\'offre n\'a pas été modifiée');
        }
        return $this->renderForm('backoffice/offer/editcustom.html.twig', [
            'offer' => $offer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_offer_delete", methods={"POST"})
     */
    public function delete(Request $request, Offer $offer, OfferRepository $offerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $offer->getId(), $request->request->get('_token'))) {
            $offerRepository->remove($offer, true);

            $this->addFlash('success', 'L\'offre a bien été supprimée');
        }

        return $this->redirectToRoute('app_backoffice_offer_index', [], Response::HTTP_SEE_OTHER);
    }
}
