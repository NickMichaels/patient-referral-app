<?php

namespace App\Repository;

use App\Model\Paginator;
use App\Entity\Practitioner;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Practitioner>
 */
class PractitionerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Practitioner::class);
    }

    public function findAllWithPagination(int $page): Paginator
    {
        $query = $this->createQueryBuilder('p')->orderBy('p.createdAt', 'ASC');

        return new Paginator($query, $page);
    }

    //    /**
    //     * @return Practitioner[] Returns an array of Practitioner objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Practitioner
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
