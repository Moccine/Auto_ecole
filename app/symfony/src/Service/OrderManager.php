<?php


namespace App\Service;

use App\Entity\Card;
use App\Entity\Orders;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderManager
{
    use ControllerTrait;
    const DATE_REGEX_FORMAT = '/(\d{4}-\d{2}-\d{2})/m';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $translator;
    private $pricingManager;

    /**
     * BookingManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, PricingManager $pricingManager)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->pricingManager = $pricingManager;
    }




    public function createOrders(Card $card)
    {
        $Orders = new Orders();
        /** @var User $user */
        $user = $card->getUser();
        $Orders->setStudent($user);

        return $Orders;
    }



}
