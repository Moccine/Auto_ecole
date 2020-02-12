<?php


namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        if (!$createdAt instanceof \DateTime) {
            $this->onPersist();
        }
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPersist()
    {
        $this->createdAt = new \DateTime();
    }
}
