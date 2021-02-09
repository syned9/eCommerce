<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Produit;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


 /**
     * 
     *@Route("/home", name ="home")
     * 
     */
    public function index(SessionInterface $session): Response
    {
        if($session->get("connexionEtat") == "connect") {
            $connexionEtat = "connect";
        } else {
            $connexionEtat = "";
        }

        return $this->render('home/home.html.twig', [
            "connexionEtat" => $connexionEtat

        ]);
    }


  /*  public function home(SessionInterface $session):Response 
    { 
        
        
        return $this->render('home/home.html.twig');
    }
*/

   
     /**
     * 
     *@Route("/products", name ="products")
     * 
     */
    public function userProduct(ProduitRepository $productstRepository, 
    Request $request, CategoriesRepository $categoriesRepository ,  SessionInterface $session):Response 
    { 
        if($session->get("connexionEtat") == "connect") {
            $connexionEtat = "connect";
        } else {
            $connexionEtat = "";
        }



        $offset = max(0, $request->query->getInt('offset', 0));
        
        $paginator = $productstRepository->getProductPaginator($offset);
        $categories = $categoriesRepository->findAll();
       
        return $this->render('home/userProducts.html.twig',[
             
            'products'=>$paginator,
             'categories'=>$categories,
            'previous' => $offset - ProduitRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ProduitRepository::PAGINATOR_PER_PAGE),
            "connexionEtat" => $connexionEtat,
          

        ]);
    }

    /**
     * @Route("/productsByCategory", name ="productsCategory")
     */

    public function productsCategory(ProduitRepository $productstRepository, 
    Request $request, CategoriesRepository $categoriesRepository, SessionInterface $session ):Response 
    {
        if($session->get("connexionEtat") == "connect") {
            $connexionEtat = "connect";
        } else {
            $connexionEtat = "";
        }


        $offset = max(0, $request->query->getInt('offset', 0));
        $categorieId = $request->query->get('categorie', '');
        if (!empty($categorieId)) {
            $categorie = $categoriesRepository->find($categorieId);
        }
        
        $paginator = $productstRepository->getCategoriePaginator($offset, $categorie);
        $categories = $categoriesRepository->findAll();
       
        return $this->render('home/userProducts.html.twig',[
            'products'=>$paginator,
             'categories'=>$categories,
            'previous' => $offset - ProduitRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ProduitRepository::PAGINATOR_PER_PAGE),
            "connexionEtat" => $connexionEtat

        ]);
     }

    
}
