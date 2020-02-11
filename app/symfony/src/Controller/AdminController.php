<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/index", name="admin_index")
     */
    public function indexAction()
    {
       $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/index", name="admin_index")
     */
    public function editUserAcction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findBy([

        ]);
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

}
