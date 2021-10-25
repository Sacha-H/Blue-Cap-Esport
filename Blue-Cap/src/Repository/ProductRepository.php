<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
    * return all Product per page
    * @return void
    */
    public function getPaginatedProduct($page, $limit, $filters = null){

        $query = $this->createQueryBuilder('p');

        //on filtre les données
        if ($filters != null) {   
                $query->where('c.product_category IN(:cats)')
                ->setParameter(':cats', array_values($filters));
        }

        $query->orderBy('p.id')
        ->setFirstResult(($page * $limit) - $limit)
        ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }
    /**
    * return total Product
    * @return void
    */
    public function getTotalProduct($filters = null){
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)');
            if ($filters != null) {   
                $query->where('c.product_category IN(:cats)')
                ->setParameter(':cats', array_values($filters));
        }
            ;
            return $query->getQuery()->getSingleScalarResult();
    }


    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */

    // public function getFilters($filters){
    //     return $this->createQueryBuilder('a')
    //     ->Where('a.product_category IN(:cats)')
    //     ->setParameter('cats', $filters);
        
    // }
    
    // public function findByCategory($id)
    // {
    //     return $this->createQueryBuilder('p')
    //         ->andWhere('p.category_product = :val')
    //         ->setParameter('val', $id)
    //         ->orderBy('p.nameCategory', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
