<?php

namespace App\Repository;

use App\Entity\EvaluationScore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EvaluationScore|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvaluationScore|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvaluationScore[]    findAll()
 * @method EvaluationScore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluationScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvaluationScore::class);
    }

    // /**
    //  * @return EvaluationScore[] Returns an array of EvaluationScore objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EvaluationScore
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
