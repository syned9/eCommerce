<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(SessionInterface $session): Response
    {
        if($session->get("connexionEtat") == "connect") {
            $connexionEtat = "connect";
        } else {
            $connexionEtat = "";
        }

        return $this->render('home/index.html.twig', [
            "connexionEtat" => $connexionEtat
        ]);
    }
}
