<?php


namespace App\Service;

use App\Entity\Booking;
use App\Entity\BookingOrder;
use App\Entity\Orders;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MailManager
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $twig;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    private $adminMail;
    /** @var ContainerInterface */
    private $container;

    /**
     * MailManager constructor.
     * @param ObjectManager $entityManager
     * @param \Swift_Mailer $mailer
     * @param EngineInterface $twig
     * @param TranslatorInterface $translator
     * @param string $oiseMail
     */
    public function __construct(
        ObjectManager $entityManager,
                                \Swift_Mailer $mailer,
                                EngineInterface $twig,
                                TranslatorInterface $translator,
                                ContainerInterface $container
    ) {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->translator = $translator;
        $this->adminMail = $_ENV['ADMIN_MAIL'];
        $this->container = $container;
    }

    /**
     * @param Orders $orders
     */
    public function sendConfirmationMail(Orders $orders)
    {
        $accommodationPricing = null;
        $this->sendCustomerMail($orders, ['to' => $orders->getStudent()->getEmail()]);
        $this->sendAgencyMail($orders, $this->translator->trans('email.agency.subject.success'));
    }

    /**
     * @param Orders $orders
     * @param array $recipients
     */
    public function sendCustomerMail(Orders $orders, array $recipients): void
    {
        $content = $this->twig->render('/mail/customer.confirmation.html.twig', $this->getMailDatas($orders));
        $this->sendMail(
            $content,
            $this->translator->trans('email.customer.subject', ['%orderNumber%' => $bookingOrder->getCode()]),
            array_merge(['cc' => $this->adminMail], $recipients),
            $this->adminMail
        );
    }

    /**
     * @param Orders $orders
     * @return array
     */
    public function getMailDatas(Orders $orders)
    {
        return [
            'student' => $orders->getStudent(),
            'orders' => $orders,
        ];
    }

    /**
     * @param string $content
     * @param string $subject
     * @param array $recipients like ['to' => ['email@mail.com', ...], 'cc' => [], 'bcc' => [...]]
     * @param null|string $contactName
     */
    public function sendMail($content, $subject = '', array $recipients = [], $contactName = false): void
    {
        $to = $recipients['to'] ?? [];
        $cc = $recipients['cc'] ?? [];
        $bcc = $recipients['bcc'] ?? [];
        $from = $to;
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setCc($cc)
            ->setBcc($bcc)
            ->setBody($content, 'text/html');

        $this->mailer->send($message);
    }

    /**
     * @param array $data
     */
    public function sendAgencyMail(Orders $orders, string $subject): void
    {
        $content = $this->twig->render('/mail/agency.confirmation.html.twig', $this->getMailDatas($orders));
        $this->sendMail($content, $subject, ['to' => $this->adminMail], $this->adminMail);
    }

    /**
     * Failed agency mail
     *
     * @param Orders $orders
     */
    public function sendFailledPaymentMail(Orders $orders)
    {
        $this->sendAgencyMail($orders, $this->translator->trans('email.agency.subject.failed'));
    }
}
