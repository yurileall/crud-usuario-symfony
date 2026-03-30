<?php

namespace App\Controller;

use App\Service\PetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PetController extends AbstractController
{
    private $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    #[Route('api/pet', name: 'pet,index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->petService->todosPet(), 200, []);
    }

    #[Route('api/pet', name: 'pet.create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dados = $request->toArray();
        $user = $this->getUser();

        $pet = $this->petService->createPet($user, $dados);

        return $this->json([
            'msg' => "Pet criado com sucesso",
            'pet' => [
                'id' => $pet->getId(),
                'nome' => $pet->getNome(),
                'Dono' => $pet->getUser()->getNome()
            ]
        ]);
    }
}
