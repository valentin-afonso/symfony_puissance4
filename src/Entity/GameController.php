<?php

namespace App\Entity;

use App\Repository\GameControllerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameControllerRepository::class)]
class GameController
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'gameControllers')]
    private ?User $player1 = null;

    #[ORM\ManyToOne(inversedBy: 'gameControllers')]
    private ?User $player2 = null;

    #[ORM\Column(length: 255)]
    private ?string $board = null;

    #[ORM\Column(nullable: true)]
    private ?int $winner = null;

    #[ORM\Column]
    private ?int $currentTurn = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer1(): ?User
    {
        return $this->player1;
    }

    public function setPlayer1(?User $player1): self
    {
        $this->player1 = $player1;

        return $this;
    }

    public function getPlayer2(): ?User
    {
        return $this->player2;
    }

    public function setPlayer2(?User $player2): self
    {
        $this->player2 = $player2;

        return $this;
    }

    public function getBoard(): ?string
    {
        return $this->board;
    }

    public function setBoard(string $board): self
    {
        $this->board = $board;

        return $this;
    }

    public function getWinner(): ?int
    {
        return $this->winner;
    }

    public function setWinner(?int $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getCurrentTurn(): ?int
    {
        return $this->currentTurn;
    }

    public function setCurrentTurn(int $currentTurn): self
    {
        $this->currentTurn = $currentTurn;

        return $this;
    }
}
