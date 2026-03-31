<?php

namespace App\Service;

use App\Entity\Pet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;

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

    public function updatePet(int $idPet, array $dados): ?Pet
    {
        $pet = $this->em->getRepository(Pet::class)->find($idPet);

        if (!$pet) {
            throw new \InvalidArgumentException("Pet não encontrado");
        }

        if (!empty($dados['nome'])) {
            $pet->setNome($dados['nome']);
        }

        if (!empty($dados['dataNascimento'])) {
            $dataNascimento = \DateTime::createFromFormat('d/m/Y', $dados['dataNascimento'])
                ?:  throw new \InvalidArgumentException("Formato de data inválido: {$dados['dataNascimento']}");

            $pet->setDataNascimento($dataNascimento);
        }

        $this->em->flush();

        return $pet;
    }

    public function delelePet(int $idPet): bool
    {
        $pet = $this->em->getRepository(Pet::class)->find($idPet);

        if (empty($pet)) {
            return false;
        }

        $this->em->remove($pet);
        $this->em->flush();

        return true;
    }
}
