<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Message;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user_read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['user_read'])]
    #[Assert\NotBlank(groups: ['create'], message: "O nome é obrigatório.")]
    #[Assert\Length(min: 3, groups: ['create', 'update'], minMessage: 'O nome deve conter pelo menos {{ limit }} caracteres.')]
    private ?string $nome = null;

    #[ORM\Column(length: 150, unique: true)]
    #[Groups(['user_read'])]
    #[Assert\NotBlank(groups: ['create'], message: "O email é obrigatório.")]
    #[Assert\Email(groups: ['create', 'update'], message: "O email '{{value}}' não é válido.")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['create'], message: "A senha é obrigatória.")]
    #[Assert\Length(min: 6, groups: ['create', 'update'], minMessage: 'A senha deve conter pelo menos {{limit}} caracteres.')]
    private ?string $senha = null;

    #[Groups(['user_read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $flgAtivo = true;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $dataCadastro = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $dataAtualizacao = null;

    public function __construct()
    {
        $this->dataCadastro = new \DateTimeImmutable();
    }

    // #[ORM\PrePersist]
    // public function setDataCadastro(): void
    // {
    //     $this->dataCadastro = new \DateTimeImmutable();
    // }

    #[ORM\PreUpdate]
    public function setDataAtualizacao(): void
    {
        $this->dataAtualizacao = new \DateTime();
    }

    public function getDataCadastro(): \DateTimeImmutable
    {
        return $this->dataCadastro;
    }

    public function getDataAtualizacao(): ?\DateTime
    {
        return $this->dataAtualizacao;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): self
    {
        $this->senha = $senha;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function isFlgAtivo(): bool
    {
        return $this->flgAtivo;
    }

    public function eraseCredentials(): void {}

    public function setFlgAtivo(bool $flgAtivo): self
    {
        $this->flgAtivo = $flgAtivo;
        return $this;
    }
}
