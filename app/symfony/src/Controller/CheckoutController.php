<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\Orders;
use App\Entity\Payment;
use App\Entity\Shop;
use App\Service\PaymentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout/{id}", name="checkout", methods={"POST"})
     * @throws \Exception
     */
    public function index(
        Request $request,
        Card $card,
                          PaymentManager $paymentManager,
                          TranslatorInterface $translator,
                          Session $session
    ) {
        try {
            /** @var Session $session */
            $em = $this->getDoctrine()->getManager();
            $paymentManager->setPrivateKey();
            $payment = $em->getRepository(Payment::class)->findOneBy([
                'card' => $card
            ]);

            $intent = $paymentManager->retrievePayment($payment);

            if (!$paymentManager->validatePayment($payment, $intent)) {
                throw $this->createNotFoundException($translator->trans('intent.error.not_found'));
            }
            $intent->charges->data;
            $payment = $paymentManager->createPayment($intent);
            $bookingOrder = $payment->getOrders();

            if ($bookingOrder instanceof Orders) {
                $bookingOrder->setStatus(Orders::PAID);
                $payment->setStatus(Payment::STATUS_SUCCESS);
                $card->setStatus(Card::PAID);
                $card->getCourses()->map(function (Course $course) {
                    $course->setStatus(Course::PAID);
                });
            }
            $em->flush();
            $session->set('card', $card);
            $session->set('order', $bookingOrder);
        } catch (\Exception $exception) {
            return $this->render('checkout/failed.html.twig', [
                'card' => $card,
            ]);
        }


        return $this->redirectToRoute('checkout_confirmation');
    }

    /**
     * @Route("/checkout/confirmation/", name="checkout_confirmation")
     * @throws \Exception
     */
    public function confirmationActiion(Request $request, PaymentManager $paymentManager, TranslatorInterface $translator, Session $session)
    {
        /** @var Card $card */
        $card = $session->get('card');
        /** @var Payment $payment */
        $payment = $this->getDoctrine()->getRepository(Payment::class)->findOneBy([
            'card' => $card
        ]);
        $courses = $this->getDoctrine()->getRepository(Course::class)->findBy([
            'card' => $card
        ]);
        $shop = null;
        if ($card->getType() == Card::TYPE_PACKAGE) {
            $shop = $this->getDoctrine()->getRepository(Shop::class)->find($card->getShop()->getId());
        }

        return $this->render('checkout/confirmation.html.twig', [
            'card' => $session->get('card'),
            'order' => $payment->getOrders(),
            'courses' => $courses,
            'shop' => $shop,
        ]);
    }
}
