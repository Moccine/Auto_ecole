<?php


namespace App\Controller;


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
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

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
        SessionInterface $session)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        $neUser = $this->getDoctrine()->getRepository(User::class)->find(4);
        $mailManager->sendConfirmationEmailMessage($neUser);

        if ($form->isSubmitted() and $form->isValid()) {
            $PasswordHash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($PasswordHash);
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
     * @param Request $request
     * @param SessionInterface $session
     * @param UserManager $userManager
     * @return RedirectResponse|Response
     * @Route("/check-email", name="registration_check_email")
     */
    public function checkEmailAction(Request $request, SessionInterface $session, UserManager $userManager)
    {
        $email = $session->get('send_confirmation_email/email');

        if (empty($email)) {
            return $this->redirectToRoute('security_login');
        }
        $session->remove('send_confirmation_email/email');
        $user = $userManager->findUserByEmail($email);
        if (null === $user) {
            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/Registration/check_email.html.twig', array(
            'user' => $user,
        ));
    }


    /**
     * @Route("/confirmed/{id}", name="registration_confirmed")
     * @param Request $request
     * @param User $user
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @return Response
     */
    public function confirmedAction(
        Request $request,
        User $user,
        UserManager $userManager,
        SessionInterface $session)
    {

        if (!$user instanceof User) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('security/Registration/confirmed.html.twig',
            [
                'user' => $user,
                'targetUrl' => $this->getTargetUrlFromSession($session, $userManager),
            ]
        );
    }

    /**
     * @param SessionInterface $session
     * @param UserManager $userManager
     * @return mixed|null
     */
    private function getTargetUrlFromSession(SessionInterface $session, UserManager $userManager)
    {
        $key = sprintf('_security.%s.target_path', $userManager->generateToken());

        if ($session->has($key)) {
            return $session->get($key);
        }

        return null;
    }

    /**
     * @param Request $request
     * @param UserManager $userManager
     * @return RedirectResponse
     * @Route("/confirm/{token}", name="registration_confirm")
     */
    public function confirmAction(Request $request, UserManager $userManager)
    {
        $token =  $request->query->get('token')?? 'test';
        $user = $userManager->findUserByConfirmationToken($token);
        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }
        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $userManager->updateUser($user);
        $url = $this->generateUrl('registration_confirmed', ['token' => $token]);
        return $this->redirect($url);
    }

}