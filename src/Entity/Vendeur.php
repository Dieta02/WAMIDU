<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\VendeurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: VendeurRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'vendeur:item']),
        new GetCollection(normalizationContext: ['groups' => 'vendeur:list']),
        new Post(normalizationContext: ['groups' => 'vendeur:item']),
        new Delete(normalizationContext: ['groups' => 'vendeur:item']),
        new Put(normalizationContext: ['groups' => 'vendeur:item']),


    ],
    //order: ['year' => 'DESC', 'city' => 'ASC'],
    paginationEnabled: false,
)]
class Vendeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vendeur:list', 'vendeur:item'])]
    private ?int $id = null;

    #[Assert\Type(type: 'alpha', message: 'Vous ne devez entrer que des lettres !')]
    #[ORM\Column(length: 100)]
    #[Groups(['vendeur:list', 'vendeur:item'])]
    private ?string $name = null;

    #[Assert\Type(type: 'alpha', message: 'Vous ne devez entrer que des lettres !')]
    #[ORM\Column(length: 100)]
    #[Groups(['vendeur:list', 'vendeur:item'])]
    private ?string $surname = null;

    //#[Assert\Unique(message: 'Cette adresse email à déja été enregistrée !')]
    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['vendeur:list', 'vendeur:item'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['vendeur:list', 'vendeur:item'])]
    private ?string $password = null;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}