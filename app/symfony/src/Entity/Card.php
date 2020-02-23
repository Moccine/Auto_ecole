<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card
{
    const PENDING = 'PENDING';
    const PAID = 'PAID';
    const SHOP = 1;
    const DRIVING = 0;
    const TYPE_PACKAGE = 1;
    const TYPE_UNITE = 0;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total=0;

    /**
     * @ORM\Column(type="float")
     */
    private $price=0;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = self::PENDING;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cards")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Course", mappedBy="card")
     */
    private $courses;

    /**
     * dertermine si l'achat est package=1 ou pas
     *
     * @ORM\Column(type="boolean")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Orders", inversedBy="card")
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Shop", inversedBy="cards")
     */
    private $shop;

    /**
     * Card constructor.
     */
    public function __construct()
    {
        $this->course = new ArrayCollection();
        $this->total=0;
        $this->courses = new ArrayCollection();
        $this->type = self::DRIVING;
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
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float $total
     * @return $this
     */
    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourse(): Collection
    {
        return $this->course;
    }

    /**
     * @param Course $course
     * @return $this
     */
    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setCard($this);
        }

        return $this;
    }

    /**
     * @param Course $course
     * @return $this
     */
    public function removeCourse(Course $course): self
    {
        if ($this->courses->contains($course)) {
            $this->courses->removeElement($course);
            // set the owning side to null (unless already changed)
            if ($course->getCard() === $this) {
                $course->setCard(null);
            }
        }

        return $this;
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
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    /**
     * @return bool|null
     */
    public function getType(): ?bool
    {
        return $this->type;
    }

    /**
     * @param bool $type
     * @return $this
     */
    public function setType(bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return float|int
     */
    public function totalTva()
    {
        return $this->total* (1- 0.2);
    }

    /**
     * @param Orders|null $orders
     * @return $this
     */
    public function setOrders(?Orders $orders): self
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * @return Orders|null
     */
    public function getOrders(): ?Orders
    {
        return $this->orders;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }

}
