<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\Credit;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    /**
     * @Route("/course", name="course")
     */
    public function index()
    {
        return $this->render('course/index.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }

    /**
     * @Route("/course/edit/{id}", name="course_edit")
     */
    public function editAction(Course $course)
    {
        /** @var User $instructor */
        $instructor = $course->getInstructor();
        $mettingPoint = $course->getMettingPoint();

        return $this->redirectToRoute('unity_list_hours_drivingins', [
            'instructor_id' => $instructor->getId(),
            'mettingPoint_id' => $mettingPoint->getId()
        ]);
    }

    /**
     * @Route("/course/remove/card/{card_id}/course/{course_id}", name="course_remove")
     * @ParamConverter("course", options={"id" = "course_id"})
     * @ParamConverter("card", options={"id" = "card_id"})
     */
    public function removeAction(card $card, Course $course)
    {
        $em = $this->getDoctrine()->getManager();
        if ($card instanceof Card) {
            $card->removeCourse($course);
        }
        $em->flush();

        return $this->redirectToRoute('display_card', [ 'id' => $card->getId()]);
    }



    /**
     * liste des cours acheter à l'unite
     *
     * @Route("/unity/courses/student/{id}", name="student_course_list_unity")
     */
    public function studentCourseListAction(User  $user)
    {
        $cards = $this->getDoctrine()->getRepository(Card::class)->findByUserAndType($user, Card::TYPE_UNITE);
        $courses = [];
        /** @var Card $card */
        foreach ($cards as $card) {
            array_merge($courses, $card->getCourses()->toArray());
        }

        return $this->render('course/studentCourseList.html.twig', [
            'cards' => $cards,
            'courses' => []

        ]);
    }
}
