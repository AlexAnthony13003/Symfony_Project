<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('name')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min:2, max:50)]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[Assert\NotNull()]
    #[Assert\Positive()]
    #[Assert\LessThan(600)]
    private ?int $time = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull()]
    #[Assert\Positive()]
    #[Assert\LessThan(13)]
    private ?int $number_of_persons = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(6)]
    private ?int $difficulty = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank()]
    private string $description ;

    #[ORM\Column]
    #[Assert\Positive()]
    #[Assert\LessThan(1001)]
    private ?float $price = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isFavorite;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity:Ingredient::class)]
    private $ingredients;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function setUpdatedAtValue(){
        $this->updatedAt = new \DateTimeImmutable();
    }

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

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getNumberOfPersons(): ?int
    {
        return $this->number_of_persons;
    }

    public function setNumberOfPersons(?int $number_of_persons): self
    {
        $this->number_of_persons = $number_of_persons;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(?int $difficulty): self
    {
        $this->difficulty = $difficulty;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIsFavorite(): ?int
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(int $isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIngredients(): ArrayCollection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredients): self
    {
        if(!$this->ingredients->contains($ingredients)){
            $this->ingredients[] = $ingredients;
        }
        return $this;
    }
    public function removeIngredient(Ingredient $ingredient): self
    {
            $this->ingredients->removeElement($ingredient);
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
