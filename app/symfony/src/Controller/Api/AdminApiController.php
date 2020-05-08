<?php


namespace App\Controller\Api;

use App\Entity\User;
use App\Form\activeUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminApiController
 * @package App\Controller\Api
 */
class AdminApiController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * CourseApiController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin/activate/user/{id}",name="admin_activate_user")
     * @param Request $request
     * @param User $user
     * @return string|JsonResponse
     */
    public function activateUser(Request $request, User $user)
    {
        try {
            $data = $request->request->get('active_user');
            $user->setActivated((bool)$data['activated'])
                ->setRole($data['role']);
            $this->em->flush();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
        return $this->json([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/admin/activate/form/user/{id}",name="admin_activate_form_user")
     * @param Request $request
     * @param User $user
     * @return string|JsonResponse
     */
    public function activateFormUser(Request $request, User $user)
    {
        $form = $this->createForm(activeUserType::class, $user);
        $templateForm = $this->renderView('admin/activateFormUse.html.twig', ['form' => $form->createView()]);

        return $this->json([
            'message' => 'success',
            'template' => $templateForm,
        ], Response::HTTP_OK);
    }
}
