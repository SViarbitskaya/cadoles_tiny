<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\UserRepository;
use App\Repository\GroupRepository;

class DataTablesController extends AbstractController
{
    #[Route('/admin/users/data', name: 'users_data', methods: ['GET'])]
    public function usersData(Request $request, UserRepository $userRepository): JsonResponse
    {
        $parameters = $request->query->all();
        $start = $parameters['start'] ?? 0;
        $length = $parameters['length'] ?? 5;
        $search = $parameters['search'] ?? '';

        // Total records without filtering
        $totalRecords = $userRepository->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->getQuery()
        ->getSingleScalarResult();

        // Query with filtering
        $queryBuilder = $userRepository->createQueryBuilder('u');

        if (!empty($search)) {
            $queryBuilder->andWhere('u.email LIKE :search')
                        ->setParameter('search', '%' . $search["value"] . '%');
        }

        $totalFilteredRecords = $queryBuilder->select('COUNT(u.id)')
                                            ->getQuery()
                                            ->getSingleScalarResult();

        $data = $queryBuilder->select('u.id, u.email, u.roles')
                            ->leftJoin('u.groups', 'g')
                            ->addSelect('g.name AS group_name')
                            ->setFirstResult($start)
                            ->setMaxResults($length)
                            ->getQuery()
                            ->getArrayResult();

        // Group the results correctly
        $formattedData = [];
        foreach ($data as $user) {
            $userId = $user['id'];
            if (!isset($formattedData[$userId])) {
                // Geenrate show and edit urls for each row
                $userShowUrl = $this->generateUrl('admin_user_show', [
                    'id' => $user['id'],
                ]);
                $userEditUrl = $this->generateUrl('admin_user_edit', [
                    'id' => $user['id'],
                ]);
                $formattedData[$userId] = [
                    'id' => $user['id'],
                    'urls' => [$userShowUrl, $userEditUrl],
                    'email' => $user['email'],
                    'groups' => [],
                    'roles' => $user['roles'],
                    'actions' => '', // Placeholder for actions
                ];
            }
            if (!empty($user['group_name'])) {
                $formattedData[$userId]['groups'][] = ['name' => $user['group_name']];
            }
        }

        // Convert associative array to indexed array for JSON response
        $formattedData = array_values($formattedData);

        return new JsonResponse([
            'draw' => $request->query->getInt('draw', 1),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFilteredRecords,
            'data' => $formattedData,
        ]);
    }


    #[Route('/admin/groups/data', name: 'groups_data', methods: ['GET'])]
    public function groupsData(Request $request, GroupRepository $groupRepository): JsonResponse
    {
        $start = $request->query->getInt('start', 0);
        $length = $request->query->getInt('length', 10);
        $search = $request->query->get('search')['value'] ?? '';

        $totalRecords = $groupRepository->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $filteredQuery = $groupRepository->createQueryBuilder('g');

        if (!empty($search['value'])) {
            $filteredQuery->andWhere('g.name LIKE :search')
                          ->setParameter('search', '%' . $search['value'] . '%');
        }

        $totalFilteredRecords = $filteredQuery->select('COUNT(g.id)')->getQuery()->getSingleScalarResult();

        $data = $filteredQuery->setFirstResult($start)
                              ->setMaxResults($length)
                              ->getQuery()
                              ->getArrayResult();

        return new JsonResponse([
            'draw' => $request->query->getInt('draw', 1),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFilteredRecords,
            'data' => $data
        ]);
    }
}
