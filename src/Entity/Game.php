<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 70)]
    private ?string $colorCode = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    /**
     * @var Collection<int, Play>
     */
    #[ORM\OneToMany(targetEntity: Play::class, mappedBy: 'game', cascade: ['persist', 'remove'])]
    private Collection $moves;
    
    public function __construct()
    {
        $this->moves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getColorCode(): ?string
    {
        return $this->colorCode;
    }

    public function setColorCode(string $colorCode): static
    {
        $this->colorCode = $colorCode;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getMoves(): Collection
    {
        return $this->moves;
    }

    public function addMove(Play $move): static
    {
        if (!$this->moves->contains($move)) {
            $this->moves->add($move);
            $move->setGame($this);
        }

        return $this;
    }

    public function removeMove(Play $move): static
    {
        if ($this->moves->removeElement($move)) {
            // set the owning side to null (unless already changed)
            if ($move->getGame() === $this) {
                $move->setGame(null);
            }
        }

        return $this;
    }
}
