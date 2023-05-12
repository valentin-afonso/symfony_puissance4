<?php

namespace App\Entity;

use App\Repository\MoveRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoveRepository::class)]
class Move
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: GameController::class, cascade: ['persist', 'remove'])]
    private ?GameController $game = null;
    
    #[ORM\Column(length: 255)]
    private ?int $rowName = null;

    #[ORM\Column(length: 255)]
    private ?int $columnName = null;

    #[ORM\Column]
    private ?int $player = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?GameController
    {
        return $this->game;
    }

    public function setGame(?GameController $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getRowName(): ?int
    {
        return $this->rowName;
    }

    public function setRowName(int $rowName): self
    {
        $this->rowName = $rowName;

        return $this;
    }

    public function getColumnName(): ?int
    {
        return $this->columnName;
    }

    public function setColumnName(int $columnName): self
    {
        $this->columnName = $columnName;

        return $this;
    }

    public function getPlayer(): ?int
    {
        return $this->player;
    }

    public function setPlayer(int $player): self
    {
        $this->player = $player;

        return $this;
    }
}
