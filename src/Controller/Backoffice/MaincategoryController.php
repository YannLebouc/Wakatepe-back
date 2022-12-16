<?php

namespace App\Controller\Backoffice;

use App\Entity\MainCategory;
use App\Form\MainCategoryType;
use App\Repository\MainCategoryRepository;
use App\Services\CustomSlugger;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/maincategory")
 */
class MaincategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_backoffice_maincategory_index", methods={"GET"})
     */
    public function index(MainCategoryRepository $mainCategoryRepository): Response
    {
        return $this->render('backoffice/maincategory/index.html.twig', [
            'main_categories' => $mainCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_backoffice_maincategory_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MainCategoryRepository $mainCategoryRepository, CustomSlugger $customSlugger): Response
    {
        $mainCategory = new MainCategory();
        $form = $this->createForm(MainCategoryType::class, $mainCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $newSlug = $customSlugger->slugToLower($mainCategory->getName());
                $mainCategory->setSlug($newSlug);
                $mainCategoryRepository->add($mainCategory, true);

                $this->addFlash('success', 'la main catégorie a bien été ajoutée');

                return $this->redirectToRoute('app_backoffice_maincategory_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'la main catégorie n\'a pas été ajoutée');
        }
        return $this->renderForm('backoffice/maincategory/new.html.twig', [
            'main_category' => $mainCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_maincategory_show", methods={"GET"})
     */
    public function show(MainCategory $mainCategory): Response
    {
        return $this->render('backoffice/maincategory/show.html.twig', [
            'main_category' => $mainCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_maincategory_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, MainCategory $mainCategory, MainCategoryRepository $mainCategoryRepository, CustomSlugger $customSlugger): Response
    {
        $form = $this->createForm(MainCategoryType::class, $mainCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $mainCategory->setUpdatedAt(new DateTime());
                $newSlug = $customSlugger->slugToLower($mainCategory->getName());
                $mainCategory->setSlug($newSlug);
                $mainCategoryRepository->add($mainCategory, true);

                $this->addFlash('success', 'la main catégorie a bien été modifiée');

                return $this->redirectToRoute('app_backoffice_maincategory_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'la main catégorie n\'a pas été modifiée');
        }

        return $this->renderForm('backoffice/maincategory/edit.html.twig', [
            'main_category' => $mainCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_maincategory_delete", methods={"POST"})
     */
    public function delete(Request $request, MainCategory $mainCategory, MainCategoryRepository $mainCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $mainCategory->getId(), $request->request->get('_token'))) {
            $mainCategoryRepository->remove($mainCategory, true);

            $this->addFlash('success', 'la main catégorie a bien été supprimée');

        }

        return $this->redirectToRoute('app_backoffice_maincategory_index', [], Response::HTTP_SEE_OTHER);
    }
}
