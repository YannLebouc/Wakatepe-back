<?php

namespace App\Controller\Backoffice;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Form\OfferTypeCustom;
use App\Repository\OfferRepository;
use DateTime;
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
     * @Route("/{id}", name="app_backoffice_offer_show", methods={"GET"})
     */
    public function show(?Offer $offer): Response
    {
        if (!$offer) {
            throw $this->createNotFoundException("L'offre demandée n'a pas été trouvée");}

        return $this->render('backoffice/offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_offer_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ?Offer $offer, OfferRepository $offerRepository): Response
    {
        if (!$offer) {
            throw $this->createNotFoundException("L'offre demandée n'a pas été trouvée");}

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $offerRepository->add($offer, true);

                $this->addFlash('success', 'L\'offre a bien été modifiée');

                return $this->redirectToRoute('app_backoffice_offer_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'L\'offre n\'a pas été modifiée');
        }
        return $this->renderForm('backoffice/offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_offer_delete", methods={"POST"})
     */
    public function delete(Request $request, ?Offer $offer, OfferRepository $offerRepository): Response
    {
        if (!$offer) {
            throw $this->createNotFoundException("L'offre demandée n'a pas été trouvée");}

        if ($this->isCsrfTokenValid('delete' . $offer->getId(), $request->request->get('_token'))) {
            $offerRepository->remove($offer, true);

            $this->addFlash('success', 'L\'offre a bien été supprimée');
        }

        return $this->redirectToRoute('app_backoffice_reported_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/validate", name="app_backoffice_offer_validate", methods={"POST"})
     */
    public function validate(Request $request, ?Offer $offer, OfferRepository $offerRepository): Response
    {
        if ($this->isCsrfTokenValid('validate' . $offer->getId(), $request->request->get('_token'))) {
        }
        if (!$offer) {
            throw $this->createNotFoundException("L'offre demandée n'a pas été trouvée");}
        $offer->setIsReported(false);
        $offer->setUpdatedAt(new DateTime());
        $offerRepository->add($offer, true);


        return $this->redirectToRoute('app_backoffice_reported_index', [], Response::HTTP_SEE_OTHER);
    }
}
