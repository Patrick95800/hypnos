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
}
