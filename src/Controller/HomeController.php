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
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


 /**
     * 
     *@Route("/home", name ="home")
     * 
     */
    public function home(ProduitRepository $productstRepository, Request $request):Response 
    { 
        
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $productstRepository->getProductPaginator($offset);
        
        return $this->render('home/home.html.twig');
    }


   
     /**
     * 
     *@Route("/products", name ="products")
     * 
     */
    public function userProduct(ProduitRepository $productstRepository, 
    Request $request, CategoriesRepository $categoriesRepository ):Response 
    { 
        
        $offset = max(0, $request->query->getInt('offset', 0));
        
        $paginator = $productstRepository->getProductPaginator($offset);
        $categories = $categoriesRepository->findAll();
       
        return $this->render('home/userProducts.html.twig',[
             
            'products'=>$paginator,
             'categories'=>$categories,
            'previous' => $offset - ProduitRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ProduitRepository::PAGINATOR_PER_PAGE),
          

        ]);
    }
 
     /**
     * 
     *@Route("/products/{categorie}/categoriesFilter", name ="categorie")
     * 
     */
    public function categorie(Request $request, ProduitRepository $productstRepository,
    CategoriesRepository $categoriesRepository):Response 
    { 
        
         $categorieId = $request->query->get('categorie', '');
        
         $offset = max(0, $request->query->getInt('offset', 0));
        $paginatorCategorie = $productstRepository->getCategoriePaginator($offset, $categorieId);
        $categories = $categoriesRepository->findAll();
       
        return $this->render('home/categories.html.twig', [
            'categoriesFilter'=>$paginatorCategorie,
            'categories'=>$categories,
            'previous' => $offset - ProduitRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginatorCategorie), $offset + ProduitRepository::PAGINATOR_PER_PAGE),
        ]);
    }
    
}
