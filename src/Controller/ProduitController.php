<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index(ProduitRepository $produitRepo, SessionInterface $session): Response
    {
        if($session->get("connexionEtat") == "connect") {
            $connexionEtat = "connect";
        } else {
            $connexionEtat = "";
        }

        $produits = $produitRepo->getProduits();
        return $this->render('produit/index.html.twig', [
            'produits' => $produits, 
            "connexionEtat" => $connexionEtat
        ]);
    }

    /**
     * @Route("/produit/{id}", name="produit_show")
     */
    public function show(Produit $produit, SessionInterface $session): Response
    {

        if($session->get("connexionEtat") == "connect") {
            $connexionEtat = "connect";
        } else {
            $connexionEtat = "";
        }
        return $this->render('produit/produit.html.twig', [
            'produit' => $produit,
            'connexionEtat' => $connexionEtat,
        ]);
    }
}
