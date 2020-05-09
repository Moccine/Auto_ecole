<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\MettingPoint;
use App\Entity\User;
use App\Form\InstructorEditType;
use App\Form\InstructorHourType;
use App\Form\InstructorType;
use App\Form\MettingPointType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InstructorController extends AbstractController
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
     * @Route("/admin/instructor/list", name="list_instructor")
     */
    public function index()
    {
        $instructors = $this->getDoctrine()->getRepository(User::class)->findByRole(User::ROLE_RIVING_INSTRUCTOR);
        if(empty($instructors)){
            $this->session->getFlashBag()->add('error', 'Liste vide ');
        }
        return $this->render('instructor/index.html.twig', [
            'instructors' => $instructors,
        ]);
    }


    /**
     * @Route("/admin/instructor/add/", name="add_instructor")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */

    public function addInstructorAction(Request $request,  UserPasswordEncoderInterface $encoder)
    {
        $user =new User();
        $form = $this->createForm(InstructorType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            if(array_key_exists('plainPassword', $request->request->get('instructor'))){
                $password = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password)->setPlainPassword($password);
                $user->setEnabled(true)->setRole(User::ROLE_RIVING_INSTRUCTOR);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->session->getFlashBag()->add('success', 'Votre utilisateur a été crée avec succès ');

           return  $this->redirectToRoute('list_instructor');
        }
        return $this->render('instructor/addInstructor.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin/instructor/edit/{id}", name="edit_instructor")
     */

    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(InstructorEditType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
        }
        return $this->render('instructor/editInstructor.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
