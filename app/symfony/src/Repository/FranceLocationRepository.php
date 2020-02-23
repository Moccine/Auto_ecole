<?php

namespace App\Repository;

use App\Entity\FranceLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FranceLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method FranceLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method FranceLocation[]    findAll()
 * @method FranceLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FranceLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FranceLocation::class);
    }

    // /**
    //  * @return FranceLocation[] Returns an array of FranceLocation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FranceLocation
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
