<?php

namespace App\Controller;



use App\Entity\Produit;
use App\Form\ProduitFormType;
use App\Repository\CategoriesRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(CategoriesRepository $categoriesRepository, ProduitRepository $produitRepository, Request $request): Response
    {
        $noms = $produitRepository->getListNom();
        $prixs = $produitRepository->getListPrix();
        $categories = $categoriesRepository->getListNoms();
        
        $nom_search = $request->query->get('nom_search', '');
        $prix_search = $request->query->get('prix_search', '');
        $categorie_search = $request->query->get('categorie_search', '');
        

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $produitRepository->getProduitPaginator($offset, $nom_search, $prix_search, $categorie_search);
        return $this->render('produit/index.html.twig', [
            'nom_search' => $nom_search,
            'noms' => $noms,
            'prix_search'=> $prix_search,
            'prixs' =>  $prixs,
            'categorie_search' => $categorie_search,
            'categories' => $categories,
            'produits' => $paginator,
            'previous' => $offset - ProduitRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ProduitRepository::PAGINATOR_PER_PAGE),
        ]);
    }
    /**
     * @Route("/produit", name="prod")
     */
    public function pro( ProduitRepository $produitRepository, Request $request): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $produitRepository->getProduitPaginator( $offset);
        return $this->render('produit/produit.html.twig', [
            'produits' => $paginator,
            'previous' => $offset - ProduitRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ProduitRepository::PAGINATOR_PER_PAGE),
        ]);
    }
    /**
     * @Route("/produit/{id}", name="produits")
     */
    public function show(Produit $produit, ProduitRepository $produitRepository, Request $request): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $produitRepository->getProduitPaginator( $offset);
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,

        ]);
    }
    /** 
     * @Route("/produit/{id}/modif", name="modif")
     */
    public function update(Produit $produit, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitFormType::class, $produit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
                
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produits',['id' => $produit->getId()]);
        }
        
        return $this->render('produit/modif.html.twig', [
            'produits' => $produit,
            'form_produit' => $form->createView()
        ]);
    }
    /**
     * @Route("/newproduit", name="new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitFormType::class, $produit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $images= $form->get('UrlImage')->getData();
           // foreach($images as $image){
              // $fichier = md5(uniqid()). '.' .$image->guessExtension();
              //  $image->move($this->getParameter('images_directory'),$fichier);
               
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('homepage', ['id' => $produit->getId()]);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form_produit' => $form->createView()
        ]);
    }
    /**
     * @Route("/produit/{id}/delete", name="delete")
     */
    public function Delete(Produit $produit, EntityManagerInterface $entityManager): Response
    {
        //$produit = $produit->getProduit();
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }
      /**
     * @Route("/acceuil", name="acceuil")
     */
    public function acceuil()
    {
        
        return $this->render('produit/acceuil.html.twig');
    }
}
