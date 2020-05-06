<?php


namespace App\Service;

use App\Entity\Course;
use Doctrine\ORM\EntityManager;
use phpDocumentor\Reflection\Types\Parent_;

class PackageManager
{
    /** @var EntityManager */
    private $em;

    /**
     * PackageManager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function updateCourseStatus()
    {
        $now = new \DateTime();
        $courses = $this->em->getRepository(Course::class)->findBy([
            'courseDate' =>$now->add(new \DateInterval('P1D'))->setTime(0, 0, 0)

        ]);
    }
}
