<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\ProduitRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("profile/commande", name="commande")
     */
    public function index(ProduitRepository $produitRepository, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        foreach($panier as $id => $quantite) {
            $panierWithData[] = [
                'produit' => $produitRepository->find($id),
                'quantite' => $quantite
            ];
        }

        $total = 0;

        foreach($panierWithData as $item) {
            if($item['produit'] != null) {
                $totalitem = $item['produit']->getPrix() * $item['quantite'];
                $total += $totalitem;
            }   
        }

        return $this->render('commande/index.html.twig', [
            "total" => $total,
            "items" => $panierWithData,
            "connexionEtat" => 'connect'
        ]);
    }

    /**
     * @Route("profile/commande/validation", name="commande_validation")
     */
    public function validation(ProduitRepository $produitRepository, SessionInterface $session, EntityManagerInterface $entityManager ): Response
    {
        $panier = $session->get('panier', []);
        foreach($panier as $id => $quantite) {
            $panierWithData[] = [
                'produit' => $produitRepository->find($id),
                'quantite' => $quantite,
                "connexionEtat" => 'connect'
            ];
        }

        $total = 0;

        foreach($panierWithData as $item) {
            // dd($item['quantite']);
            if($item['produit'] != null) {
                $totalitem = $item['produit']->getPrix() * $item['quantite'];
                $total += $totalitem;
            }   
        }

        $commande = new Commande();
        $commande->setAdresse($_POST['adresse']);
        $commande->setPrix($total);
        $commande->setDate(new DateTime());
        $commande->setUser($session->get('user'));
        $commande->setProduit($panierWithData);

        $entityManager->persist($commande);
        $entityManager->flush();
        return $this->render('commande/validation.html.twig', [
            "commande" => $commande,
            "connexionEtat" => 'connect'
        ]);
    }
}
