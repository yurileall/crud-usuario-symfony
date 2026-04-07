<?php

namespace App\Service;

use App\Entity\TipoPet;
use Doctrine\ORM\EntityManagerInterface;

class TipoPetService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function todosTipoPet(): array
    {
        return $this->em->getRepository(TipoPet::class)->findAll();
    }

    public function createTipoPet($dados): TipoPet
    {
        $tipoPet = new TipoPet();
        $tipoPet->setNome($dados['nome']);

        $this->em->persist($tipoPet);
        $this->em->flush();

        return $tipoPet;
    }
}
