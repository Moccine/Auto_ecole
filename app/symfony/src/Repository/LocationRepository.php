<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    /**
     * @param $role
     * @return mixed
     */
    public function findByUser(User $user)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('l')
            ->from($this->_entityName, 'l')
            ->where('l.user = :user')
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }

    public function locationByMettingPoint()
    {
        $qb = $this->createQueryBuilder('l');
        $qb->andWhere('l.activated = :activated')
            ->andWhere('l.metting = :metting')
            ->groupBy('l.city')
            ->setParameters([
                'activated' => Location::ACTIVATED,
                'metting' => Location::ACTIVATED,]);

        return $qb->getQuery()->getResult();

    }
    public function userByLocation(User $user)
    {
        $qb = $this->createQueryBuilder('l')
            ->andWhere('l.user = :user ')
            ->setParameter('user', $user);
        ;
        return $qb->getQuery()->getResult();

    }


}
