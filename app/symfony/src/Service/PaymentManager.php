<?php


namespace App\Service;

;

use App\Entity\Orders;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaymentManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var OrderManager
     */
    private $bookingmanager;
    private $translator;

    /**
     * PaymentManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, OrderManager $bookingManager, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->bookingmanager = $bookingManager;
        $this->translator = $translator;
    }

    /**
     * @return $this
     */
    public function setApiKey(string $apiKey)
    {
        Stripe::setApiKey($apiKey);
    }

    /**
     * @return PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function retrievePayment(Payment $payment)
    {
        return PaymentIntent::retrieve($payment->getPaymentIntentId());
    }

    /**
     * Determine if the payment was cancelled.
     *
     * @return bool
     */
    public function isCancelled(PaymentIntent $paymentIntent)
    {
        return $paymentIntent->status === PaymentIntent::STATUS_CANCELED;
    }

    /**
     * Determine if the payment was successful.
     *
     * @return bool
     */
    public function isSucceeded(PaymentIntent $paymentIntent)
    {
        return $paymentIntent->status === PaymentIntent::STATUS_SUCCEEDED;
    }

    public function validatePayment(payment $payment, PaymentIntent $intent)
    {
        return $payment->getPaymentIntentId() === $intent->id;
    }

    /**
     * @param Orders $orders
     * @param array $options
     * @return Customer
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createPaymentCustomer(Orders $orders, array $options = [])
    {
        $student = $orders->getStudent();
        $data = [
            'email' => $student->getEmail(),
            'name' => sprintf('%s %s', $student->getFirstName(), $student->getLastName()),
            'phone' => $student->getPhone(),
        ];
        if ($options && is_array($options)) {
            $data = array_merge($options, $data);
        }

        return Customer::create($data);
    }

    /**
     * @param Orders $orders
     * @param array $options
     * @return PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createIntent(Orders $orders, array $options = [])
    {
        $student = $orders->getStudent();

        $description = sprintf("Numéro de commande: %s %s %s",
            $orders->getOrderNumber(),
            $student->getFirstName(), $student->getLastName());

        $data = [
            'amount' => $orders->getTotal() * 100,
            'currency' => Payment::CURRENCY,
            'description' => $description,
            'metadata' => [
                'order' => $orders->getId(),
            ]];

        if ($options && is_array($options)) {
            $data = array_merge($options, $data);
        }

        return PaymentIntent::create($data);
    }

    /**
     * @param PaymentIntent $intent
     * @return mixed|null
     */
    public function getClientSecret(PaymentIntent $intent)
    {
        return $intent['client_secret'];
    }

    /**
     * @param PaymentIntent $intent
     * @return Payment
     * @throws \Exception
     */
    public function createPayment(PaymentIntent $intent)
    {/** @var Payment $payment */
        $payment = $this->em->getRepository(Payment::class)->findOneBy([
            'paymentIntentId' => $intent->id
        ]);
        if (!$payment instanceof Payment) {
            throw new \Exception($this->translator->trans('payment.found'));
        }

        return $payment;
    }

    /**
     * @param PaymentIntent $intent
     * @throws \Stripe\Exception\ApiErrorException
     * @throws \Exception
     */
    public function isValidPaymentIntent(PaymentIntent $intent)
    {
        $paymentIntent = PaymentIntent::retrieve($intent->id);

        return ($paymentIntent instanceof PaymentIntent);
    }

    public function setPrivateKey(){
        Stripe::setApiKey($_ENV['PRIVATE_KEY']);
    }
}
