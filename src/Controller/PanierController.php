<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\AjouterPanierFormType;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $panier = $session->get('panier', []);
        $panierWithData[] = [];
        foreach($panier as $id => $quantite) {
            $panierWithData[] = [
                'produit' => $produitRepository->find($id),
                'quantite' => $quantite
            ];
        }

        // dd($panierWithData);

        $total = 0;
        if(count($panierWithData) > 1) {
            foreach($panierWithData as $item) {
                // dd($item['quantite']);
                if($item['produit'] != null) {
                    $totalitem = $item['produit']->getPrix() * $item['quantite'];
                    $total += $totalitem;
                }   
            }
        }
        
        if($session->get("connexionEtat") == "connect") {
            $connexionEtat = "connect";
        } else {
            $connexionEtat = "";
        }
        
        return $this->render('panier/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
            "connexionEtat" => $connexionEtat
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="panier_add")
     */
    public function add($id, SessionInterface $session)
    {
        // $form = $this->createForm();
        // $form->handleRequest($request);
        $panier = $session->get('panier', []);
        $panier[$id] = intval($_POST['quantite']);
        $session->set('panier', $panier);

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/panier/remove/{id}", name="panier_remove")
     */
    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        if(!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("profile/panier/validation", name="panier_validation")
     */
    public function validation(SessionInterface $session, EntityManagerInterface $entityManager, PanierRepository $panierRepo)
    {
        $panierValider = $panierRepo->findOneBySomeField($session->get('user'));
        $panierValider->setProduits(array($session->get('panier', [])));
        // dd($panierValider);
        if(!empty($panierValider)) {
            $entityManager->persist($panierValider);
            $entityManager->flush();
        }
        // $session->set('panier', []);
        return $this->redirectToRoute("commande_encours");
    }
}
