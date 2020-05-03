<?php

namespace App\Repository;

use App\Entity\CustomerRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method CustomerRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerRequest[]    findAll()
 * @method CustomerRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerRequest::class);
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param null                                                $sortDirection
     * @param null                                                $sortField
     * @param null                                                $dqlFilter
     *
     * @return \App\Repository\DoctrineQueryBuilder
     */
    public function createListQueryBuilder(
        UserInterface $user,
        $sortDirection = null,
        $sortField = null,
        $dqlFilter = null
    ) {
        /* @var DoctrineQueryBuilder $queryBuilder */
        $queryBuilder = $this->createQueryBuilder('entity')
            ->select('entity');


        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy(sprintf('%s%s', 'entity.', $sortField), $sortDirection);
        }


        $queryBuilder->andWhere('entity.user = :user')->setParameter('user', $user);
        $queryBuilder->leftJoin('entity.supplierProposals', 'sp');

        return $queryBuilder;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $categories
     * @param null                                    $sortField
     * @param null                                    $sortDirection
     * @param null                                    $dqlFilter
     *
     * @return \App\Repository\DoctrineQueryBuilder
     */
    public function createListQueryBuilderForProposal(
        Collection $categories,
        $sortDirection = null,
        $sortField = null,
        $dqlFilter = null
    ) {
        /* @var DoctrineQueryBuilder $queryBuilder */
        $queryBuilder = $this->createQueryBuilder('entity')
            ->select('entity');


        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy(sprintf('%s%s', 'entity.', $sortField),
                $sortDirection);
        }

        $categoriesIds = [];
        foreach ($categories as $category) {
            $categoriesIds[] = $category->getId();
        }

        if ($categoriesIds) {
            $queryBuilder->andWhere('entity.category IN (:categoriesIds)')
                ->setParameter('categoriesIds', implode(', ', $categoriesIds));
        }

        return $queryBuilder;
    }
}
