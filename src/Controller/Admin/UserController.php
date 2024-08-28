<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
#[IsGranted(User::ROLE_ADMIN)]
class UserController extends AbstractController
{
    #[Route('/users', name: 'admin_users', methods: ['GET'])]
    public function users(UserRepository $userRepository): Response
    {
        // Fetch the list of users from the database
        $users = $userRepository->findAll();

        // Render the template and pass the list of users to it
        return $this->render('admin/user/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        // Define the validation group
        $validationGroups = ['create']; // Example: use 'create' group for new user creation

        $form = $this->createForm(UserType::class, $user, [])
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);



        if ($form->isSubmitted()) {

            // Manually set the plain password in the entity before validating
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPlainPassword($plainPassword);
            }

            if ($form->isValid()) {

                // Hash the password before saving the user
                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPlainPassword());
                $user->setPassword($hashedPassword);

                $entityManager->persist($user);
                $entityManager->flush();

                /** @var SubmitButton $submit */
                $submit = $form->get('saveAndCreateNew');

                if ($submit->isClicked()) {
                    return $this->redirectToRoute('admin_user_new', [], Response::HTTP_SEE_OTHER);
                }

                return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/{id}', name: 'admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        // Render the template and pass the user to it
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Hash the password if it's changed
            if ($form->get('plainPassword')->getData()) {
                $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($hashedPassword);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        /** @var string|null $token */
        $token = $request->getPayload()->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'user.deleted_successfully');

        return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
    }
}
