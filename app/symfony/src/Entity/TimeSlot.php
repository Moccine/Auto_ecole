<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TimeSlotRepository")
 */
class TimeSlot
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $day;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $timeslot = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="timeSlot")
     */
    private $user;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDay(): ?\DateTimeInterface
    {
        return $this->day;
    }

    /**
     * @param \DateTimeInterface $day
     * @return $this
     */
    public function setDay(\DateTimeInterface $day): self
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getTimeslot(): ?array
    {
        return $this->timeslot;
    }

    /**
     * @param array|null $timeslot
     * @return $this
     */
    public function setTimeslot(?array $timeslot): self
    {
        $this->timeslot = $timeslot;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
