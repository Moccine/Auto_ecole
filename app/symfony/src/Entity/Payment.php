<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Payment
{
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const CURRENCY = 'eur';
    const STATUS_PENDING = 'PENDING';
    const TVA = 0.2;

    use CreatedAtTrait;
    use UpdatedAtTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $remoteId;

    /**
     * @ORM\Column(type="string", length=100,  nullable=true)
     */
    private $paymentIntent;

    /**
     * @ORM\Column(type="string", length=100,  nullable=true)
     */
    private $paymentIntentId;

    /**
     * @ORM\Column(type="float")
     */
    private $amount = 0;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $currency = self::CURRENCY;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Orders", inversedBy="payment")
     */
    private $orders;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Card", cascade={"persist", "remove"})
     */
    private $card;


    /**
     * @ORM\Column(type="string", length=100,  nullable=true)
     */
    private $stripePrivateKey;

    /**
     * Payment constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->stripePrivateKey = $_ENV['PRIVATE_KEY'];
    }

    /**
     * @return Orders|null
     */
    public function getOrders(): ?Orders
    {
        return $this->orders;
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
     * @return mixed
     */
    public function getRemoteId()
    {
        return $this->remoteId;
    }

    /**
     * @param mixed $remoteId
     */
    public function setRemoteId($remoteId): self
    {
        $this->remoteId = $remoteId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentIntent()
    {
        return $this->paymentIntent;
    }

    /**
     * @param mixed $paymentIntent
     */
    public function setPaymentIntent($paymentIntent): self
    {
        $this->paymentIntent = $paymentIntent;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

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
     * @param mixed $status
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): self
    {
        $this->currency = $currency;

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
    public function getPaymentIntentId()
    {
        return $this->paymentIntentId;
    }

    /**
     * @param $paymentIntentId
     * @return $this
     */
    public function setPaymentIntentId($paymentIntentId): self
    {
        $this->paymentIntentId = $paymentIntentId;

        return $this;
    }
}
