<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ActivatedTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $activated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deactivatedAt;

    /**
     * Get activated.
     *
     * @return bool
     */
    public function isActivated()
    {
        return $this->activated;
    }

    /**
     * Set deactivatedAt.
     *
     * @param \DateTime $deactivatedAt
     *
     * @return $this
     */
    public function setDeactivatedAt($deactivatedAt)
    {
        $this->deactivatedAt = $deactivatedAt;

        return $this;
    }

    /**
     * Get deactivatedAt.
     *
     * @return \DateTime
     */
    public function getDeactivatedAt()
    {
        return $this->deactivatedAt;
    }

    /**
     * Initialization.
     *
     * @ORM\PrePersist
     */
    public function initActivatedTrait()
    {
        $this->activated = $this->activated ? true : false;
    }

    /**
     * @return bool|null
     */
    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    /**
     * @param bool|null $activated
     * @return $this
     */
    public function setActivated(?bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }
}
