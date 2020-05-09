<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InstructorType;
use App\Form\StudentEditType;
use App\Form\StudentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StudentController extends AbstractController
{
    /** @var SessionInterface */
    private $session;

    /**
     * AdminController constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

    }
    /**
     * @Route("/admin/student/list", name="list_students")
     */
    public function listAction()
    {
        $students = $this->getDoctrine()->getRepository(User::class)->findByRole(User::ROLE_DRIVING_STUDENT);
        if(empty($students)){
            $this->session->getFlashBag()->add('error', 'Liste vide ');
        }
        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }

    /**
     * @Route("/admin/student/add/", name="add_student")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     */

    public function addInstructorAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(StudentType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            if(array_key_exists('plainPassword', $request->request->get('student'))){
                $password = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password)->setPlainPassword($password);
                $user->setEnabled(true)->setRole(User::ROLE_DRIVING_STUDENT);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->session->getFlashBag()->add('success', 'Votre utilisateur a été crée avec succès ');

            return  $this->redirectToRoute('list_students');

        }
        return $this->render('student/addStudent.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/student/edit/{id}", name="edit_student")
     * @param Request $request
     * @param User $user
     * @return RedirectResponse|Response
     */

    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(StudentEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->session->getFlashBag()->add('success', 'Votre utilisateur mis à jour ');

            return  $this->redirectToRoute('list_students');
        }
        return $this->render('student/editStudent.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
