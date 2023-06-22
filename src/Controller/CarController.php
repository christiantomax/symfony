<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Car;

#[Route('/api/v1', name: 'api_')]

class CarController extends AbstractController
{
    
    #[Route('/car', name: 'car_index', methods:['get'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $transportation = $doctrine
            ->getRepository(Car::class)
            ->findAll();

        $data = [];
        foreach ($transportation as $item){
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'transportation_type' => $item->getTransportationType(),
                'description' => $item->getDescription(),
                'seat' => $item->getSeat(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/car', name: 'car_create', methods:['post'])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $transportation = new Car();
        $transportation->setName($request->get('name'));
        $transportation->setTransportationType($request->get('transportation_type'));
        $transportation->setDescription($request->get('description'));
        $transportation->setSeat($request->get('seat'));

        $entityManager->persist($transportation);
        $entityManager->flush();

        $data = [
            'id' => $transportation->getId(),
            'name' => $transportation->getName(),
            'transportation_type' => $transportation->getTransportationType(),
            'description' => $transportation->getDescription(),
            'seat' => $transportation->getSeat(),
        ];

        return $this->json($data);
    }

    #[Route('/car/{id}', name: 'car_show', methods:['get'])]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $transportation = $doctrine
        ->getRepository(Car::class)
        ->findAll();
        
        $data = [];
        foreach ($transportation as $item){
            if($item->getId() == $id){
                $data[] = [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'transportation_type' => $item->getTransportationType(),
                    'description' => $item->getDescription(),
                    'seat' => $item->getSeat(),
                ];
                return $this->json($data);
            }
        }

        return $this->json('No project found for id '.$id, 404);
    }

    #[Route('/car/{id}', name: 'car_update', methods:['put', 'patch'])]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $transportation = $entityManager
            ->getRepository(Car::class)
            ->findAll();

        $data = null;
        foreach ($transportation as $item){
            if($item->getId() == $id){
                $data = $item;
                break;
            }
        }

        if (!$data) {
            return $this->json('No project found for id '.$id, 404);
        }

        if ($request->get('name')) {
            $data->setName($request->get('name'));
        }
        
        if ($request->get('transportation_type')) {
            $data->setTransportationType($request->get('transportation_type'));
        }
        
        if ($request->get('description')) {
            $data->setDescription($request->get('description'));
        }

        if ($request->get('seat')) {
            $data->setSeat($request->get('seat'));
        }
        $entityManager->flush();

        return $this->json(
            [
                'id' => $data->getId(),
                'name' => $data->getName(),
                'transportation_type' => $data->getTransportationType(),
                'description' => $data->getDescription(),
                'seat' => $data->getSeat(),
            ]
        );
    }

    #[Route('/car/{id}', name: 'car_delete', methods:['delete'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $transportation = $entityManager
            ->getRepository(Car::class)
            ->findAll();

        $data = null;
        foreach ($transportation as $item){
            if($item->getId() == $id){
                $data = $item;
                break;
            }
        }

        if (!$data) {
            return $this->json('No project found for id '.$id, 404);
        }

        $entityManager->remove($data);
        $entityManager->flush();

        return $this->json('Deleted a transportation data successfully with id '.$id);
    }
}
