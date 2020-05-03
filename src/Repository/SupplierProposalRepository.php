<?php

namespace App\Repository;

use App\Entity\SupplierProposal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SupplierProposal|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplierProposal|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplierProposal[]    findAll()
 * @method SupplierProposal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplierProposalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplierProposal::class);
    }

    // /**
    //  * @return SupplierProposal[] Returns an array of SupplierProposal objects
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
    public function findOneBySomeField($value): ?SupplierProposal
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
