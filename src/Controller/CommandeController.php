<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Commande;
use App\Repository\AdminRepository;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("profile/commande", name="commande_encours")
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
    public function validation(ProduitRepository $produitRepository, SessionInterface $session, EntityManagerInterface $entityManager, AdminRepository $adminRepository ): Response
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
        $user = $adminRepository->findOneBySomeId($session->get('user')->getId());
        $commande = new Commande();
        $commande->setAdresse($_POST['adresse']);
        $commande->setPrix($total);
        $commande->setDate(new DateTime());
        // dd($user);
        $commande->setUser($user);
        $commande->setProduit($panierWithData);

        $entityManager->persist($commande);
        $entityManager->flush();
        return $this->render('commande/validation.html.twig', [
            "commande" => $commande,
            "connexionEtat" => 'connect'
        ]);
    }

    /**
     * @Route("profile/commandes", name="Mes_commandes")
     */
    public function MesCommandes(CommandeRepository $commandeRepository, AdminRepository $adminRepository, Request $request, SessionInterface $session): Response
    {
        $user = $adminRepository->findOneBySomeId($session->get('user')->getId());
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commandeRepository->findByUser($user, $offset);

        return $this->render('commande/mesCommandes.html.twig', [
            'commandes' => $paginator,
            'previous' => $offset - CommandeRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommandeRepository::PAGINATOR_PER_PAGE),
            "connexionEtat" => 'connect'
        ]);
    }

    /**
     * @Route("admin/commandes", name="admin_commandes")
     */
    public function AdminCommandes(CommandeRepository $commandeRepository, AdminRepository $adminRepository, Request $request, SessionInterface $session): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commandeRepository->findAll();

        return $this->render('commande/adminCommandes.html.twig', [
            'commandes' => $paginator,
            'previous' => $offset - CommandeRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommandeRepository::PAGINATOR_PER_PAGE),
            "connexionEtat" => 'connect'
        ]);
    }

    /**
     * @Route("admin/commandes/supp", name="commande_supp")
     */
    public function supp($commande, EntityManagerInterface $entityManager): Response
    {
        
        $entityManager->remove($commande);
        $entityManager->flush();

        return $this->redirectToRoute('admin_commandes');
    }
}
