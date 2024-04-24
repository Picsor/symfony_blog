<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 20)]
    private ?string $date = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
    
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $title_regex = '/^.{8,}$/';
        $title_msg = 'Title must be at least 8 characters long';
        $metadata->addPropertyConstraints('title', [
            new Assert\NotBlank(),
            new Assert\Regex([
                'pattern' => $title_regex,
                'message' => $title_msg
            ])
        ]);
    
        $date_regex = '/^\d{2}\/\d{2}\/\d{4}$/';
        $date_msg = 'Date must be in the format dd/mm/yyyy';
        $metadata->addPropertyConstraints('date', [
            new Assert\NotBlank(),
            new Assert\Regex([
                'pattern'=> $date_regex,
                'message'=> $date_msg
            ])
        ]);

        $content_regex = '/^.{20,200}$/';
        $content_msg = 'Content must be between 20 and 200 characters long';
        $metadata->addPropertyConstraints('content', [
            new Assert\NotBlank(),
            new Assert\Regex([
                'pattern'=> $content_regex,
                'message'=> $content_msg
            ])
        ]);
    }
}
