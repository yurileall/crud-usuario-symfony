<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    //Listar todos os usuários
    #[Route('/users', methods: ['GET'],  name: 'user.index')]
    public function index(): JsonResponse
    {
        return $this->json($this->userService->getAllUsers());
    }

    // Criar Usuários
    #[Route('/users', methods: ['POST'], name: 'usersCreate.create')]
    public function create(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $this->userService->createUser($data['nome'], $data['email'], $data['senha']);

        return $this->json(
            [
                'msg' => 'Usuário criado com sucesso!'
            ],
            201
        );
    }


    //Editar Usuários
    #[Route('/users/{id}', methods: ['PUT'], name: 'usersUpdate.update')]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = $request->toArray();

        $users = $this->userService->updateUser($id, $data['nome'] ?? null, $data['email'] ?? null, $data['senha'] ?? null);

        if (!$users) {
            return $this->json([
                'msg' => "Usuário não encontrado"
            ], 404);
        }

        return $this->json([
            'msg' => "Usuário com email {$users->getEmail()} foi editado com sucesso"
        ], 200);
    }

    #[Route('/users/{id}', methods: ['DELETE'], name: 'usersDelete.delete')]
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->userService->deleteUser($id);

        if (!$deleted) {
            return $this->json([
                'msg' => "Usuário não encontrado"
            ], 404);
        }

        return $this->json(null, 204);
    }
}
