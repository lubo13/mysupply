<?php

namespace App\Repository;

use App\Entity\SupplierBid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SupplierBid|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplierBid|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplierBid[]    findAll()
 * @method SupplierBid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplierBidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplierBid::class);
    }

    // /**
    //  * @return SupplierBid[] Returns an array of SupplierBid objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SupplierBid
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
