<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\MettingPoint;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $role
     * @return mixed
     */
    public function findByRole($role)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $role
     * @return mixed
     */
    public function findByRoleMettingPoint(MettingPoint $mettingPoint, $role)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->leftJoin('u.mettingPoint', 'm')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.mettingPoint IN (:mettingPoint)')
            ->setParameters([
                'roles' => '%"'.$role.'"%',
                'mettingPoint' => $mettingPoint
            ]);

        return $qb->getQuery()->getResult();
    }
}
