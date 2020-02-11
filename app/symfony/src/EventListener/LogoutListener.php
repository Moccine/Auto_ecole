<?php


namespace App\EventListener;


use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class LogoutListener implements LogoutHandlerInterface
{
    /** @var UserManagerInterface $userManager */
    private $userManager;
    /** @var SessionInterface $session */
    private $session;


    public function __construct(UserManagerInterface $userManager, SessionInterface $session){

        $this->userManager = $userManager;
        $this->session = $session;
    }

    /**
     * @inheritDoc
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
     if($this->session->has('card')){
         $this->session->remove('card');
     }

    }
}
