<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\User;
use App\Service\MailManager;
use App\Service\UserManager;
use DateTime;
use FOS\UserBundle\Form\Type\ResettingFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $user = $userManager->findUserByEmail($email);

        if ((null !== $user) && !$user->isPasswordRequestNonExpired(self::RETRYTTL)) {

            if ($user->getConfirmationToken() === null) {
                $user->setConfirmationToken($userManager->generateToken());
            }
            $mailManager->sendResettingEmailMessage($user);
            $user->setPasswordRequestedAt(new DateTime());
            $userManager->updateUser($user);
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
            return new RedirectResponse($this->generateUrl('fos_user_resetting_request'));
        }

        return $this->render('security/Resetting/check_email.html.twig',
            ['tokenLifetime' => ceil(self::RETRYTTL / 3600)]
        );
    }

    /**
     * @param Request $request
     * @param UserManager $userManager
     * @return RedirectResponse|Response
     * @Route("/reset/{token}", name="fos_user_resetting_reset", methods={"POST"})
     */
    public function resetAction(Request $request, UserManager $userManager)
    {
        $token = $request->request->get('token');
        $user = $userManager->findUserByConfirmationToken($token);
        if (!$user instanceof User) {
            return  $this->redirect("security_login");
        }

        $form = $this->createForm(ResettingFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($user);
            return $this->redirect('profile_show');
        }

        return $this->render('security/Resetting/reset.html.twig', array(
            'token' => $token,
            'form' => $form->createView(),
        ));
    }
}
