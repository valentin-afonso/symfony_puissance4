<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAll();
  

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' =>$users,
        ]);
    }
}
