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
   
    public function getProduitPaginator(int $offset, string $nom = '', string $prix ='', string $categorie =''): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('c');
        if ($nom) {
            $queryBuilder = $queryBuilder
                ->andWhere('c.nom = :nom')
                ->setParameter('nom', $nom);
        }
        if ($prix) {
            $queryBuilder = $queryBuilder
                ->andWhere('c.prix = :prix')
                ->setParameter('prix', $prix);
        }
        if ($categorie) {
            $queryBuilder = $queryBuilder
                ->andWhere('c.nom = :nom')
                ->setParameter('nom', $categorie);
        }
        $query = $queryBuilder
        ->orderBy('c.nom', 'DESC')
        ->setMaxResults(self::PAGINATOR_PER_PAGE)
        ->setFirstResult($offset)
        ->getQuery()
        ;
        return new Paginator($query);
     }
     public function getListNom()
    {
        $noms = [];
        foreach ($this->createQueryBuilder('c')
        ->select('c.nom')
        ->distinct(true)
        ->orderBy('c.nom', 'ASC')
        ->getQuery()
        ->getResult() as $cols) {
            $noms[] = $cols['nom'];
        }
        return $noms;
    }
    public function getListPrix()
    {
        $prixs = [];
        foreach ($this->createQueryBuilder('c')
        ->select('c.prix')
        ->distinct(true)
        ->orderBy('c.prix', 'ASC')
        ->getQuery()
        ->getResult() as $cols) {
            $prixs[] = $cols['prix'];
        }
        return $prixs;
    }
    
}
