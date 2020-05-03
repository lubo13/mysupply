<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function prepareForChoices(): ?array
    {
        $results = $this->createQueryBuilder('c')
            ->select('c.id, c.name')
            ->getQuery()
            ->getScalarResult();

        $categories = [];

        foreach ($results as $result) {
            $categories[$result['name']] = $result['id'];
        }
        return $categories;
    }
}
