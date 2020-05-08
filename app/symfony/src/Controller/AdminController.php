<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminType;
use App\Form\StudentType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
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
     * @Route("/list/admin", name="admin_list")
     * @return Response
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findByRole(User::ROLE_SUPER_ADMIN);
        if(empty($users)){
            $this->session->getFlashBag()->add('error', 'Liste vide ');
        }

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/index", name="admin_index")
     */
    public function editUserAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findBy([

        ]);
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/add/admin", name="admin_add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addAction(Request $request, UserPasswordEncoderInterface $encoder
)
    {
        $user = new User();
        $form = $this->createForm(AdminType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {

            $password = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password)->setPlainPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            return $this->redirectToRoute('admin_list');
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/admin/{id}", name="admin_edit")
     * @param Request $request
     * @param User $admin
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, User $admin)
    {
        $form = $this->createForm(User::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            return $this->redirectToRoute('admin_list');
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/remove/admin/{id}", name="admin_remove")
     * @param Request $request
     * @param SessionInterface $session
     * @param User $admin
     * @return RedirectResponse
     */
    public function remove(Request $request, SessionInterface $session, User $admin)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $em->remove($admin);
            $em->flush();
            $session->getFlashBag()->add('notice', 'Profile updated');
            return $this->redirectToRoute('admin_list');
        } catch (\Exception $e) {
            return $this->redirectToRoute('admin_list');
        }
    }
}
