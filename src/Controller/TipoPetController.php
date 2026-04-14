<?php

namespace App\Controller;

use App\Service\TipoPetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TipoPetController extends AbstractController
{

    private TipoPetService $tipoPetService;

    public function __construct(TipoPetService $tipoPetService)
    {
        $this->tipoPetService = $tipoPetService;
    }

    #[Route('api/tipo/pet', name: 'tipoPet.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json(
            $this->tipoPetService->todosTipoPet(),
            200,
            []
        );
    }

    #[Route('api/tipo/pet', name: 'tipoPet.create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dados = $request->toArray();
        $tipoPet = $this->tipoPetService->createTipoPet($dados);

        if (!$tipoPet) {
            return $this->json([
                'msg' => "Erro ao criar tipo de pet!"
            ], 400);
        }

        return $this->json([
            'msg' => "Tipo de pet criado com sucesso!",
            'tipoPet' => [
                'id' => $tipoPet->getId(),
                'nome' => $tipoPet->getNome()
            ]
        ], 201);
    }

    #[Route('api/tipo/pet/{id}', name: 'tipoPet.update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $dados = $request->toArray();
        $tipoPetUpdate = $this->tipoPetService->updateTipoPet($id, $dados);

        if (!$tipoPetUpdate) {
            return $this->json([
                'msg' => "Erro ao atualizar tipo de pet!"
            ], 400);
        }

        return $this->Json([
            'msg' => "Tipo de pet atualizado com sucesso!",
            'tipoPet' => [
                'tipoPet' => $tipoPetUpdate->getId(),
                'nome' => $tipoPetUpdate->getNome()
            ]
        ], 200);
    }

    #[Route('api/tipo/pet/{id}', name: 'tipoPet.delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $tipPetDelete = $this->tipoPetService->deleteTipoPet($id);

        if (!$tipPetDelete) {
            return $this->json([
                'msg' => "Erro ao deletar tipo de pet!"
            ], 400);
        }

        return $this->json([
            'msg' => "Tipo Pet deletado com sucesso!"
        ], 200);
    }
}
