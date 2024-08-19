<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    #[Route('/admin/groups', name: 'admin_groups')]
    public function groups(): Response
    {
        // Render groups management page
        return $this->render('admin/groups.html.twig');
    }
}