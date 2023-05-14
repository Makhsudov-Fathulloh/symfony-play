<?php

namespace App\Article\Entity;

use App\Article\Repository\ArticleRepository;
use App\User\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\Table(name: 'user')]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(
        name: 'title',
        type: Types::STRING,
        length: 128
    )]
    private ?string $title = null;

    #[ORM\Column(
        name: 'description',
        type: Types::STRING,
        length: 128
    )]
    private ?string $description = null;

    #[ORM\Column(
        type: Types::TEXT
    )]
    private ?string $content = null;

    #[ORM\Column(
        name: 'image',
        type: Types::STRING,
        length: 128,
        nullable: true
    )]
    private ?string $image = null;

    #[ORM\Column(
        name: 'created_at',
        type: Types::INTEGER
    )]
    private int $createdAt;

    #[ORM\Column(
        name: 'updated_at',
        type: Types::INTEGER,
        nullable: true
    )]
    private ?int $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(int $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?int $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
