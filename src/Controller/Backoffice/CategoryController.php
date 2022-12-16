<?php

namespace App\Controller\Backoffice;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Services\CustomSlugger;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_backoffice_category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('backoffice/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_backoffice_category_new", methods={"GET", "POST"})
     * 
     */

    public function new(Request $request, CategoryRepository $categoryRepository, CustomSlugger $customSlugger): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $category->setPicture('https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Michael_Youn_2018.jpg/220px-Michael_Youn_2018.jpg');
                $newSlug = $customSlugger->slugToLower($category->getName());
                $category->setSlug($newSlug);
                $categoryRepository->add($category, true);

                $this->addFlash('success', 'la catégorie a bien été ajoutée');

                return $this->redirectToRoute('app_backoffice_category_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'la catégorie n\'a pas été ajoutée');

        }
        return $this->renderForm('backoffice/category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('backoffice/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository, CustomSlugger $customSlugger): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $category->setUpdatedAt(new DateTime());
                $newSlug = $customSlugger->slugToLower($category->getName());
                $category->setSlug($newSlug);
                $categoryRepository->add($category, true);

                $this->addFlash('success', 'la catégorie a bien été modifiée');

                return $this->redirectToRoute('app_backoffice_category_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'la catégorie n\'a pas été modifiée');
        }
        return $this->renderForm('backoffice/category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_category_delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        $this->addFlash('success', 'la catégorie a bien été supprimée');

        return $this->redirectToRoute('app_backoffice_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
