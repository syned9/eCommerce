<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitFormType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
    /**
     * @Route("/produit/{id}", name="produit")
     */
    public function show(Produit $produit, ProduitRepository $produitRepository, Request $request): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $produitRepository->getProduitPaginator($produit, $offset);
        return $this->render('produit/show.html.twig', [
            
            'produit' => $paginator,
            'previous' => $offset - ProduitRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ProduitRepository::PAGINATOR_PER_PAGE),
        ]);
    }
    /**
     * @Route("/produit/{id}/modif", name="produits")
     */
    public function modif(Produit $produit): Response
{

        $form = $this->createForm(ProduitFormType::class, $produit);
        return $this->render('produit/modif.html.twig', [
            'produit' => $produit,
            'form_produit' => $form->createView()
        ]);
    }
}
