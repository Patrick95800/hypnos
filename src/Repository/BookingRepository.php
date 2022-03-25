<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function add(Booking $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Booking $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findAllForManager(User $user)
    {
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.hotel', 'h')
            ->innerJoin('h.owner', 'u')
            ->where('u.id = :owner_id')
            ->setParameter('owner_id', $user->getId());

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findExistingBookings(array $booking)
    {
        $beginAt = \DateTimeImmutable::createFromFormat(
            'd/m/Y',
            $booking['beginAt']['day'].'/'.$booking['beginAt']['month'].'/'.$booking['beginAt']['year']
        );
        $endAt = \DateTimeImmutable::createFromFormat(
            'd/m/Y',
            $booking['endAt']['day'].'/'.$booking['endAt']['month'].'/'.$booking['endAt']['year']
        );

        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.suite', 's')
            ->innerJoin('b.hotel', 'h')
            ->where('h = :hotel_id')
            ->andWhere('s = :suite_id')
            ->andWhere('(b.beginAt BETWEEN :begin_at_from AND :begin_at_to) OR (b.endAt BETWEEN :end_at_from AND :end_at_to)')
            ->setParameter('hotel_id', (int) $booking['hotel'])
            ->setParameter('suite_id', (int) $booking['suite'])
            ->setParameter('begin_at_from', $beginAt)
            ->setParameter('begin_at_to', $endAt)
            ->setParameter('end_at_from', $beginAt)
            ->setParameter('end_at_to', $endAt)
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
