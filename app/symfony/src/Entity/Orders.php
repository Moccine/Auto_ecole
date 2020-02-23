<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\IdentifiableTrait;
use App\Entity\Traits\OrderTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Orders
{
    use CreatedAtTrait;
    use IdentifiableTrait;
    use OrderTrait;

    const PAID = 'PAID';
    const TVA = 0.2;
    const STATUS_CANCLED = 'CANCELED';
    const STATUS_PENDING = 'PENDIND';

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $orderNumber;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $status;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $productDetails;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     */
    private $student;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Payment", mappedBy="orders")
     */
    private $payment;


    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $discount = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $tva;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $billDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="orders")
     */
    private $card;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Shop")
     */
    private $shop;

    /**
     * BookingOrder constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->payment = new ArrayCollection();
        $this->orderNumber = sprintf('%s%s', time(), 'ORDER');
        $this->Orders = new ArrayCollection();
        $this->card = new ArrayCollection();
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
     * @return Collection|Payment[]
     */
    public function getPayment(): Collection
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     * @return $this
     */
    public function addPayment(Payment $payment): self
    {
        if (!$this->payment->contains($payment)) {
            $this->payment[] = $payment;
            $payment->setOrders($this);
        }

        return $this;
    }

    /**
     * @param Payment $payment
     * @return $this
     */
    public function removePayment(Payment $payment): self
    {
        if ($this->payment->contains($payment)) {
            $this->payment->removeElement($payment);
            // set the owning side to null (unless already changed)
            if ($payment->getOrders() === $this) {
                $payment->setOrders(null);
            }
        }

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     * @return $this
     */
    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getTva(): ?float
    {
        return $this->tva;
    }

    /**
     * @param float $tva
     * @return $this
     */
    public function setTva(float $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getBillDate(): ?\DateTimeInterface
    {
        return $this->billDate;
    }

    /**
     * @param \DateTimeInterface|null $billDate
     * @return $this
     */
    public function setBillDate(?\DateTimeInterface $billDate): self
    {
        $this->billDate = $billDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param mixed $orderNumber
     */
    public function setOrderNumber($orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return array
     */
    public function getProductDetails(): array
    {
        return $this->productDetails;
    }

    /**
     * @param array $productDetails
     * @return $this
     */
    public function setProductDetails(array $productDetails): self
    {
        $this->productDetails = $productDetails;

        return $this;
    }


    /**
     * @return Collection|Card[]
     */
    public function getCard(): Collection
    {
        return $this->card;
    }

    /**
     * @param Card $card
     * @return $this
     */
    public function addCard(Card $card): self
    {
        if (!$this->card->contains($card)) {
            $this->card[] = $card;
            $card->setOrders($this);
        }

        return $this;
    }

    /**
     * @param Card $card
     * @return $this
     */
    public function removeCard(Card $card): self
    {
        if ($this->card->contains($card)) {
            $this->card->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getOrders() === $this) {
                $card->setOrders(null);
            }
        }

        return $this;
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

    public function getTotalTva()
    {
        return $this->total * self::TVA;
    }
}
