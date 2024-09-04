<?php

namespace App\Entity;

use App\Repository\PlayRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayRepository::class)]
class Play
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: "moves")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $receivedAt = null;

    #[ORM\Column(length: 70)]
    private ?string $proposedCode = null;

    #[ORM\Column(length: 50)]
    private ?string $evaluationResult = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getReceivedAt(): ?\DateTimeImmutable
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(\DateTimeImmutable $receivedAt): static
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    public function getProposedCode(): ?string
    {
        return $this->proposedCode;
    }

    public function setProposedCode(string $proposedCode): static
    {
        $this->proposedCode = $proposedCode;

        return $this;
    }

    public function getEvaluationResult(): ?string
    {
        return $this->evaluationResult;
    }

    public function setEvaluationResult(string $evaluationResult): static
    {
        $this->evaluationResult = $evaluationResult;

        return $this;
    }
}
