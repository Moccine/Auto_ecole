<?php


namespace App\Controller;

use App\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage", methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $hops = $this->getDoctrine()->getRepository(Shop::class)->findBy([
        'priority' => 2
    ]);

        $em = $this->getDoctrine()->getManager();
        $bestOffers = $em->getRepository(Shop::class)->findBy([
            'priority' => Shop::BEST_OFFERS
        ]);
        $drivingCards = $em->getRepository(Shop::class)->findBy([
            'priority' => Shop::DRIVING_CARD
        ]);

        return $this->render('default/index.html.twig', [
            'shops'=> $hops,
            'bestOffers' => $bestOffers,
            'drivingCards' => $drivingCards,


        ]);
    }
}
