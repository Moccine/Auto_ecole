<?php


namespace App\Controller;

use App\Entity\BookingOrder;
use App\Entity\Orders;
use App\Entity\Payment;
use App\Service\MailManager;
use App\Service\PaymentLogger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class StripeWebhookController extends BaseAjaxController
{
    const WEBHOOK_AUTHORIZED_IP = [
        '54.187.174.169',
        '54.187.205.235',
        '54.187.216.72',
        '54.241.31.99',
        '54.241.31.102',
        '54.241.34.107',
    ];

    /** @var TranslatorInterface */
    private $translator;

    /**
     * StripeWebhookController constructor.
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    /**
     * @Route("/payment/callback", name="payment_callback", methods={"POST"})
     */
    public function paymentAction(Request $request, PaymentLogger $paymentLogger, MailManager $mailManager)
    {
        if (in_array($request->getClientIp(), self::WEBHOOK_AUTHORIZED_IP)) {
            $em = $this->getDoctrine()->getManager();
            $paymentLogger->logInfo('Webhook data : ' . json_encode($request->get('data')));
            $paymentIntentId = $request->get('data')['object']['id'];
            $type = $request->get('type');
            /** @var Orders $orders */
            /** @var Payment $payment */
            $payment = $this->getDoctrine()->getRepository(Payment::class)->findOneBy([
                'paymentIntent' => $paymentIntentId
            ]);

            if (!$payment instanceof Payment) {
                throw $this->createNotFoundException($this->translator->trans('payment.error.not_found'));
            }

            $orders = $payment->getOrders();

            switch ($type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentSucceeded($mailManager, $orders, $payment);
                    break;
                case 'payment_intent.canceled':
                    break;
                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailed($mailManager, $orders, $payment);
                    break;
            }

            $em->flush();
        } else {
            $mesage = sprintf('Forbidden %s', $request->getClientIp());
            $paymentLogger->logAlert($mesage);
            throw new AccessDeniedHttpException();
        }
        $paymentLogger->logInfo(sprintf('orderId: %s, status: %s', $orders->getId(), $type));

        return $this->returnJsonResponse([$payment, $paymentIntentId]);
    }

    /**
     * handle payment intent success
     *
     * @param Orders $orders
     * @param $type
     */
    public function handlePaymentSucceeded(MailManager $mailManager, Orders $orders, Payment $payment)
    {
        $this->updatePayment($payment, Payment::STATUS_SUCCESS);
        $orders->setStatus($orders::DEPOSIT_PAID);
        $mailManager->sendConfirmationMail($orders);
    }

    /**
     * Update payment status
     *
     * @param $type
     * @param $status
     * @return object|null
     */
    protected function updatePayment(Payment $payment, $status)
    {
        $payment->setStatus($status);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $payment;
    }

    /**
     * handle payment intent failed
     *
     * @param $type
     */
    public function handlePaymentFailed(MailManager $mailManager, Orders $orders, Payment $payment)
    {
        $this->updatePayment($payment, Payment::STATUS_FAILED);
        $orders->setStatus(Orders::STATUS_PENDING);
        $mailManager->sendFailledPaymentMail($orders);
    }
}
