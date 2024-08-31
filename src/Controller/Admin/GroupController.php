<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GroupController extends AbstractController
{
    #[Route('/admin/groups', name: 'admin_groups', methods: ['GET'])]
    public function groups(GroupRepository $groupRepository): Response
    {
        // Fetch the list of groups from the database
        $groups = $groupRepository->findAll();

        // Render the template and pass the list of groups to it
        return $this->render('admin/group/list.html.twig', [
            'groups' => $groups,
        ]);
    }

    #[Route('/group/new', name: 'admin_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        // Persist the selected users to the group
        foreach ($group->getUsers() as $user) {
            $user->addGroup($group);
            $entityManager->persist($user);
        }

        $entityManager->persist($group);
        $entityManager->flush();
            $entityManager->persist($group);
            $entityManager->flush();

            /** @var SubmitButton $submit */
            $submit = $form->get('saveAndCreateNew');

            if ($submit->isClicked()) {
                return $this->redirectToRoute('admin_group_new', [], Response::HTTP_SEE_OTHER);
            }

            return $this->redirectToRoute('admin_groups', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/group/new.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/group/{id}', name: 'admin_group_show', methods: ['GET'])]
    public function show(Group $group): Response
    {
        // Render the template and pass the group to it
        return $this->render('admin/group/show.html.twig', [
            'group' => $group,
        ]);
    }

    #[Route('/admin/group/{id}/edit', name: 'admin_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the selected users to the group
            foreach ($group->getUsers() as $user) {
                $user->addGroup($group);
                $entityManager->persist($user);
            }

            $entityManager->flush();

            return $this->redirectToRoute('admin_group_edit', ['id' => $group->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/group/edit.html.twig', [
            'group' => $group,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/group/{id}', name: 'admin_group_delete', methods: ['POST'])]
    public function delete(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        /** @var string|null $token */
        $token = $request->getPayload()->get('token');

        if (!$this->isCsrfTokenValid('delete', $token)) {
            return $this->redirectToRoute('admin_groups', [], Response::HTTP_SEE_OTHER);
        }

        $entityManager->remove($group);
        $entityManager->flush();

        $this->addFlash('success', 'group.deleted_successfully');

        return $this->redirectToRoute('admin_groups', [], Response::HTTP_SEE_OTHER);
    }
}
