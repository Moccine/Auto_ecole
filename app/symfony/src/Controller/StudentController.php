<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
