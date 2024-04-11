<?php

namespace App\Repository;

use App\Entity\VisitorIP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VisitorIP>
 *
 * @method VisitorIP|null find($id, $lockMode = null, $lockVersion = null)
 * @method VisitorIP|null findOneBy(array $criteria, array $orderBy = null)
 * @method VisitorIP[]    findAll()
 * @method VisitorIP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitorIPRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VisitorIP::class);
    }

    //    /**
    //     * @return VisitorIP[] Returns an array of VisitorIP objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?VisitorIP
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
