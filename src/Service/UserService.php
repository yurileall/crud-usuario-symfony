<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //Listar todos Usuários
    public function getAllUsers(): array
    {
        return $this->em->getRepository(User::class)->findAll();
    }

    //Criar Usuários
    public function createUser(string $nome, string $email, string $senha): User
    {
        $user = new User();
        $user->setNome($nome);
        $user->setEmail($email);
        $user->setSenha($senha);
        $user->setFlgAtivo(true);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    //Editar Usuários
    public function updateUser(int $id, ?string $nome, ?string $email, ?string $senha): ?User
    {
        $user = $this->em->getRepository(User::class)->find($id);

        if (!$user) {
            return null;
        }

        if ($nome) $user->setNome($nome);
        if ($email) $user->setEmail($email);
        if ($senha) $user->setSenha($senha);

        $this->em->flush();

        return $user;
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->em->getRepository(User::class)->find($id);

        if (!$user) {
            return false;
        }

        $this->em->remove($user);
        $this->em->flush();

        return true;
    }
}
