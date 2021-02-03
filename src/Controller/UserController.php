<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion(): Response
    {
        return $this->render('user/connexion.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/compte", name="compte")
     */
    public function compte(): Response
    {
        return $this->render('user/compte.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
