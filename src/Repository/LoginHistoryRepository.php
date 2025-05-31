<?php

namespace App\Repository;

use App\Entity\LoginHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LoginHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoginHistory::class);
    }

    /**
     * @return int
     */
    public function countFailedLoginsInLastHour($user): int
    {
        $qb = $this->createQueryBuilder('l');
        $qb->select('count(l.id)')
            ->where('l.user = :user')
            ->andWhere('l.success = false')
            ->andWhere('l.createdAt > :since')
            ->setParameter('user', $user)
            ->setParameter('since', new \DateTimeImmutable('-1 hour'));
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
