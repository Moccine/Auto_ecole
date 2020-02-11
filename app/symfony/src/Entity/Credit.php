<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreditRepository")
 */
class Credit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Shop")
     */
    private $shop;

    /**
     * @ORM\Column(type="float")
     */
    private $total=0;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Card", cascade={"persist", "remove"})
     */
    private $card;

    /**
     * @ORM\Column(type="float")
     */
    private $rest;

    /**
     * @ORM\Column(type="integer")
     */
    private $courseNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $restCourseNumber;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Shop|null
     */
    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    /**
     * @param Shop|null $shop
     * @return $this
     */
    public function setShop(?Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
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
     * @return float|null
     */
    public function getRest(): ?float
    {
        return $this->rest;
    }

    /**
     * @param float $rest
     * @return $this
     */
    public function setRest(float $rest): self
    {
        $this->rest = $rest;

        return $this;
    }

    public function getCourseNumber(): ?int
    {
        return $this->courseNumber;
    }

    public function setCourseNumber(int $courseNumber): self
    {
        $this->courseNumber = $courseNumber;

        return $this;
    }

    public function getRestCourseNumber(): ?int
    {
        return $this->restCourseNumber;
    }

    public function setRestCourseNumber(int $restCourseNumber): self
    {
        $this->restCourseNumber = $restCourseNumber;

        return $this;
    }
}
