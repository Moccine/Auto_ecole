<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InstructorType;
use App\Form\StudentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * @Route("/admin//student/list", name="list_students")
     */
    public function listAction()
    {
        $students = $this->getDoctrine()->getRepository(User::class)->findByRole(User::ROLE_DRIVING_STUDENT);

        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }

    /**
     * @Route("/admin/student/add/", name="add_student")
     */

    public function addInstructorAction(Request $request)
    {
        $form = $this->createForm(StudentType::class, new User());
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
        }
        return $this->render('student/addStudent.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin/student/edit/{id}", name="edit_student")
     */

    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(StudentType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
        }
        return $this->render('student/addStudent.html.twig', [
            'form' => $form->createView(),
            'student' => $user,
        ]);
    }
}
