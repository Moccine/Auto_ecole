<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Course;
use App\Entity\User;
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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
    use ControllerTrait;
    /**
     * @var FactoryInterface
     */
    private $formFactory;

    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function editAction(Request $request)
    {
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

        /** @var SessionInterface $session */
        $session = $this->get('session');
        if (!empty($items)) {
            $session->set('card', $items);
        }

        $user = $this->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);


        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $userManager = $this->get('fos_user.user_manager');

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('fos_user_profile_edit');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return $this->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.' . $this->container->getParameter('fos_user.template.engine'),
            ['form' => $form->createView()]
        );
    }
}
