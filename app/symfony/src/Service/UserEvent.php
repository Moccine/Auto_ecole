<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserEvent
 * @package App\Service
 */
class UserEvent
{
    /**
     * @var null|Request
     */
    protected $request;

    /**
     * @var UserManager
     */
    protected $user;

    /**
     * UserEvent constructor.
     *
     * @param UserManager $user
     * @param Request|null  $request
     */
    public function __construct(UserManager $user, Request $request = null)
    {
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * @return UserManager
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}