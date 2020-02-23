<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShopRepository")
 */
class Shop
{
    const BEST_OFFERS =  1;
    const DRIVING_CARD =  2;
    const offers = [
        self::BEST_OFFERS => 'shop.best_offers',
        self::DRIVING_CARD => 'shop.driving_card',
    ];
    const PEDDING = 'PEDDING';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="float", precision=10, scale=0, nullable=true)
     */
    private $hour;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priority;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $complementDescription;

    /**
     * @ORM\Column(type="integer")
     */
    private $courseNumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="shop")
     */
    private $cards;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHour(): ?string
    {
        return $this->hour;
    }

    /**
     * @param string|null $hour
     * @return $this
     */
    public function setHour(?string $hour): self
    {
        $this->hour = $hour;

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
     * @param float|null $price
     * @return $this
     */
    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int|null $priority
     * @return $this
     */
    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getComplementDescription(): ?string
    {
        return $this->complementDescription;
    }

    /**
     * @param string|null $complementDescription
     * @return $this
     */
    public function setComplementDescription(?string $complementDescription): self
    {
        $this->complementDescription = $complementDescription;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCourseNumber(): ?int
    {
        return $this->courseNumber;
    }

    /**
     * @param int $courseNumber
     * @return $this
     */
    public function setCourseNumber(int $courseNumber): self
    {
        $this->courseNumber = $courseNumber;

        return $this;
    }
    

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setShop($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getShop() === $this) {
                $card->setShop(null);
            }
        }

        return $this;
    }

}
