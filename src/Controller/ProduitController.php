<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{



      /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('produit/admin.html.twig', [
        ]);
    }
 /**
     * 
     *@Route("/newProduct", name ="newProduct")
     * 
     */
    public function  newProduit(Request $request, EntityManagerInterface $entityManager):Response
    {

        $produit = new Produit(); 
        $form = $this->createForm(ProduitFormType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
             
            $produit = $form->getData(); 
            $entityManager->persist($produit);
            $entityManager->flush(); 

            return $this->redirectToRoute('newProduct');
        }


        return $this->render('produit/newProduct.html.twig',[

            'form_produit'=>$form->createView(),
        ]);
    }

}
