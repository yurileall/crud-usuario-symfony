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

    #[Route('api/pet', name: 'pet.index', methods: ['GET'])]
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
                'idUser' => $pet->getUser()->getId(),
                'user' => $pet->getUser()->getNome()
            ]
        ]);
    }

    #[Route('api/pet/{idPet}', name: 'pet.update', methods: ['PUT'])]
    public function update(int $idPet, Request $request): JsonResponse
    {
        $dados = $request->toArray();

        $pet = $this->petService->updatePet($idPet, $dados);

        if (!$pet) {
            $this->json([
                'msg' => "Pet não encontado!"
            ], 404);
        }

        return $this->json([
            'msg' => "Pet criado com sucesso!",
            'pet' => [
                'nome' => $pet->getNome(),
                'dataNascimento' => $pet->getDataNascimento()->format('d/m/Y'),
                'idUser' => $pet->getUser()->getId(),
                'user' => $pet->getUser()->getNome()

            ]
        ], 200, [], ['json_encode_options' => JSON_UNESCAPED_SLASHES]);
    }

    #[Route('api/pet/{idPet}', name: 'pet.delete', methods: ['DELETE'])]
    public function delete(int $idPet)
    {
        $deletePet = $this->petService->delelePet($idPet);

        if (empty($deletePet)) {
            return $this->json([
                'msg' => "Pet não encontrado!"
            ], 404);
        }

        return $this->json([
            'msg' => "Pet deletado com sucesso!"
        ], 200);
    }
}
