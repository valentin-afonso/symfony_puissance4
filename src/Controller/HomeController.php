<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GameControllerRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(GameControllerRepository $gameRepository): Response
    {

        $current_user = $this->getUser();
        $current_user_id = $current_user->getId();
        $games = $gameRepository->findBy(['player1' => $current_user_id]);
        $games2 = $gameRepository->findBy(['player2' => $current_user_id]);
        // findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'games' => $games,
            'games2' => $games2,
        ]);
    }
}
