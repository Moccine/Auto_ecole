<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseRepository")
 */
class Course
{
    const DEFAULT_PRICE = 29.59;
    const ACTIVATED = 'ACTIVATED';
    const IN_CARD = 1;
    const PAID=2;
    const DONE = 3;
    const COURSE_STATUS = [
        self::IN_CARD => 'course.in_card',
        self::PAID => 'course.paid',
        self::DONE => 'course.done',
    ];
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;
    const DAYS = [
        self::MONDAY => 'day.monday',
        self::TUESDAY => 'day.tuesday',
        self::WEDNESDAY => 'day.wednesday',
        self::THURSDAY => 'day.thursday',
        self::FRIDAY => 'day.friday',
        self::SATURDAY => 'day.saturday',
        self::SUNDAY => 'day.sunday',
    ];

    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;
    const MONTHS = [
        self::JANUARY => 'month.January',
        self::FEBRUARY => 'month.february',
        self::MARCH => 'month.march',
        self::APRIL => 'month.april',
        self::MAY => 'month.may',
        self::JUNE => 'month.june',
        self::JULY => 'month.july',
        self::AUGUST => 'month.august',
        self::SEPTEMBER => 'month.September',
        self::OCTOBER => 'month.october',
        self::NOVEMBER => 'month.november',
        self::DECEMBER => 'month.december',
    ];
    use CreatedAtTrait;
    use UpdatedAtTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $courseDate;


    /**
     * @ORM\Column(type="float")
     */
    private $price = self::DEFAULT_PRICE;


    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status = self::IN_CARD;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $instructor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Card")
     */
    private $card;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MettingPoint")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mettingPoint;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Orders", mappedBy="course")
     */
    private $orders;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->orders = new ArrayCollection();
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return $this
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getStudent(): ?User
    {
        return $this->student;
    }

    /**
     * @param User|null $student
     * @return $this
     */
    public function setStudent(?User $student): self
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getInstructor(): ?User
    {
        return $this->instructor;
    }

    /**
     * @param User|null $instructor
     * @return $this
     */
    public function setInstructor(?User $instructor): self
    {
        $this->instructor = $instructor;

        return $this;
    }

    /**
     * @return Card|null
     */
    public function getCard(): ?Card
    {
        return $this->card;
    }

    /**
     * @param Card|null $card
     * @return $this
     */
    public function setCard(?Card $card): self
    {
        $this->card = $card;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCourseDate(): ?\DateTime
    {
        return $this->courseDate;
    }

    /**
     * @param \DateTime $courseDate
     * @return $this
     */
    public function setCourseDate(\DateTime $courseDate): self
    {
        $this->courseDate = $courseDate;

        return $this;
    }

    public function getMettingPoint(): ?MettingPoint
    {
        return $this->mettingPoint;
    }

    public function setMettingPoint(?MettingPoint $mettingPoint): self
    {
        $this->mettingPoint = $mettingPoint;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCourse($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getCourse() === $this) {
                $order->setCourse(null);
            }
        }

        return $this;
    }
}
