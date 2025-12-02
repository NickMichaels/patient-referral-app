<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    /**
     * Returns an array of appointments that are already booked
     * for a given practicioner
     *
     * @param  int    $practId
     * @param  string $startTime
     * @param  string $endTime
     * @return array<mixed>
     */
    public function findPracticionerAppointments(
        int $practId,
        string $startTime,
        string $endTime
    ): array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT COUNT(*) AS appointment_no
            FROM appointment a
            WHERE a.practicioner_id = :pid
            AND a.start_time < :end_time
            AND a.end_time > :start_time
            ORDER BY a.id ASC
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
    //     * @return Appointment[] Returns an array of Appointment objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Appointment
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
