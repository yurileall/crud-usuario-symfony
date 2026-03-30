<?php

namespace App\Service;

use App\Entity\Pet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PetService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function todosPet(): array
    {
        return $this->em->getRepository(Pet::class)->findAll();
    }

    public function createPet(User $user,  $dados): Pet
    {
        $nome = $dados['nome'];
        $dataNascimentoStr = $dados['dataNascimento'];
        $dataNascimento = \DateTime::createFromFormat('d/m/Y', $dataNascimentoStr);

        if (!$dataNascimento) {
            throw new \InvalidArgumentException("Formato de data inválido: $dataNascimentoStr");
        }

        $pet = new Pet();
        $pet->setNome($nome);
        $pet->setDataNascimento($dataNascimento);
        $pet->setUser($user);

        $this->em->persist($pet);
        $this->em->flush();

        return $pet;
    }
}
