<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
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

        if ($senha) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $senha);
            $user->setSenha($hashedPassword);
        }

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

        if ($email) {
            $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser && $existingUser->getId() !== $id) {
                throw new \Exception("O email '{$email}' já está em uso por outro usuário.");
            }
            $user->setEmail($email);
        }

        if ($email) $user->setEmail($email);

        if ($senha) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $senha);
            $user->setSenha($hashedPassword);
        }

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
