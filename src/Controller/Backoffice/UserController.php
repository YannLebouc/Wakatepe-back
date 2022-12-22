<?php

namespace App\Controller\Backoffice;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserTypeEdit;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="app_backoffice_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('backoffice/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_backoffice_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user->setPicture('https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460__340.png');
                $passwordHashed = $userPasswordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($passwordHashed);
                $userRepository->add($user, true);

                $this->addFlash('success', 'L\'utilisateur a bien été ajouté');

                return $this->redirectToRoute('app_backoffice_user_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'L\' utilisateur n\'a pas été ajouté');
        }
        return $this->renderForm('backoffice/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('backoffice/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        
        $form = $this->createForm(UserTypeEdit::class, $user);
        if(!in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $form->remove('roles');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user->setUpdatedAt(new DateTime());
                $userRepository->add($user, true);

                $this->addFlash('success', 'L\'utilisateur a bien été modifié');

                return $this->redirectToRoute('app_backoffice_user_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'L\'utilisateur n\'a pas été modifié');
        }
        return $this->renderForm('backoffice/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        $this->addFlash('success', 'l\'utilisateur a bien été supprimé');

        return $this->redirectToRoute('app_backoffice_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
