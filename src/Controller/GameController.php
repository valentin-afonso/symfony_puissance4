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

class GameController extends AbstractController
{
    #[Route('/game/{id}', name: 'app_game')]
    public function index(int $id, GameControllerRepository $gameRepository, MoveRepository $moveRepository,EntityManagerInterface $entityManager, Request $request): Response
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
        var_dump($player_turn);

        // $row = $request->query->get('row');
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
            // var_dump($col);
            // var_dump($row);

            
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
            return $this->redirectToRoute('app_game', ["id" => $game->getId()]);
        }
    
        $moves = $moveRepository->findBy(['game' => $game]);

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'gameId' => $id,
            'player1' => $player1,
            'player2' => $player2,
            'playerTurn' => $player_turn,
            'yourTurn' => $your_turn,
            'moves' => $moves,
        ]);
    }
}
