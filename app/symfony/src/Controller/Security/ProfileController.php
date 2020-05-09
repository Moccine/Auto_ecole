<?php

namespace App\Controller\Security;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\User;
use App\Form\ProfileType;
use App\Form\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param Request $request
     * @return RedirectResponse|Response|null
     * @Route("profile/edit", name="profile_edit")
     */
    public function editAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->hasRole('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('admin_edit', ['id' => $user->getId()]);
        }
        $courses = $this->getDoctrine()->getRepository(Course::class)->findBy([
            'student' => $this->getUser(),
            'status' => Card::PENDING
        ]);
        $items = [];
        /** @var Course $course */
        foreach ($courses as $course) {
            $mettingPoint = $course->getMettingPoint()->first();

            array_push(
                $items,
                [
                    'mettingPoint' => $mettingPoint,
                    'course' => $course

                ]
            );
        }
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        return $this->render('Profile/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
