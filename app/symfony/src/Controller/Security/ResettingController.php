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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @param Translator $translator
     * @return RedirectResponse|Response|null
     */
    public function sendEmailAction(
        Request $request,
        UserManager $userManager,
        MailManager $mailManager,
        TranslatorInterface $translator
    ) {
        $email = $request->request->get('email');
        $user = $userManager->findUserByEmail($email);
        if (!$user instanceof User) {
            $this->addFlash('error', 'Impossible de reinitialiser');
            return $this->redirectToRoute('security_login');
        }
        if ($user->isPasswordRequestNonExpired(self::RETRYTTL)) {
            $this->addFlash('error', 'Vous avez recemment reinitialiser votre mot de passe');
            return $this->redirectToRoute('security_login');
        }
        if ($user->getConfirmationToken() === null) {
            $user->setConfirmationToken($userManager->generateToken());
        }
        $mailManager->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new DateTime());
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', $translator->trans('resetting.check_email', ['%tokenLifetime%' => ceil(self::RETRYTTL / 3600)]));
        return new RedirectResponse($this->generateUrl('resetting_check_email', ['email' => $email]));
    }

    /**
     * Tell the user to check his email provider.
     * @Route("/check-email", name="resetting_check_email", methods={"GET"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function checkEmailAction(Request $request)
    {
        $email = $request->query->get('email');
        if (empty($email)) {
            $this->addFlash('error', 'user not found');
            return $this->redirectToRoute('resetting_request');
        }
        $this->addFlash('error', 'user not found');

        return $this->render(
            'security/Resetting/check_email.html.twig',
            ['tokenLifetime' => ceil(self::RETRYTTL / 3600)]
        );
    }

    /**
     * @param Request $request
     * @param UserManager $userManager
     * @param Session $session
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     * @Route("/reset/{token}", name="resetting_reset")
     */
    public function resetAction(
        Request $request,
        UserManager $userManager,
        Session $session,
        UserPasswordEncoderInterface $encoder
    ) {
        $token = $request->attributes->get('token');
        $user = $userManager->findUserByConfirmationToken($token);
        if (!$user instanceof User) {
            $this->addFlash('error', 'user not found');
            return $this->redirect($this->generateUrl('security_login'));
        }

        $form = $this->createForm(ResettingFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setConfirmationToken(null);
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre mot a été mis à jour');

            return $this->redirect($this->generateUrl('security_login'));
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
