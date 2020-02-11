<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\Credit;
use App\Entity\MettingPoint;
use App\Entity\User;
use App\Form\InstructorHourType;
use App\Form\MettingPointType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PackageController extends AbstractController
{
    /**
     * @Route("/package", name="package")
     */
    public function index()
    {
        return $this->render('package/index.html.twig', [
            'controller_name' => 'PackageController',
        ]);
    }
    /**
     * @Route("/package/booking/metting-point/seacrch/card/{id}/", name="package_metting_search_form")
     */
    public function packageBookingAction(Request $request, Card $card, SessionInterface $session)
    {
        $session->set('card_id', $request->query->get('card'));
        $session->remove('card_id');
        $form = $this->createForm(MettingPointType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            /** @var MettingPoint $mettingPoint */
            $mettingPoint = $form->getData()['address'];

            return $this->redirectToRoute('booking_instructor_list_by_metting_point',
                [
                    'id' => $mettingPoint->getId(),
                    'mp_id' => $mettingPoint->getId(),
                    'card_id' => $card->getId(),
            ]);

        }
        return $this->render('package/instructorSearchForm.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * get instructor list by metting point
     * @Route("/list/booking/metting-point/{mp_id}/card/{card_id}/", name="booking_instructor_list_by_metting_point")
     * @ParamConverter("mettingPoint", options={"id" = "mp_id"})
     * @ParamConverter("card", options={"id" = "card_id"})

     *
     * @param Request $request
     * @param MettingPoint $mettingPoint
     */
    public function bookingPackegeInstructorsListAction(Request $request, MettingPoint $mettingPoint, Card $card, FlashBagInterface $flashBag)
    {
        $instructors = $mettingPoint->getUsers()->filter(function (User $user) {
            return $user->getRole() === User::ROLE_RIVING_INSTRUCTOR;
        })->toArray();

        if (empty($instructors)) {
            $flashBag->set('alert', 'Aucun Moniteur');

            return $this->redirectToRoute('booking_instructor_search_form', [
                'mettingPoint' => $mettingPoint,
            ]);
        }

        return $this->render('package/instructorList.html.twig', [
            'instructors' => $instructors,
            'mettingPoint' => $mettingPoint,
            'card' => $card,
        ]);
    }



    /**
     * @Route("/hours/metting-point/{mp_id}/instructor/{instructor_id}/card/{card_id}", name="list_horurs_drivingins")
     * @ParamConverter("user", options={"id" = "instructor_id"})
     * @ParamConverter("mettingPoint", options={"id" = "mp_id"})
     * @ParamConverter("card", options={"id" = "card_id"})
     */
    public function instructorHoursAction(Request $request, User $user, MettingPoint $mettingPoint, Card $card, SessionInterface $session)
    {
        $form = $this->createForm(InstructorHourType::class, null,
            [
                'instructor' => $user,
                'student' => $this->getUser()
            ]
        );

            $credit = $this->getDoctrine()->getRepository(Credit::class)->findOneBy([
                'card' => $card
            ]);

        /** @var Course $course */
        $form->handleRequest($request);

        return $this->render('package/instructorHours.html.twig', [
            'mettingPoint' => $mettingPoint,
            'form' => $form->createView(),
            'instructor' => $user,
            'card' => $card,
            'credit' => $credit
        ]);
    }

    /**
     * @Route("/package/courses/list/card/{id}", name="package_card_courses_list", methods={"GET"})
     */
    public function packageCoursesListAction(Card $card)
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
        $credit = $this->getDoctrine()->getRepository(Credit::class)->findOneBy([
            'card' => $card
        ]);
        $quantity = count($courses);

        return $this->render('package/packageCoursesList.html.twig', [
            'courses' => $courses,
            'quantity' => $quantity,
            'items' => $items,
            'price' =>$price,
            'card' => $card,
            'credit' => $credit,

        ]);
    }

    /**
     * liste des cours acheter par package
     *
     * @Route("/package/courses/list", name="package_course_list")
     */
    public function studentPackageCourseListAction()
    {
        //student_course_list_package

        $cards = $this->getDoctrine()->getRepository(Card::class)->findByUserAndType($this->getUser(), Card::TYPE_PACKAGE);
        $cardDatas = [];
        foreach ($cards as $card){
            /** @var Credit $credit */
            $credit = $this->getDoctrine()->getRepository(Credit::class)->findOneBy(['card' => $card]);


            array_push($cardDatas, [
                'card' => $card,
                'credit' =>$credit,
                ]);
        }
        $courses = [];
        /** @var Card $card */
        foreach ($cards as $card){
            array_merge($courses, $card->getCourses()->toArray());
        }

        return $this->render('package/packageCardList.html.twig', [
            'courses' => $courses,
            'cards' => $cards,
            'cardDatas' => $cardDatas

        ]);

    }
}
