<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'client:item']),
        new GetCollection(normalizationContext: ['groups' => 'client:list']),
        new Post(normalizationContext: ['groups' => 'client:item']),
        new Delete(normalizationContext: ['groups' => 'client:item']),
        new Put(normalizationContext: ['groups' => 'client:item']),


    ],
    //order: ['year' => 'DESC', 'city' => 'ASC'],
    paginationEnabled: false,
)]
#[UniqueEntity('email', message: 'Cet client existe déjà ',)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['client:list', 'client:item'])]
    private ?int $id = null;

    #[Assert\Type(type: 'alpha', message: 'Vous ne devez entrer que des lettres !')]
    #[ORM\Column(length: 100)]
    #[Groups(['client:list', 'client:item'])]
    private ?string $name = null;

    #[Assert\Type(type: 'alpha', message: 'Vous ne devez entrer que des lettres !')]
    #[ORM\Column(length: 100)]
    #[Groups(['client:list', 'client:item'])]
    private ?string $surname = null;

    #[Assert\Email]
    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['client:list', 'client:item'])]
    private ?string $email = null;

    #[ORM\Column(length: 8)]
    #[Groups(['client:list', 'client:item'])]
    private ?string $codeUser = null;

    //#[Assert\Length(min: 4, max: 4)]
    #[ORM\Column(nullable: true)]
    #[Groups(['client:list', 'client:item'])]
    private ?int $codePin = null;

    //#[Assert\Length(min: 4, max: 4)]
    #[ORM\Column]
    #[Groups(['client:list', 'client:item'])]
    private ?int $ticket = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

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

    public function getCodeUser(): ?string
    {
        return $this->codeUser;
    }

    public function setCodeUser(string $codeUser): self
    {
        $this->codeUser = $codeUser;

        return $this;
    }

    public function getCodePin(): ?int
    {
        return $this->codePin;
    }

    public function setCodePin(?int $codePin): self
    {
        $this->codePin = $codePin;

        return $this;
    }

    public function getTicket(): ?int
    {
        return $this->ticket;
    }

    public function setTicket(?int $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }
}