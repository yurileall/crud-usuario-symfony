<?php

namespace App\Entity;

use App\Repository\UserViewRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserViewRepository::class)]
#[ORM\Table(name: 'view_user')]
class UserView
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $nome;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $dataNascimento;

    #[ORM\Column(type: "integer")]
    private int $idadeUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDataNascimento(): \DateTimeInterface
    {
        return $this->dataNascimento;
    }

    public function getIdadeUser(): int
    {
        return $this->idadeUser;
    }
}
