<?php

namespace App\Repository;

use App\Entity\PracticionerSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PracticionerSchedule>
 */
class PracticionerScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PracticionerSchedule::class);
    }

    /**
     * Returns an array of practicioners that are available
     * to be scheduled as per their shifts, not existing appointments
     *
     * @param  int    $practId
     * @param  string $startTime
     * @param  string $endTime
     * @return array<mixed>
     */
    public function findByPracticionerId(
        int $practId,
        string $startTime,
        string $endTime
    ): array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT DISTINCT p.practicioner_id
            FROM practicioner_schedule p
            WHERE p.practicioner_id = :pid
            AND p.shift_start <= :start_time
            AND p.shift_end >= :end_time;
            ORDER BY p.id ASC
        ';

        $resultSet = $conn->executeQuery(
            $sql,
            [
                'pid' => $practId,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]
        );

        return $resultSet->fetchAllAssociative();
    }

    //    /**
    //     * @return PracticionerSchedule[] Returns an array of PracticionerSchedule objects
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

    //    public function findOneBySomeField($value): ?PracticionerSchedule
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
