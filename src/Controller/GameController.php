<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GameControllerRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Move;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MoveRepository;
use Psr\Log\LoggerInterface;

class GameController extends AbstractController
{
    #[Route('/game/{id}', name: 'app_game')]
    public function index(int $id, GameControllerRepository $gameRepository, MoveRepository $moveRepository,EntityManagerInterface $entityManager, Request $request, LoggerInterface $logger): Response
    {

        $current_user = $this->getUser();
        $current_user_email = $current_user->getUserIdentifier();

        $game = $gameRepository->findOneBy(['id' => $id]);
        $player1 = $game->getPlayer1();
        $player2 = $game->getPlayer2();
        $current_turn = $game->getCurrentTurn();
        $your_turn = false;
        if ($current_turn == 1) {
            $player_turn = $player1->getEmail();
        } else {
            $player_turn = $player2->getEmail();;
        }

        if ($player_turn == $current_user_email) {
            $your_turn = true;
        }

        $col = $request->query->get('col');
        
        if ($col != null) {
            $cols = $moveRepository->findBy(['columnName' => $col, 'game' => $game]);
            switch (count($cols)) {
                case 0:
                    $row = 5;
                    break;
                case 1:
                    $row = 4;
                    break;
                case 2:
                    $row = 3;
                    break;
                case 3:
                    $row = 2;
                    break;
                case 4:
                    $row = 1;
                    break;
                case 5:
                    $row = 0;
                    break;
            }
            $logger->info("Le joueur ". $current_user->getId() ." a joué en x: $col y: $row");
            $move = new Move();
            $move->setColumnName($col);
            $move->setRowName($row);
            $move->setGame($game);
            $move->setPlayer($current_user->getId());
            if ($current_turn == 1) {
                $game->setCurrentTurn(2);
            } else {
                $game->setCurrentTurn(1);
            }
            $entityManager->persist($move);
            $entityManager->persist($game);
            $entityManager->flush();

            // check win
            // $this->isWin($moveRepository, $game);

            return $this->redirectToRoute('app_game', ["id" => $game->getId()]);
        }
    
        $moves = $moveRepository->findBy(['game' => $game]);
        $winner = $this->isWin($moveRepository, $game);
        if ($winner != null) {
            $logger->info("Le joueur ". $winner ." a gagné");
        }

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'gameId' => $id,
            'player1' => $player1,
            'player2' => $player2,
            'playerTurn' => $player_turn,
            'yourTurn' => $your_turn,
            'moves' => $moves,
            'current_user_email' => $current_user_email,
            'winner' => $winner,
            'locale' => $this->getUser()->getLocale(),
        ]);
    }

    function isWin($moveRepository, $game) {
        $cols = $moveRepository->findBy(['game' => $game]);
        $grid = array_fill(0, 7, array_fill(0, 6, null));

        foreach ($cols as $col){
            $row = $col->getRowName();
            $column = $col->getColumnName();
            $player = $col->getPlayer();
            $grid[$column][$row] = $player;   
        }
    
        for ($column = 0; $column < 7; $column++) {
            for ($row = 0; $row < 6; $row++) {
                $player = $grid[$column][$row];

                if ($player !== null) {
                    // Vérifier les combinaisons possibles de puissance 4 dans les directions : droite, bas, diagonale droite, diagonale gauche
                    if ($this->checkFourInARow($grid, $column, $row, 1, 0)) {
                        return $player;
                    }
                    if ($this->checkFourInARow($grid, $column, $row, 0, 1)) {
                        return $player;
                    }
                    if ($this->checkFourInARow($grid, $column, $row, 1, 1)) {
                        return $player;
                    }
                    if ($this->checkFourInARow($grid, $column, $row, 1, -1)) {
                        return $player;
                    }
                }
            }
        }
        return null; // Aucun vainqueur trouvé
    }

    // Vérifie s'il y a une combinaison de puissance 4 à partir d'une position donnée dans une direction donnée
    function checkFourInARow($grid, $column, $row, $deltaX, $deltaY) {
        $player = $grid[$column][$row];
        $count = 0;

        for ($i = 0; $i < 4; $i++) {
            $newColumn = $column + $i * $deltaX;
            $newRow = $row + $i * $deltaY;

            // Vérifier si la position est en dehors de la grille
            if ($newColumn < 0 || $newColumn >= 7 || $newRow < 0 || $newRow >= 6) {
                return false;
            }

            // Vérifier si la case contient le même joueur
            if ($grid[$newColumn][$newRow] === $player) {
                $count++;
            } else {
                return false;
            }
        }

        return $count === 4;
    }

}

