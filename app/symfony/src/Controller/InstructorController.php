<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\MettingPoint;
use App\Entity\User;
use App\Form\InstructorHourType;
use App\Form\InstructorType;
use App\Form\MettingPointType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/instructor")
 */
class InstructorController extends AbstractController
{
    /**
     * @Route("/list", name="list_instructor")
     */
    public function index()
    {
        $instructors = $this->getDoctrine()->getRepository(User::class)->findByRole(User::ROLE_RIVING_INSTRUCTOR);

        return $this->render('instructor/index.html.twig', [
            'instructors' => $instructors,
        ]);
    }


    /**
     * @Route("/add/", name="add_instructor")
     */

    public function addInstructorAction(Request $request)
    {
        $form = $this->createForm(InstructorType::class, new User());
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
        }
        return $this->render('instructor/addInstructor.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/edit/{id}", name="edit_instructor")
     */

    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(InstructorType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
        }
        return $this->render('instructor/addInstructor.html.twig', [
            'form' => $form->createView(),
            'instructor' => $user,
        ]);
    }
}
