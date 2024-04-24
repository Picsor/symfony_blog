<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $mainDish = null;

    #[ORM\Column(length: 255)]
    private ?string $startDish = null;

    #[ORM\Column(length: 255)]
    private ?string $desert = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMainDish(): ?string
    {
        return $this->mainDish;
    }

    public function setMainDish(string $mainDish): static
    {
        $this->mainDish = $mainDish;

        return $this;
    }

    public function getStartDish(): ?string
    {
        return $this->startDish;
    }

    public function setStartDish(string $startDish): static
    {
        $this->startDish = $startDish;

        return $this;
    }

    public function getDesert(): ?string
    {
        return $this->desert;
    }

    public function setDesert(string $desert): static
    {
        $this->desert = $desert;

        return $this;
    }
}
