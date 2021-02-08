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
     * @Route("/productsByCategory", name ="productsCategory")
     */
    public function productsCategory(ProduitRepository $productstRepository, 
    Request $request, CategoriesRepository $categoriesRepository ):Response 
    {
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
        ]);
    }
}
