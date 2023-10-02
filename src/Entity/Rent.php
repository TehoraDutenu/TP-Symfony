<?php

namespace App\Entity;

use App\Repository\RentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentRepository::class)]
class Rent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'rents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeRent $typeRent = null;

    #[ORM\Column]
    private ?bool $has_owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getTypeRent(): ?TypeRent
    {
        return $this->typeRent;
    }

    public function setTypeRent(?TypeRent $typeRent): static
    {
        $this->typeRent = $typeRent;

        return $this;
    }

    public function isHasOwner(): ?bool
    {
        return $this->has_owner;
    }

    public function setHasOwner(bool $has_owner): static
    {
        $this->has_owner = $has_owner;

        return $this;
    }
}
