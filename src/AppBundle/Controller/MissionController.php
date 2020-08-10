<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mission;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Mission controller.
 *
 * @Route("mission")
 */
class MissionController extends Controller
{
    /**
     * Lists all mission entities.
     *
     * @Route("/", name="mission_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $missions = $em->getRepository('AppBundle:Mission')->findAll();
        $missions_sans_contratF = $em->getRepository('AppBundle:Mission')->findBy([
            'contratFName' => null

        ]);
        $missions_sans_contratC = $em->getRepository('AppBundle:Mission')->findBy([
            'contratCName' => null

        ]);
        $missions_sans_BC = $em->getRepository('AppBundle:Mission')->findBy([
            'bcName' => null

        ]);
        $nb_sans_contratF = count($missions_sans_contratF);
        $nb_sans_contratC = count($missions_sans_contratC);
        $nb_sans_BC = count($missions_sans_BC);



        /* foreach ($missions as $mission) {

             // $time = new \DateTime('now');


             $time = new DateTime();

             $dateDebut = $mission->getDateDebut();

             $interval = $time->diff($dateDebut, false);

             $val = $interval->format('%d');

             if ($val >= 30) {

                 array_push($arr, $mission);
             }
             dump($interval, $interval->format('%d'));
             $elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');


         }*/


        // dump($missions);

        return $this->render('mission/index.html.twig', array(
            'missions' => $missions,
            'nb_F'=>$nb_sans_contratF,
            'nb_C'=>$nb_sans_contratC,
            'nb_BC'=>$nb_sans_BC,


        ));
    }

    /**
     * Creates a new mission entity.
     *
     * @Route("/new", name="mission_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $mission = new Mission();
        $form = $this->createForm('AppBundle\Form\MissionType', $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dump($request);
            // die();
            $em = $this->getDoctrine()->getManager();
            $em->persist($mission);
            $em->flush();

            return $this->redirectToRoute('mission_show', array('id' => $mission->getId()));
        }

        return $this->render('mission/new.html.twig', array(
            'mission' => $mission,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a mission entity.
     *
     * @Route("/{id}", name="mission_show")
     * @Method("GET")
     */
    public function showAction(Mission $mission)
    {
        $deleteForm = $this->createDeleteForm($mission);

        return $this->render('mission/show.html.twig', array(
            'mission' => $mission,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing mission entity.
     *
     * @Route("/{id}/edit", name="mission_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Mission $mission)
    {
        $deleteForm = $this->createDeleteForm($mission);
        $editForm = $this->createForm('AppBundle\Form\MissionType', $mission);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mission_edit', array('id' => $mission->getId()));
        }

        return $this->render('mission/edit.html.twig', array(
            'mission' => $mission,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing mission entity.
     *
     * @Route("/{id}/upload", name="mission_upload")
     * @Method({"GET", "POST"})
     */
    public function uploadAction(Request $request, Mission $mission)
    {
        $deleteForm = $this->createDeleteForm($mission);
        $editForm = $this->createForm('AppBundle\Form\MissionType', $mission);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mission_show', array('id' => $mission->getId()));
        }

        return $this->render('mission/upload.html.twig', array(
            'mission' => $mission,
            'form' => $editForm->createView(),

        ));
    }

    /**
     * Deletes a mission entity.
     *
     * @Route("/{id}", name="mission_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Mission $mission)
    {
        $form = $this->createDeleteForm($mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mission);
            $em->flush();
        }

        return $this->redirectToRoute('mission_index');
    }

    /**
     * Creates a form to delete a mission entity.
     *
     * @param Mission $mission The mission entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Mission $mission)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('mission_delete', array('id' => $mission->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
