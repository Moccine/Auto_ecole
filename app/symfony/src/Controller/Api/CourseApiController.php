<?php


namespace App\Controller\Api;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\Credit;
use App\Entity\MettingPoint;
use App\Entity\User;
use App\Service\CourseManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CourseApiController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * CourseApiController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/add/course/{instructor_id}", name="add_course", methods={"GET"})
     * @ParamConverter("user", options={"id" = "instructor_id"})
     * @param Request $request
     * @param User $user
     * @param CourseManager $courseManager
     * @return JsonResponse
     */
    public function addCourseAction(Request $request, User $user, CourseManager $courseManager)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var MettingPoint $mettingPoint */
        $mettingPoint =  $em->getRepository(MettingPoint::class)->find($request->query->get('mettingPoint'));
        /** @var User $instructor */
        $instructor =  $em->getRepository(User::class)->find($request->query->get('instructor'));
        /** @var User $student */
        $student = $this->getUser();

        $courseRepository = $this->getDoctrine()->getRepository(Course::class);
        /** @var SessionInterface $session */
        $session = $this->get('session');
        $courseHour = (int)$request->query->get('hour');
        $courseDateTime = $request->query->get('datetime');
        $courseDate = \DateTime::createFromFormat('Y/m/d', $courseDateTime)->setTime($courseHour, 0, 0);
        $course = $courseRepository->findOneBy([
            'student' => $student,
            'instructor' => $user,
            'courseDate' => $courseDate,
        ]);

        if (!$course instanceof Course) {
            $course = new Course();
            $em->persist($course);
        }
        $course->setStudent($student)
            ->setInstructor($instructor)
            ->setCoursedate($courseDate)
            ->setMettingPoint($mettingPoint);


        $card = $this->getDoctrine()->getRepository(Card::class)->findOneBy([
            'user' => $student,
            'status' => Card::PENDING
        ]);

        if (!$card instanceof Card) {
            $card = new Card();
            $card->setUser($student);
            $em->persist($card);
        }

        $courseManager->addCourse($card, $course);
        $session->set('card', $card);
        $price = 0;

        $courses = $card->getCourses();

        if ($courses->count()>0) {
            foreach ($courses->toArray() as $course) {
                if ($course instanceof Course) {
                    $price += (float)$course->getPrice();
                }
            }
        }
        $card->setTotal($price);
        $em->flush();

        return $this->json([
            'message' => 'success',
            'price' => $price,
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/remove/coure/{instructor_id}", name="remove_course", methods={"GET"})
     * @ParamConverter("user", options={"id" = "instructor_id"})
     * @param Request $request
     */
    public function removeCourseAction(Request $request, User $user, CourseManager $courseManager)
    {

        /** @var SessionInterface $session */
        $session = $this->get('session');

        $courseRepository = $this->getDoctrine()->getRepository(Course::class);
        $courseHour = (int)$request->query->get('hour');
        $em = $this->getDoctrine()->getManager();
        /** @var User $student */
        $student = $this->getUser();
        $courseDateTime = $request->query->get('datetime');
        $courseDate = \DateTime::createFromFormat('Y/m/d', $courseDateTime)->setTime($courseHour, 0, 0);
        $course = $courseRepository->findOneBy([
            'student' => $student,
            'instructor' => $user,
            'courseDate' => $courseDate,
        ]);
        $card = $this->getDoctrine()->getRepository(Card::class)->findOneBy([
            'user' => $student,
            'status' => Card::PENDING
        ]);

        if ($course instanceof Course and $card instanceof Card) {
            /** @var Card $card */
            $courseManager->removeCourse($card, $course);
            $em->remove($course);
            $em->flush();
        }
        $price = 0;
        $courses = $card->getCourses();
        if ($courses->count()>0) {
            foreach ($courses->toArray() as $course) {
                if ($course instanceof Course) {
                    $price += (float)$course->getPrice();
                }
            }
        }
        $card->setTotal($price);
        $em->flush();
        $session->set('card', $card);
        return $this->json([
            'message' => 'succes',
            'price' => $price//float)array_sum($price),

        ], Response::HTTP_OK);
    }

    /**
     * @Route("/course/info/{id}", name="course_info")
     */
    public function CourseInformationAction(Course $course)
    {
        $template = $this->renderView('course/courseInfo.html.twig', ['course' => $course,]);
        return $this->json([
            'template' => $template,
             'success' => true, //float)array_sum($price),

        ]);
    }

    /**
     * @Route("/add/package/course/card/{card_id}/credit/{credit_id}", name="add_package_course", methods={"GET"})
     * @ParamConverter("card", options={"id" = "card_id"})
     * @ParamConverter("credit", options={"id" = "credit_id"})
     * @param Request $request
     */

    public function addpackageCourse(request $request, Card $card, Credit $credit)
    {
        if ($credit->getRestCourseNumber() ===0) {
            return $this->json([
                'message' => 'failed',
                'restCourseNumber' => $credit->getRestCourseNumber(),
                'courseNumber' => $credit->getCourseNumber(),
            ], Response::HTTP_OK);
        }
        $em = $this->getDoctrine()->getManager();
        /** @var MettingPoint $mettingPoint */
        $mettingPoint =  $em->getRepository(MettingPoint::class)->find($request->query->get('mettingPointId'));
        /** @var User $instructor */
        $instructor =  $em->getRepository(User::class)->find($request->query->get('instructorId'));
        /** @var User $student */
        $student = $this->getUser();
        $courseRepository = $this->getDoctrine()->getRepository(Course::class);
        /** @var SessionInterface $session */
        $session = $this->get('session');
        $courseHour = (int)$request->query->get('hour');
        $courseDateTime = $request->query->get('datetime');
        $courseDate = \DateTime::createFromFormat('d/m/Y', $courseDateTime)->setTime($courseHour, 0, 0);
        $course = $courseRepository->findOneBy([
            'student' => $student,
            'instructor' => $instructor,
            'courseDate' => $courseDate,
        ]);

        if (!$course instanceof Course) {
            $course = new Course();
            $course->setCard($card);
            $em->persist($course);
        }

        $course->setStudent($student)
            ->setInstructor($instructor)
            ->setCoursedate($courseDate)
            ->setMettingPoint($mettingPoint);
        $credit->setRestCourseNumber((int)($credit->getRestCourseNumber()-1));
        $em->flush();


        return $this->json([
            'message' => 'success',
            'restCourseNumber' => $credit->getRestCourseNumber(),
            'courseNumber' => $credit->getCourseNumber(),
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/remove/package/course/card/{card_id}/credit/{credit_id}", name="remove_package_course", methods={"GET"})
     * @ParamConverter("card", options={"id" = "card_id"})
     * @ParamConverter("credit", options={"id" = "credit_id"})
     * @param Request $request
     */
    public function removePackageCourseAction(Request $request, card $card, Credit $credit, CourseManager $courseManager)
    {

        /** @var SessionInterface $session */
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();

        /** @var MettingPoint $mettingPoint */
        $mettingPoint =  $em->getRepository(MettingPoint::class)->find($request->query->get('mettingPointId'));
        /** @var User $instructor */
        $instructor =  $em->getRepository(User::class)->find($request->query->get('instructorId'));
        /** @var User $student */
        $student = $this->getUser();

        $courseRepository = $this->getDoctrine()->getRepository(Course::class);
        $courseHour = (int)$request->query->get('hour');

        $courseDateTime = $request->query->get('datetime');
        $courseDate = \DateTime::createFromFormat('d/m/Y', $courseDateTime)->setTime($courseHour, 0, 0);
        $course = $courseRepository->findOneBy([
            'student' => $student,
            'instructor' => $instructor,
            'courseDate' => $courseDate,
        ]);

        if ($course instanceof Course and $card instanceof Card) {
            /** @var Card $card */
            $courseManager->removeCourse($card, $course);
            $em->remove($course);
            $em->flush();
        }
        $credit->setRestCourseNumber((int)($credit->getRestCourseNumber()+1));

        $em->flush();
        $session->set('card', $card);
        return $this->json([
            'message' => 'succes',
            'restCourseNumber' => $credit->getRestCourseNumber(),
            'courseNumber' => $credit->getCourseNumber(),

        ], Response::HTTP_OK);
    }
}
