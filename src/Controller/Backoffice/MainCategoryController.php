<?php

namespace App\Controller\Backoffice;

use App\Entity\MainCategory;
use App\Form\MainCategoryType;
use App\Repository\MainCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/main/category")
 */
class MainCategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_backoffice_main_category_index", methods={"GET"})
     */
    public function index(MainCategoryRepository $mainCategoryRepository): Response
    {
        return $this->render('backoffice/main_category/index.html.twig', [
            'main_categories' => $mainCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_backoffice_main_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MainCategoryRepository $mainCategoryRepository): Response
    {
        $mainCategory = new MainCategory();
        $form = $this->createForm(MainCategoryType::class, $mainCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mainCategoryRepository->add($mainCategory, true);

            return $this->redirectToRoute('app_backoffice_main_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/main_category/new.html.twig', [
            'main_category' => $mainCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_main_category_show", methods={"GET"})
     */
    public function show(MainCategory $mainCategory): Response
    {
        return $this->render('backoffice/main_category/show.html.twig', [
            'main_category' => $mainCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_main_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, MainCategory $mainCategory, MainCategoryRepository $mainCategoryRepository): Response
    {
        $form = $this->createForm(MainCategoryType::class, $mainCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mainCategoryRepository->add($mainCategory, true);

            return $this->redirectToRoute('app_backoffice_main_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/main_category/edit.html.twig', [
            'main_category' => $mainCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_main_category_delete", methods={"POST"})
     */
    public function delete(Request $request, MainCategory $mainCategory, MainCategoryRepository $mainCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mainCategory->getId(), $request->request->get('_token'))) {
            $mainCategoryRepository->remove($mainCategory, true);
        }

        return $this->redirectToRoute('app_backoffice_main_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
