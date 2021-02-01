<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(ProduitCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ECommerce');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Produit', 'fa fa-list', Produit::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-list', Categories::class);
        // yield MenuItem::section('The Label', 'fas fa-list', EntityClass::class);
        // return [
        //     // ...
    
        //     // links to the 'index' action of the Category CRUD controller
        //     MenuItem::linkToCrud('Produit', 'fa fa-tags', Category::class),
    
        //     // links to a different CRUD action
        //     MenuItem::linkToCrud('Add Category', 'fa fa-tags', Category::class)
        //         ->setAction('new'),
    
        //     MenuItem::linkToCrud('Show Main Category', 'fa fa-tags', Category::class)
        //         ->setAction('detail')
        //         ->setEntityId(1),
    
        //     // if the same Doctrine entity is associated to more than one CRUD controller,
        //     // use the 'setController()' method to specify which controller to use
        //     MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class)
        //         ->setController(LegacyCategoryCrudController::class),
    
        //     // uses custom sorting options for the listing
        //     MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class)
        //         ->setDefaultSort(['createdAt' => 'DESC']),

        // ];      
    }
}
