<?php

namespace App\Repository;

use App\Entity\MettingPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MettingPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method MettingPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method MettingPoint[]    findAll()
 * @method MettingPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MettingPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MettingPoint::class);
    }

    // /**
    //  * @return MettingPoint[] Returns an array of MettingPoint objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MettingPoint
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
