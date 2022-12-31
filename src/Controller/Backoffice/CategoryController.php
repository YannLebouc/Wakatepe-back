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
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @param CustomSlugger $customSlugger
     * @return Response
     */
    public function new(Request $request, CategoryRepository $categoryRepository, CustomSlugger $customSlugger): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $newSlug = $customSlugger->slugToLower($category->getName());
                $category->setSlug($newSlug);
                $categoryRepository->add($category, true);

                $this->addFlash('success', 'la catégorie a bien été ajoutée');

                return $this->redirectToRoute('app_backoffice_category_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'La catégorie n\'a pas été ajoutée');

        }
        return $this->renderForm('backoffice/category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{id}", name="app_backoffice_category_show", methods={"GET"})
     *
     * @param Category|null $category
     * @return Response
     */
    public function show(?Category $category): Response
    {
        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'a pas été trouvée");}

        return $this->render('backoffice/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_category_edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Category|null $category
     * @param CategoryRepository $categoryRepository
     * @param CustomSlugger $customSlugger
     * @return Response
     */
    public function edit(Request $request, ?Category $category, CategoryRepository $categoryRepository, CustomSlugger $customSlugger): Response
    {
        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'a pas été trouvée");}

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $category->setUpdatedAt(new DateTime());
                $newSlug = $customSlugger->slugToLower($category->getName());
                $category->setSlug($newSlug);
                $categoryRepository->add($category, true);
                
                $this->addFlash('success', 'La catégorie a bien été modifiée');

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
     *
     * @param Request $request
     * @param Category|null $category
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function delete(Request $request, ?Category $category, CategoryRepository $categoryRepository): Response
    {
        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'a pas été trouvée");}

        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        $this->addFlash('success', 'la catégorie a bien été supprimée');

        return $this->redirectToRoute('app_backoffice_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
