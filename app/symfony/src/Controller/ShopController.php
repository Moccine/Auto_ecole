<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Shop;
use App\Entity\User;
use App\Form\ShopType;
use Nelmio\ApiDocBundle\Annotation\Security;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shop",)
 */
class ShopController extends AbstractController
{
    /**
     * @Route("/list", name="shop_list")
     * @param SessionInterface $session
     * @return Response
     */
    public function index(SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $hops = $em->getRepository(Shop::class)->findAll();
        if(empty($hops)){
            $session->getFlashBag()->add('error', 'Liste vide ');
        }
        return $this->render('shop/index.html.twig', [
            'shops' => $hops,
        ]);
    }

    /**
     * @Route("/add/", name="shop_add")
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(ShopType::class, new Shop());
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            return $this->redirectToRoute('shop_list');
        }

        return $this->render('shop/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="shop_edit")
     */
    public function edit(Request $request, Shop $shop)
    {
        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            return $this->redirectToRoute('shop_list');
        }

        return $this->render('shop/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/remove/{id}", name="shop_remove")
     */
    public function remove(Request $request, SessionInterface $session, Shop $shop)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $em->remove($shop);
            $em->flush();
            $session->getFlashBag()->add('notice', 'Profile updated');
            return $this->redirectToRoute('shop_list');
        } catch (\Exception $e) {
            return $this->redirectToRoute('shop_list');
        }
    }

    /**
     * @Route("/", name="shop_display")
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function shop(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $bestOffers = $em->getRepository(Shop::class)->findBy([
            'priority' => Shop::BEST_OFFERS
        ]);
        $drivingCards = $em->getRepository(Shop::class)->findBy([
            'priority' => Shop::DRIVING_CARD
        ]);

        return $this->render('shop/shop.html.twig', [
            'bestOffers' => $bestOffers,
            'drivingCards' => $drivingCards,
        ]);
    }

    /**
     * @Route("/add/product/{id}", name="add_product")
     * @param Request $request
     * @param SessionInterface $session
     * @param Shop $shop
     * @return RedirectResponse
     */
    public function addProductToCard(Request $request, SessionInterface $session, Shop $shop)
    {
        $em = $this->getDoctrine()->getManager();
        $card = new Card();
        /** @var User $user */
        $user = $this->getUser();
        /** @var Float $price */
        $price = $shop->getPrice();
        $card->setTotal($price)
            ->setUser($user)
            ->setType(Card::SHOP)
            ->setShop($shop);
        $em->persist($card);
        $em->flush();
        return $this->redirectToRoute('stripe_payment_form', ['id' => $card->getId()]);
    }
}
