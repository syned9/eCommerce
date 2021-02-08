<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
   
    /**
     * 
     *@Route("/products/{id}", name ="commande")
     * 
     */
    public function detailProduct(Produit $product, Request $request):Response 
    { 
        $getId = $request->query;
        dump($getId);
        return $this->render('commande/commande.html.twig',[
             
            'product'=>$product,

        ]);
    }

  /**
     * 
     *@Route("/compte", name ="compte")
     * 
     */
    public function compte():Response 
    { 

        return $this->render('commande/compte.html.twig');
    }

}
