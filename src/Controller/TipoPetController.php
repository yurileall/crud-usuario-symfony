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

    #[Route('/tipo/pet', name: 'tipoPet.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json(
            $this->tipoPetService->todosTipoPet(),
            200,
            []
        );
    }

    #[Route('/tipo/pet', name: 'tipoPet.create', methods: ['POST'])]
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
}
