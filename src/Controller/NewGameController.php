<?php

namespace App\Controller;

use App\Entity\GameController;
use App\Form\GameFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class NewGameController extends AbstractController
{
    #[Route('/new/game', name: 'app_new_game')]
    public function index(UserRepository $userRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $game = new GameController();

        $form = $this->createForm(GameFormType::class,$game);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $board = "new board";
            $current_turn = 1;
    
            $game->setBoard($board);
            $game->setCurrentTurn($current_turn);

            $entityManager->persist($game);
            $entityManager->flush();

            // dump($game);
            return $this->redirectToRoute('app_game', ["id" => $game->getId()]);
        }

        return $this->render('new_game/index.html.twig', [
            'newGameForm' => $form->createView(),
        ]);
    }
}
