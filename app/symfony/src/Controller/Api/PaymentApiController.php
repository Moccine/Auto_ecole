<?php


namespace App\Controller\Api;

use App\Controller\BaseAjaxController;
use App\Entity\Card;
use App\Entity\Credit;
use App\Entity\Orders;
use App\Entity\Payment;
use App\Entity\User;
use App\Service\PaymentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PaymentApiController extends BaseAjaxController
{


    /**
     * @Route("/add/payement/{card_id}" , name="api_web_edit_payment", methods={"POST"})
     * @ParamConverter("card", options={"id" = "card_id"})
     * @param Request $request
     * @throws \Stripe\Exception\ApiErrorException
     * @throws \Exception
     */
    public function editPaymentAction(Card $card, PaymentManager $paymentManager)
    {
        $em = $this->getDoctrine()->getManager();
        $payment = new Payment();
        /** @var User $user */
        $this->createPayment($card);
        $orders = $this->createOrder($card);
        if ($card->getType() == Card::TYPE_PACKAGE) {
            $credit = $this->createCredit($card);
            $em->persist($credit);
        }
        $em->persist($orders);
        $em->persist($payment);
        $paymentManager->setPrivateKey();
        $paymentManager->createPaymentCustomer($orders);
        $intent = $paymentManager->createIntent($orders);
        if (!$paymentManager->isValidPaymentIntent($intent)) {
            throw new \Exception('Is not a Stripe intent');
        }
        $payment->setPaymentIntentId($intent['id']);
        $payment->setOrders($orders);
        $payment->setCard($card);
        $clientSecret = $paymentManager->getClientSecret($intent);

        $em->flush();

        return $this->returnJsonResponse([
            'total' => $orders->getTotal(),
            'clientSecret' => $clientSecret
        ], 'success');
    }

    /**
     * create Payment with card
     *
     * @param Card $card
     * @return Payment
     * @throws \Exception
     */
    public function createPayment(Card $card)
    {
        $payment = new Payment();
        $payment->setCurrency(Payment::CURRENCY)
            ->setAmount($card->getTotal())
            ->setStatus(Payment::STATUS_PENDING)
            ->setAmount($card->getTotal());
        return $payment;
    }

    /**
     * create Order with card
     *
     * @param Card $card
     * @return Orders
     * @throws \Exception
     */
    public function createOrder(Card $card)
    {
        $orders = new Orders();
        return $orders->setStudent($this->getUser())
            ->setCurrency(Payment::CURRENCY)
            ->setTva(Payment::TVA)
            ->setTotal($card->getTotal())
            ->setStatus(Payment::STATUS_PENDING)
            ->setQuantity($card->getCourses()->count());
    }

    /**
     * create Credit wit card
     *
     * @param Card $card
     * @return Credit
     */
    public function createCredit(Card $card)
    {
        $credit = new Credit();
        $courseNumber = $card->getShop()->getCourseNumber();
        return $credit->setCard($card)
            ->setTotal($card->getTotal())
            ->setShop($card->getShop())
            ->setRest($card->getTotal())
            ->setRestCourseNumber($courseNumber)
            ->setCourseNumber($courseNumber);
    }
}
