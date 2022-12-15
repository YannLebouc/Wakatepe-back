<?php

namespace App\Controller\Backoffice;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
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
     * @Route("/", name="app_backoffice_wish_index", methods={"GET"})
     */
    public function index(WishRepository $wishRepository): Response
    {
        return $this->render('backoffice/wish/index.html.twig', [
            'wishes' => $wishRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_backoffice_wish_new", methods={"GET", "POST"})
     */
    public function new(Request $request, WishRepository $wishRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $wish = new Wish();
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wishRepository->add($wish, true);

            return $this->redirectToRoute('app_backoffice_wish_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/wish/new.html.twig', [
            'wish' => $wish,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_wish_show", methods={"GET"})
     */
    public function show(Wish $wish): Response
    {
        return $this->render('backoffice/wish/show.html.twig', [
            'wish' => $wish,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_wish_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Wish $wish, WishRepository $wishRepository): Response
    {
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wishRepository->add($wish, true);

            return $this->redirectToRoute('app_backoffice_wish_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/wish/edit.html.twig', [
            'wish' => $wish,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_wish_delete", methods={"POST"})
     */
    public function delete(Request $request, Wish $wish, WishRepository $wishRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$wish->getId(), $request->request->get('_token'))) {
            $wishRepository->remove($wish, true);
        }

        return $this->redirectToRoute('app_backoffice_wish_index', [], Response::HTTP_SEE_OTHER);
    }
}
