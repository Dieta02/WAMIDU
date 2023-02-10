<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HistoriqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueRepository::class)]
#[ApiResource]
class Historique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $client = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_h = null;

    #[ORM\Column]
    private ?int $vendeur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?int
    {
        return $this->client;
    }

    public function setClient(int $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getDateH(): ?\DateTimeInterface
    {
        return $this->date_h;
    }

    public function setDateH(\DateTimeInterface $date_h): self
    {
        $this->date_h = $date_h;

        return $this;
    }

    public function getVendeur(): ?int
    {
        return $this->vendeur;
    }

    public function setVendeur(int $vendeur): self
    {
        $this->vendeur = $vendeur;

        return $this;
    }
}
