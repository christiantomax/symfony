<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Transportation;

#[Route('/api/v1', name: 'api_')]

class TransportationController extends AbstractController
{
    #[Route('/transportation', name: 'transportation_index', methods:['get'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $transportation = $doctrine
            ->getRepository(Transportation::class)
            ->findAll();

        $data = [];
        foreach ($transportation as $item){
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'transportation_type' => $item->getTransportationType(),
                'description' => $item->getDescription(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/transportation', name: 'transportation_create', methods:['post'])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $transportation = new Transportation();
        $transportation->setName($request->get('name'));
        $transportation->setTransportationType($request->get('transportation_type'));
        $transportation->setDescription($request->get('description'));

        $entityManager->persist($transportation);
        $entityManager->flush();

        $data = [
            'id' => $transportation->getId(),
            'name' => $transportation->getName(),
            'transportation_type' => $transportation->getTransportationType(),
            'description' => $transportation->getDescription(),
        ];

        return $this->json($data);
    }

    #[Route('/transportation/{id}', name: 'transportation_show', methods:['get'])]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $transportation = $doctrine
            ->getRepository(Transportation::class)
            ->find($id);

        if (!$transportation){
            return $this->json('No project found for id '.$id, 404);
        }

        $data[] = [
            'id' => $transportation->getId(),
            'name' => $transportation->getName(),
            'transportation_type' => $transportation->getTransportationType(),
            'description' => $transportation->getDescription(),
        ];

        return $this->json($data);
    }

    #[Route('/transportation/{id}', name: 'transportation_update', methods:['put', 'patch'])]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $transportation = $entityManager
            ->getRepository(Transportation::class)
            ->find($id);
        
        if (!$transportation){
            return $this->json('No project found for id '.$id, 404);
        }

        if ($request->get('name')) {
            $transportation->setName($request->get('name'));
        }
        
        if ($request->get('transportation_type')) {
            $transportation->setTransportationType($request->get('transportation_type'));
        }
        
        if ($request->get('description')) {
            $transportation->setDescription($request->get('description'));
        }
        $entityManager->flush();

        $data[] = [
            'id' => $transportation->getId(),
            'name' => $transportation->getName(),
            'transportation_type' => $transportation->getTransportationType(),
            'description' => $transportation->getDescription(),
        ];

        return $this->json($data);
    }

    #[Route('/transportation/{id}', name: 'transportation_delete', methods:['delete'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $transportation = $entityManager
            ->getRepository(Transportation::class)
            ->find($id);

        if (!$transportation){
            return $this->json('No project found for id '.$id, 404);
        }

        $entityManager->remove($transportation);
        $entityManager->flush();

        return $this->json('Deleted a transportation data successfully with id '.$id);
    }
}
