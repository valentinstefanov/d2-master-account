<?php

namespace App\Repository;

use App\Entity\PasswordResetRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PasswordResetRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordResetRequest::class);
    }

    public function findValidToken(string $token, int $ttlMinutes = 60): ?PasswordResetRequest
    {
        $qb = $this->createQueryBuilder('r');
        $qb->where('r.token = :token')
            ->andWhere('r.requestedAt > :since')
            ->setParameter('token', $token)
            ->setParameter('since', new \DateTimeImmutable('-' . $ttlMinutes . ' minutes'));
        return $qb->getQuery()->getOneOrNullResult();
    }
}
