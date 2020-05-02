<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\ResettingFormType;
use App\Service\MailManager;
use App\Service\UserManager;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

/**
 * Controller managing the resetting of the password.
 *
 */
class ResettingController extends AbstractController
{
    const  RETRYTTL = 7200;

    /**
     * Request reset user password: submit form and send email.
     * @Route("/send-email", name="resetting_send_email" )
     * @param Request $request
     * @param UserManager $userManager
     * @param MailManager $mailManager
     * @return RedirectResponse|Response|null
     */
    public function sendEmailAction(
        Request $request,
        UserManager $userManager,
        MailManager $mailManager)
    {
        $email = $request->request->get('email');
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $request->request->get('email')
        ]);
        $user = $userManager->findUserByEmail($email);
       // if ((null !== $user) && !$user->isPasswordRequestNonExpired(self::RETRYTTL)) {
        if ((null !== $user)) {
            if ($user->getConfirmationToken() === null) {
                $user->setConfirmationToken($userManager->generateToken());
            }
                $mailManager->sendResettingEmailMessage($user);
            $user->setPasswordRequestedAt(new DateTime());
            //dd($user);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirect($this->generateUrl('resetting_check_email', ['email' => $email]));
    }

    /**
     * Tell the user to check his email provider.
     * @Route("/check-email", name="resetting_check_email")
     * @param Request $request
     * @return Response
     */
    public function checkEmailAction(Request $request)
    {
        $username = $request->query->get('username');
        if (empty($username)) {
            // the user does not come from the sendEmail action
            return $this->redirect($this->generateUrl('resetting_request'));
        }

        return $this->render('security/Resetting/check_email.html.twig',
            ['tokenLifetime' => ceil(self::RETRYTTL / 3600)]
        );
    }

    /**
     * @param Request $request
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @return RedirectResponse|Response
     * @Route("/reset/{token}", name="resetting_reset")
     */
    public function resetAction(Request $request, UserManager $userManager, SessionInterface $session)
    {
        $token = $request->attributes->get('token');
        $user = $userManager->findUserByConfirmationToken($token);
        if (!$user instanceof User) {
            throw new CustomUserMessageAuthenticationException('email could not be found.');
        }

        $form = $this->createForm(ResettingFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
           // $userManager->updateUser($user);
            return  $this->redirect($this->generateUrl('security_login'));

        }

        return $this->render('security/Resetting/reset.html.twig', [
            'token' => $token,
            'form' => $form->createView(),
        ]);
    }
    /**
     * Request reset user password: show form.
     * @Route("resetting/request", name="resetting_request")
     */
    public function requestAction()
    {
        return $this->render('security/Resetting/request.html.twig');
    }
}
