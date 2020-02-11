<?php


namespace App\Service;


use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class LocationManager
{
    use ControllerTrait;
    /** @var ContainerInterface */
   private $container;
   private $router;
   private $session;

    /**
     * LocationManager constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->session = $this->container->get('session');
        $this->router = $this->container->get('router');
    }


    public function checkLocationInstance($location, $message, $level)
    {

        if (!$location instanceof Location) {
            $this->addFlash($level, $message);

            return false;
        }
        return true;
    }
}
