<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        // Fetch the list of users from the database
        $users = $userRepository->findAll();

        // Render the template and pass the list of users to it
        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }
}