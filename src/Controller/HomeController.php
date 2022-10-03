<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]

    public function index(): Response
    {
        $result = 10 * 3;
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'result' => $result
        ]);
    }

    #[Route('/home/login', name: 'app_login')]

    public function login(): Response
    {
        return $this->render('home/login.html.twig', [
            'controller_name' => 'HomeController->Login',
        ]);
    }
}
