<?php


namespace App\Service;

use App\Entity\Orders;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MailManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Swift_Mailer
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
     * @var RouterInterface
     */
    private $router;

    /**
     * MailManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param Swift_Mailer $mailer
     * @param EngineInterface $twig
     * @param TranslatorInterface $translator
     * @param ContainerInterface $container
     * @param RouterInterface $router
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Swift_Mailer $mailer,
        EngineInterface $twig,
        TranslatorInterface $translator,
        ContainerInterface $container,
        RouterInterface $router
    )
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->translator = $translator;
        $this->adminMail = $_ENV['ADMIN_MAIL'];
        $this->container = $container;
        $this->router = $router;
    }

    /**
     * @param Orders $orders
     */
    public function sendConfirmationMail(Orders $orders)
    {
        $accommodationPricing = null;
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
     * @param bool $contactName
     */
    public function sendMail($content, $subject = '', array $recipients = [], $contactName = false)
    {
        $to = $recipients['to'] ?? [];
        $cc = $recipients['cc'] ?? [];
        $bcc = $recipients['bcc'] ?? [];
        $from = $contactName;
        if ($contactName === false) {
            $from = 'msow@kernix.com';
        }
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setCc($cc)
            ->setBcc($bcc)
            ->setBody($content, 'text/html');
        try {
            $this->mailer->send($message);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * @param User $user
     */
    public function sendConfirmationEmailMessage(User $user)
    {
        try {
            $url = $this->router->generate(
                'registration_confirm',
                ['token' => $user->getConfirmationToken()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            $content = $this->twig->render('security/Registration/email.txt.twig', [
                'user' => $user,
                'confirmationUrl' => $url
            ]);
            $subject = $this->translator->trans('registration.email.subject', ['%username%' => $user->getUsername()]);
            $this->sendMail($content, $subject, [$user->getEmail()]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * @param User $user
     * @return string
     */
    public function sendResettingEmailMessage(User $user)
    {
        try {
            $url = $this->router->generate(
                'resetting_reset',
                [
                    'token' => $user->getConfirmationToken()
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            $content = $this->twig->render('security/Resetting/email.txt.twig', [
                'user' => $user,
                'confirmationUrl' => $url
            ]);
            $subject = $this->translator->trans('resetting.email.subject', ['%username%' => $user->getUsername()]);
            $this->sendMail($content, $subject, [$user->getEmail()]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
