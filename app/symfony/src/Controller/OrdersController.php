<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrdersController
 * @package App\Controller
 *  @Route("/checkout")
 */
class OrdersController extends AbstractController
{
    /**
     * @Route("/orders", name="orders")
     */
    public function index()
    {
        return $this->render('orders/index.html.twig', [
            'controller_name' => 'OrdersController',
        ]);
    }
    /**
     * @Route("/orders/checkout", name="checkout_order")
     */
    public function checkoutAction(Request $request){

        return $this->render('orders/checkout.html.twig', [
            'orders' => 'OrdersController',
        ]);
    }
}
