<?php


namespace App\Controller\Api;

use App\Entity\MettingPoint;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InstructorApiController
 * @package App\Controller\Api
 */
class InstructorApiController extends AbstractController
{
    /**
     * @Route("/search/instructor/metting-point/{id}", name="search_instructor")
     * @param MettingPoint $mettingPoint
     * @return JsonResponse
     */
    public function searchInstructor(MettingPoint $mettingPoint)
    {
        $instructors = $this->getDoctrine()->getRepository(User::class)->findByRole(User::ROLE_RIVING_INSTRUCTOR);

        return $this->json([
            'success' => 'success',
            'instructor' => $instructors,
        ], Response::HTTP_OK);
    }
}