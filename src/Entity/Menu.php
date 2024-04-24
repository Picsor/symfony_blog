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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $starter = null;

    #[ORM\Column(length: 255)]
    private ?string $dish = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dessert = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStarter(): ?string
    {
        return $this->starter;
    }

    public function setStarter(?string $starter): static
    {
        $this->starter = $starter;

        return $this;
    }

    public function getDish(): ?string
    {
        return $this->dish;
    }

    public function setDish(string $dish): static
    {
        $this->dish = $dish;

        return $this;
    }

    public function getDessert(): ?string
    {
        return $this->dessert;
    }

    public function setDessert(?string $dessert): static
    {
        $this->dessert = $dessert;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
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
}
