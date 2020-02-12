<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class LocationController extends AbstractController
{
    /**
     * @Route("/admin/location", name="list_metting_location")
     */
    public function lisAction()
    {
        $em = $this->getDoctrine()->getManager();
        $location = $em->getRepository(Location::class)->findAll();
        return $this->render('location/index.html.twig', [
            'locations' => $location,
        ]);
    }

    /**
     * @Route("/edit/location/{id}", name="edit_metting_location")
     */
    public function editMettingLocationAction(Request $request, Location $location, TranslatorInterface $translator)
    {
        $form = $this->createForm(LocationType::class, $location);
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
     * @Route("/remove/location/{id}", name="remove_metting_location")
     */
    public function removeMettingLocationAction(Location $location, TranslatorInterface $translator)
    {
        if ($location instanceof Location) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($location);
            $em->flush();
            $this->addFlash('success', $translator->trans('form.save.success'));
        }

        return $this->redirectToRoute('location_list');
    }
}
