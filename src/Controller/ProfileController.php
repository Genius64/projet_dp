<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $username = 'username';
        $email = 'user@email.com';
        $password = '***********';
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ]);
    }
}
