<?php

namespace App\Repository;

use App\Entity\Categories;
use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }



    public const PAGINATOR_PER_PAGE = 9;

    public function getProductPaginator(int $offset):Paginator
    {
   
        $query = $this->createQueryBuilder('p')
        ->andWhere('p.disponibilite = true')
        ->setMaxResults(self::PAGINATOR_PER_PAGE)
        ->setFirstResult($offset)
        ->getQuery();
        return new Paginator($query);
    }
    public function getCategoriePaginator(int $offset, Categories $categorie){

        $query = $this->createQueryBuilder('p');

        if (!empty($categorie)) {
            $query = $query->andWhere('p.categorie = :categorie')
                        ->setParameter('categorie', $categorie);
        }
        $query = $query->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();

        return new Paginator($query);
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    public function getProduits()
    {
        return $this->createQueryBuilder('p')
            // ->andWhere('p.exampleField = :val')
            // ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

}
