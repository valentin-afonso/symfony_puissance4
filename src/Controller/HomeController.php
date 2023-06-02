<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GameControllerRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;


class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(GameControllerRepository $gameRepository): Response
    {

        $current_user = $this->getUser();
        $current_user_id = $current_user->getId();
        $games = $gameRepository->findBy(['player1' => $current_user_id]);
        $games2 = $gameRepository->findBy(['player2' => $current_user_id]);

        // TranslatorInterface $translator
        // $game_title = $translator->trans('Connect 4', [], 'messages', $this->getUser()->getLocale());
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'games' => $games,
            'games2' => $games2,
            'locale' => $this->getUser()->getLocale(),
        ]);
    }


    #[Route('/change-language', name: 'app_change_language')]
    public function changeLanguage(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $locale = $request->request->get('locale');
        // $this->getUser()->setLocale($locale);
        // $this->getDoctrine()->getManager()->flush();
        $user = $this->getUser();
        $user->setLocale($locale);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_home');
        
    }
    
}
