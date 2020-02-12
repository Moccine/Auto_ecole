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
     */
    public function indexAction(Request $request)
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

        return $this->render('default/index.html.twig', [
            'shops'=> $hops,
            'bestOffers' => $bestOffers,
            'drivingCards' => $drivingCards,


        ]);
    }
}
