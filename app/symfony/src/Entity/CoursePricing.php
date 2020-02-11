<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoursePricingRepository")
 */
class CoursePricing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $drivingPrice;

    /**
     * @ORM\Column(type="float")
     */
    private $codePrice;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDrivingPrice(): ?float
    {
        return $this->drivingPrice;
    }

    public function setDrivingPrice(float $drivingPrice): self
    {
        $this->drivingPrice = $drivingPrice;

        return $this;
    }

    public function getCodePrice(): ?float
    {
        return $this->codePrice;
    }

    public function setCodePrice(float $codePrice): self
    {
        $this->codePrice = $codePrice;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }
}
