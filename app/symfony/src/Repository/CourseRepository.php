<?php

namespace App\Repository;

use App\Entity\Course;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }


    public function CourseByStydentAndInstructor($value)
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


    public function findByStudent(User $student)
    {
        return $this->createQueryBuilder('course')
            ->andWhere('course.student = :val')
            ->setParameter('val', $student)
            ->getQuery()->getResult()
            ;
    }



    public function findCourseByDate(\DateTime $dateTime)
    {
        return $this->createQueryBuilder('course')
            ->leftJoin('course.card', 'card')
            ->andWhere('course.courseDate <= :date')
            ->andWhere('course.status <= :status')
            ->andWhere('card.shop is null')
            ->setParameters([
                'date'=> $dateTime,
                'status' => Course::IN_CARD
            ])
            ->getQuery()->getResult()
            ;
    }

    public function findCourseByDateAndUser(User $student)
    {
        //$date = $dateTime->setTime(00, 00, 00)->format('Y-m-d');
        return $this->createQueryBuilder('course')
           // ->andWhere('course.courseDate >= :date')
            ->andWhere('course.student = :student')
            ->setParameters([
              //  'date'=> $date,
                'student' => $student
            ])
            ->getQuery()->getResult()
            ;
    }
}
