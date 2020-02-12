<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DriveController extends AbstractController
{
    /**
     * @Route("/admin/drive-to-code", name="drive_to_code", methods={"GET"})
     * @param Request $request
     */
    public function driveToCodeAction(Request $request)
    {
        return $this->render('pages/driveToCode.html.twig');
    }

    /**
     * @Route("/drive-to-code", name="training_code", methods={"GET"})
     * @param Request $request
     */
    public function tranningCodeAction()
    {
        return $this->render('pages/trainingCode.html.twig');
    }
}
