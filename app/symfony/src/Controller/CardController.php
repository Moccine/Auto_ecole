<?php


namespace App\Controller;


use App\Entity\Card;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    /**
     * @Route("/card/code", name="card_code", methods={"GET"})
     * @param Request $request
     */
    public function cardCodeAction(Request $request)
    {

        return $this->render('pages/cardCode.html.twig');
    }

    /**
     * @Route("/card/display/{id}", name="display_card", methods={"GET"})
     */
    public function displayCardAction(Card $card)
    {

    $items = [];
    $price = 0;
        $courses = $card->getCourses()->toArray();
    /** @var Course $course */
        foreach ($courses as $course){
           $mettingPoint =$course->getMettingPoint();
            $price+=$course->getPrice();
           array_push($items,
           [
               'mettingPoint'=> $mettingPoint,
               'course' => $course

           ]);
       }
        $quantity = count($courses);

        return $this->render('card/displayCard.html.twig', [
            'courses' => $courses,
            'quantity' => $quantity,
            'items' => $items,
            'price' =>$price,
            'card' => $card,

        ]);
    }
    /**
     * @Route("/card/paid", name="paid_card", methods={"GET"})
     */
    public function payementAction(){

        return $this->render('card/paid.html.twig', []);
    }
}
