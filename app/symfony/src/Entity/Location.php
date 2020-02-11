<?php

namespace App\Entity;

use App\Entity\Traits\ActivatedTrait;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\LocationTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    const ACTIVATED = 1;
    const DESACTIVATED = 0;
    use ActivatedTrait;
    use CreatedAtTrait;
    use LocationTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Location constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

}
