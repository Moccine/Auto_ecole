<?php

namespace App\Repository;

use App\Entity\CoursePricing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CoursePricing|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoursePricing|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoursePricing[]    findAll()
 * @method CoursePricing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursePricingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoursePricing::class);
    }

    // /**
    //  * @return CoursePricing[] Returns an array of CoursePricing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CoursePricing
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
