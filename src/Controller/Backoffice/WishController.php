<?php

namespace App\Controller\Backoffice;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/wish")
 */
class WishController extends AbstractController
{
    /**
     * @Route("/{id}", name="app_backoffice_wish_show", methods={"GET"})
     *
     * @param Wish|null $wish
     * @return Response
     */
    public function show(?Wish $wish): Response
    {
        if (!$wish) {
            throw $this->createNotFoundException("La demande demandée n'a pas été trouvée");}

        return $this->render('backoffice/wish/show.html.twig', [
            'wish' => $wish,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_wish_edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Wish|null $wish
     * @param WishRepository $wishRepository
     * @return Response
     */
    public function edit(Request $request, ?Wish $wish, WishRepository $wishRepository): Response
    {
        if (!$wish) {
            throw $this->createNotFoundException("La demande demandée n'a pas été trouvée");}

        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $wishRepository->add($wish, true);

                $this->addFlash('success', 'La demande a bien été modifiée');

                return $this->redirectToRoute('app_backoffice_reported_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'La demande n\'a pas été modifiée');
        }
        return $this->renderForm('backoffice/wish/edit.html.twig', [
            'wish' => $wish,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_wish_delete", methods={"POST"})
     *
     * @param Request $request
     * @param Wish|null $wish
     * @param WishRepository $wishRepository
     * @return Response
     */
    public function delete(Request $request, ?Wish $wish, WishRepository $wishRepository): Response
    {
        if (!$wish) {
            throw $this->createNotFoundException("La demande demandée n'a pas été trouvée");}

        if ($this->isCsrfTokenValid('delete' . $wish->getId(), $request->request->get('_token'))) {
            $wishRepository->remove($wish, true);
        }

        return $this->redirectToRoute('app_backoffice_reported_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/validate", name="app_backoffice_wish_validate", methods={"POST"})
     *
     * @param Request $request
     * @param Wish $wish
     * @param WishRepository $wishRepository
     * @return Response
     */
    public function validate(Request $request, Wish $wish, WishRepository $wishRepository): Response
    {
        if (!$wish) {
            throw $this->createNotFoundException("La demande demandée n'a pas été trouvée");}
            
        if ($this->isCsrfTokenValid('validate' . $wish->getId(), $request->request->get('_token'))) {
        }
        
        $wish->setIsReported(false);
        $wish->setUpdatedAt(new DateTime());
        $wishRepository->add($wish, true);


        return $this->redirectToRoute('app_backoffice_reported_index', [], Response::HTTP_SEE_OTHER);
    }
}
