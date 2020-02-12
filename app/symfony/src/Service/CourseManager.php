<?php


namespace App\Service;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\MettingPoint;
use App\Repository\CourseRepository;

class CourseManager
{
    const DEFAULT_COURSE = 25.30;
    /**
     * @var int
     */
    private $price = 0;

    private $tva = 0.20;


    /**
     * @param $price
     */
    public function addPrice($price)
    {
        $this->price += $price;
    }

    /**
     * @param $price
     */
    public function substarctCourse($price)
    {
        $this->price -= $price;
    }

    /**
     * @return false|float
     */
    public function fixPrice()
    {
        $this->price = ($this->price < 0) ? $this->price : 0;

        return round($this->price, 2, PHP_ROUND_HALF_UP);
    }

    /**
     * @param Card $card
     * @param Course $course
     * @return Card
     */
    public function addCourse(Card $card, Course $course)
    {
        $card->addCourse($course)->setPrice($this->price + $course->getPrice());

        return $card;
    }

    /**
     * @param Card $card
     * @param Course $course
     * @return Card
     */
    public function removeCourse(Card $card, Course $course)
    {
        if ($this->price > 0) {
            $card->setPrice($this->price - $course->getPrice())->removeCourse($course);
        }

        return $card;
    }
}
