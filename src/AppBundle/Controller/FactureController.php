<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facture;
use AppBundle\Entity\Mission;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Facture controller.
 *
 * @Route("facture")
 */
class FactureController extends Controller
{
    /**
     * Lists all facture entities.
     *
     * @Route("/", name="facture_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $factures = $em->getRepository('AppBundle:Facture')->findAll();
        dump($factures);
        return $this->render('facture/index.html.twig', array(
            'factures' => $factures,
        ));
    }

    /**
     * Creates a new facture entity.
     *
     * @Route("/new", name="facture_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $facture = new Facture();
        $form = $this->createForm('AppBundle\Form\FactureType', $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            $em->persist($facture);
            $em->flush();

            return $this->redirectToRoute('facture_show', array('id' => $facture->getId()));
        }

        return $this->render('facture/new.html.twig', array(
            'facture' => $facture,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing facture entity.
     *
     * @Route("/{id}/facture", name="facture_mission")
     * @Method({"GET", "POST"})
     */
    public function newfromMissionAction(Request $request, Mission $mission)
    {
        $facture = new Facture();
        $facture->setBcclient($mission->getBcclient());
        $facture->setClient($mission->getClient());
        $facture->setMission($mission);

        $prixAchatHT = $mission->getPrixAchat();
        $prixVenteHT = $mission->getPrixVente();
        $facture->setConsultant($mission->getConsultant());
        $form = $this->createForm('AppBundle\Form\FactureType', $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($facture);
            $em->flush();
            $facture = $em->getRepository('AppBundle:Facture')->find($facture->getId());

            $facture->setTotalHT($prixVenteHT * $facture->getNbjour());
            $em->persist($facture);
            $em->flush();
            return $this->redirectToRoute('facture_show', array('id' => $facture->getId()));
        }

        return $this->render('facture/facture_mission.html.twig', array(
            'facture' => $facture,
            'mission'=>$mission,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{id}", name="facture_show")
     * @Method("GET")
     */
    public function showAction(Facture $facture)
    {
        $deleteForm = $this->createDeleteForm($facture);

        return $this->render('facture/show.html.twig', array(
            'facture' => $facture,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing facture entity.
     *
     * @Route("/{id}/edit", name="facture_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Facture $facture)
    {
        $deleteForm = $this->createDeleteForm($facture);
        $editForm = $this->createForm('AppBundle\Form\FactureType', $facture);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('facture_edit', array('id' => $facture->getId()));
        }

        return $this->render('facture/edit.html.twig', array(
            'facture' => $facture,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a facture entity.
     *
     * @Route("/{id}", name="facture_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Facture $facture)
    {
        $form = $this->createDeleteForm($facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($facture);
            $em->flush();
        }

        return $this->redirectToRoute('facture_index');
    }

    /**
     * Creates a form to delete a facture entity.
     *
     * @param Facture $facture The facture entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Facture $facture)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('facture_delete', array('id' => $facture->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     *
     * @Route("/test", name="route_to_retrieve_mission",options={"expose"=true})
     ** @Method({"GET", "POST"})
     */
    public function getNiveau(Request $request)
    {
        $Id = $request->get('idMission');
        $year = $request->get('year');
        /*  $Id = 6;
          $year = 2020;*/
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('AppBundle:Mission')->find($Id);
        $factures = $em->getRepository('AppBundle:Facture')->findBy(

            [
                'mission' => $mission,
                'year' => $year
            ]


        );
        if ($mission->getType()) {
            $type = $mission->getType();
        } else {

            $type = null;
        }


        if ($factures != null) {
            foreach ($factures as $facture) {

                $output[] = array($facture->getMois());
            }

            $response = json_encode(array('data' => $type, 'mois' => $output));
        }else {
            $response = json_encode(array('data' => $type, 'mois' => null));
        }





        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));

    }
}
