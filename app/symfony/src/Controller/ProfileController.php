<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\User;
use App\Form\ProfileType;
use App\Form\UserType;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\Form\Factory\FormFactory;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
    use ControllerTrait;

    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response|null
     * @Route("profile/edit", name="profile_edit")
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
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
