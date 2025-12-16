<?php

namespace App\Repository;

use App\Entity\PractitionerSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PractitionerSchedule>
 */
class PractitionerScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PractitionerSchedule::class);
    }

    /**
     * Returns an array of practitioners that are available
     * to be scheduled as per their shifts, not existing appointments
     *
     * @param  int    $practId
     * @param  string $startTime
     * @param  string $endTime
     * @return array<mixed>
     */
    public function findByPractitionerId(
        int $practId,
        string $startTime,
        string $endTime
    ): array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT DISTINCT p.practitioner_id
            FROM practitioner_schedule p
            WHERE p.practitioner_id = :pid
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
    //     * @return PractitionerSchedule[] Returns an array of PractitionerSchedule objects
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

    //    public function findOneBySomeField($value): ?PractitionerSchedule
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
