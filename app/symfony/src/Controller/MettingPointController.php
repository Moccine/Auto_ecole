<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\MettingPoint;
use App\Form\LocationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MettingPointController extends AbstractController
{
    /**
     * @Route("/metting/point", name="metting_point_list")
     */
    public function index()
    {
        $mettingPoints = $this->getDoctrine()->getRepository(MettingPoint::class)->findAll();
        return $this->render('metting_point/index.html.twig', [
            'mettingPoints' => $mettingPoints,
        ]);
    }
    /**
     * @Route("/admin/add/metting-point", name="add_metting_point")
     */
    public function createMettingLocationAction(Request $request, TranslatorInterface $translator)
    {
        $form = $this->createForm(LocationType::class, new MettingPoint());
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash('success', $translator->trans('form.save.success'));

            return $this->redirectToRoute('list_metting_location');
        }

        return $this->render('location/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/remove/metting-pint/{id}", name="remove_metting_point")
     */
    public function removeMettingLocationAction(MettingPoint $mettingPoint, TranslatorInterface $translator)
    {
        if ($mettingPoint instanceof MettingPoint) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mettingPoint);
            $em->flush();
            $this->addFlash('success', $translator->trans('form.save.success'));
        }

        return $this->redirectToRoute('metting_point_list');
    }

    /**
     * @Route("/edit/metting-point/{id}", name="edit_metting_point")
     */
    public function editMettingLocationAction(Request $request, MettingPoint $mettingPoint, TranslatorInterface $translator)
    {
        $form = $this->createForm(LocationType::class, $mettingPoint);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash('success', $translator->trans('form.save.success'));

            return $this->redirectToRoute('metting_point_list');
        }

        return $this->render('location/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
