<?php


namespace App\Controller;

use App\Entity\Card;
use App\Entity\Course;
use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProfileController extends AbstractController
{
    /**
     * @param Request $request
     * @return RedirectResponse|Response|null
     * @Route("admin/profile/edit", name="admin_profile_edit")
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        return $this->render('Admin/Profile/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
