<?php

namespace App\Repository;

use App\Entity\TrafficLawsType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TrafficLawsType|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrafficLawsType|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrafficLawsType[]    findAll()
 * @method TrafficLawsType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrafficLawsTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrafficLawsType::class);
    }

    // /**
    //  * @return TrafficLawsType[] Returns an array of TrafficLawsType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrafficLawsType
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
