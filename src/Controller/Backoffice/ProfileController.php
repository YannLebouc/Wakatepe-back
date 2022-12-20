<?php

namespace App\Controller\Backoffice;


use App\Form\UserTypeEdit;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @Route("/backoffice/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/show", name="app_backoffice_profile_show", methods={"GET"})
     */
    public function show(): Response
    {
        $user = $this->getUser();

        return $this->render('backoffice/profile/show.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user
        ]);
    }
 
    /**
     * @Route("/edit", name="app_backoffice_profile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, UserRepository $userRepository)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserTypeEdit::class, $user);
        if(!in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $form->remove('roles');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user->setUpdatedAt(new DateTime());
                $userRepository->add($user, true);

                $this->addFlash('success', 'Votre profil a bien été modifié');

                return $this->redirectToRoute('app_backoffice_user_index', [], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('danger', 'Votre profil n\'a pas été modifié');
        }
        return $this->renderForm('backoffice/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
