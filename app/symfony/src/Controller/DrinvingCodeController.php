<?php

namespace App\Controller;

use App\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DrinvingCodeController extends AbstractController
{
    /**
     * @Route("/drinving/code", name="drinving_code")
     */
    public function index()
    {
        $hops = $this->getDoctrine()->getRepository(Shop::class)->findBy([
            'priority' => 2
        ]);

        $em = $this->getDoctrine()->getManager();
        $bestOffers = $em->getRepository(Shop::class)->findBy([
            'priority' => 1
        ]);
        $drivingCards = $em->getRepository(Shop::class)->findBy([
            'priority' => 2
        ]);
        return $this->render('drinving_code/index.html.twig', [
            'bestOffers' => $bestOffers,
            'drivingCards' => $drivingCards,
        ]);
    }
}
