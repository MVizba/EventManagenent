<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Skaiciuoti left registrracijas
     *
     * @param Event $event
     * @return int
     */
    public function getRegistrationsLeft(Event $event): int
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->select('COUNT(u.id)')
            ->leftJoin('e.registeredUsers', 'u')
            ->where('e.id = :eventId')
            ->setParameter('eventId', $event->getId())
            ->groupBy('e.id');

        $registeredCount = (int) $queryBuilder->getQuery()->getSingleScalarResult();
        $registrationLimit = $event->getRegistrationLimit();
        return $registrationLimit - $registeredCount;
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
