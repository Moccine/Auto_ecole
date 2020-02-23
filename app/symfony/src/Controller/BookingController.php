<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\Credit;
use App\Entity\MettingPoint;
use App\Entity\Shop;
use App\Entity\User;
use App\Form\InstructorHourType;
use App\Form\MettingPointType;
use App\Repository\ShopRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/booking")
 */
class BookingController extends AbstractController
{

    /**
     * @Route("/search", name="unity_booking_instructor_search_form")
     */
    public function instructorByUserdMettinPointAction(Request $request, SessionInterface $session)
    {
        $session->set('card_id', $request->query->get('card'));
        $session->remove('card_id');
        $form = $this->createForm(MettingPointType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $mettingPoint = $form->getData()['address'];
            return $this->redirect($this->generateUrl('instructor_list', array('id' => $mettingPoint->getId(),
                'card' => $request->query->get('card'),)));
            // return $this->redirectToRoute('instructor_list', ['id' => $mettingPoint->getId(), 'card' => $request->query->get('card')]);
        }
        return $this->render('instructor/instructorSearchForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * get instructor list by metting point
     * @Route("/list/{id}", name="instructor_list")
     *
     * @param Request $request
     * @param MettingPoint $mettingPoint
     */
    public function instructorsListAction(Request $request, MettingPoint $mettingPoint, FlashBagInterface $flashBag)
    {
        // $instructors = $this->getDoctrine()->getRepository(User::class)->findByRoleMettingPoint($mettingPoint, User::ROLE_RIVING_INSTRUCTOR);
        $instructors = $mettingPoint->getUsers()->filter(function (User $user) {
            return $user->getRole() === User::ROLE_RIVING_INSTRUCTOR;
        })->toArray();

        if (empty($instructors)) {
            $flashBag->set('alert', 'Aucun Moniteur');

            return $this->redirectToRoute('booking_instructor_search_form', [
                'mettingPoint' => $mettingPoint,
            ]);
        }


        return $this->render('booking/instructorList.html.twig', [
            'instructors' => $instructors,
            'mettingPoint' => $mettingPoint,
            'card' => $request->query->get('card'),
        ]);
    }


    /**
     * @Route("/hours/instructor/{instructor_id}/metting-point/{mettingPoint_id}", name="unity_list_hours_drivingins")
     * @ParamConverter("user", options={"id" = "instructor_id"})
     * @ParamConverter("mettingPoint", options={"id" = "mettingPoint_id"})
     */
    public function instructorHoursAction(Request $request, User $user, MettingPoint $mettingPoint, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(
            InstructorHourType::class,
            null,
            [
                'instructor' => $user,
                'student' => $this->getUser()
            ]
        );

        $card = $this->getDoctrine()->getRepository(Card::class)->findOneBy([
            'user' => $this->getUser(),
            'status' => Card::PENDING,
        ]);

        if (!$card instanceof Card) {
            $card = new Card();
            $card->setUser($this->getUser())->setShop(null);
            $em->persist($card);
            $em->flush();
        }
        /** @var Course $course */
        $form->handleRequest($request);

        return $this->render('booking/instructorHours.html.twig', [
            'mettingPoint' => $mettingPoint,
            'form' => $form->createView(),
            'instructor' => $user,
            'card' => $card,
        ]);
    }

    /**
     * @Route("/payment/form/{id}", name="stripe_payment_form")
     */
    public function stripeFormAction(Request $request, Card $card)
    {
        $shop = $card->getShop();

        return $this->render('payment/index.html.twig', [
            'card' => $card,
            'publickey' => $_ENV['PUBLIC_KEY'],
            'shop' => $shop ?? null
        ]);
    }

    /**
     * liste des cours acheter à l'unite
     *
     * @Route("/unity/courses/student/{id}", name="student_cardUnity_list_unity")
     */
    public function studentCardUnityListAction(User  $user)
    {
        $cards = $this->getDoctrine()->getRepository(Card::class)->findByUserAndType($user, Card::TYPE_UNITE);

        return $this->render('card/studentcardUnityList.html.twig', [
            'cards' => $cards,

        ]);
    }
}
