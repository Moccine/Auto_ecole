<?php


namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Service\MailManager;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class RegistrationController
 * @package App\Controller
 */
class RegistrationController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param MailManager $mailManager
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @return Response
     * @Route("/register", name="security_registration")
     */
    public function registration(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        MailManager $mailManager,
        UserManager $userManager,
        SessionInterface $session
    ) {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password)->setPlainPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $user->setEnabled(false);
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($userManager->generateToken());
            }
            $em->flush();
            $mailManager->sendConfirmationEmailMessage($user);
            $session->set('send_confirmation_email/email', $user->getEmail());

            return $this->redirectToRoute('registration_check_email');
        }

        return $this->render("security/register.html.twig", [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @param SessionInterface $session
     * @param UserManager $userManager
     * @return RedirectResponse|Response
     * @Route("/registration-check-email", name="registration_check_email")
     */
    public function checkEmailAction(SessionInterface $session, UserManager $userManager)
    {
        $email = $session->get('send_confirmation_email/email');

        if (empty($email)) {
            return $this->redirectToRoute('security_login');
        }
        $session->remove('send_confirmation_email/email');
        /** @var User $user */
        $user = $userManager->findUserByEmail($email);
        if (null === $user) {
            return $this->redirectToRoute('security_login');
        }

        return $this->render(
            'security/Registration/check_email.html.twig',
            [
                'user' => $user,
            ]
        );
    }


    /**
     * @Route("/confirmed/{id}", name="registration_confirmed")
     * @param User $user
     * @param SessionInterface $session
     * @return RedirectResponse
     */
    public function confirmedAction(User $user, SessionInterface $session)
    {
        if (!$user instanceof User) {
            $session ->getFlashBag();
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->redirectToRoute('security_login');
    }


    /**
     * @param Request $request
     * @param UserManager $userManager
     * @return RedirectResponse
     * @Route("/confirm/{token}", name="registration_confirm")
     */
    public function confirmAction(Request $request, UserManager $userManager)
    {
        $token = $request->attributes->get('token');
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'confirmationToken' => $token
        ]);

        if (!$user instanceof User) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }
        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('registration_confirmed', [
            'id' => $user->getId()
        ]);
    }
}
