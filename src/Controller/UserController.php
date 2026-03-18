<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

    private UserService $userService;

    public function __construct(
        UserService $userService,

        #[Autowire('%env(API_TOKEN)%')]
        private string $apiToken

    ) {
        $this->userService = $userService;
    }

    public function validarToken($tokenAuthorization): bool
    {
        if (!$tokenAuthorization) {
            return false;
        }

        $token = str_replace('Bearer ', '', $tokenAuthorization);

        return $token === $this->apiToken;
    }

    //Listar todos os usuários
    #[Route('/users', methods: ['GET'],  name: 'user.index')]
    public function index(Request $request): JsonResponse
    {
        $tokenHeadersAutorization = $request->headers->get('Authorization');

        if (!$this->validarToken($tokenHeadersAutorization)) {
            return $this->json(['msg' => "Acesso negado: token inválido"], 401);
        }

        return $this->json($this->userService->getAllUsers(), 200, [], ['groups' => 'user_read']);
    }

    // Criar Usuários
    #[Route('/users', methods: ['POST'], name: 'usersCreate.create')]
    public function create(Request $request): JsonResponse
    {
        $tokenHeadersAutorization = $request->headers->get('Authorization');

        if (!$this->validarToken($tokenHeadersAutorization)) {
            return $this->json(['msg' => "Acesso negado: token inválido"], 401);
        }

        $data = $request->toArray();

        $user = $this->userService->createUser($data['nome'], $data['email'], $data['senha']);

        return $this->json(
            [
                'msg' => 'Usuário criado com sucesso!',
                'user' => [
                    'id' => $user->getId(),
                    'nome' => $user->getNome(),
                    'email' => $user->getEmail(),
                ]
            ],
            201
        );
    }


    //Editar Usuários
    #[Route('/users/{id}', methods: ['PUT'], name: 'usersUpdate.update')]
    public function update(int $id, Request $request): JsonResponse
    {
        $tokenHeadersAutorization = $request->headers->get('Authorization');

        if (!$this->validarToken($tokenHeadersAutorization)) {
            return $this->json(['msg' => "Acesso negado: token inválido"], 401);
        }

        $data = $request->toArray();

        $user = $this->userService->updateUser($id, $data['nome'] ?? null, $data['email'] ?? null, $data['senha'] ?? null);

        if (!$user) {
            return $this->json([
                'msg' => "Usuário não encontrado"
            ], 404);
        }

        return $this->json([
            'msg' => "Usuário com email {$user->getEmail()} foi editado com sucesso",
            'user' => [
                'id' => $user->getId(),
                'nome' => $user->getNome(),
                'email' => $user->getEmail(),
            ]
        ], 200);
    }

    //Deletar Usuários
    #[Route('/users/{id}', methods: ['DELETE'], name: 'usersDelete.delete')]
    public function delete(int $id, Request $request): JsonResponse
    {

        $tokenHeadersAutorization = $request->headers->get('Authorization');

        if (!$this->validarToken($tokenHeadersAutorization)) {
            return $this->json(['msg' => "Acesso negado: token inválido"], 401);
        }

        $deleted = $this->userService->deleteUser($id);

        if (!$deleted) {
            return $this->json([
                'msg' => "Usuário não encontrado"
            ], 404);
        }

        return $this->json(null, 204);
    }
}
