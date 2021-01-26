<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Projetconsultant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Projetconsultant controller.
 *
 * @Route("projetconsultant")
 */
class ProjetconsultantController extends Controller
{
    /**
     * Lists all projetconsultant entities.
     *
     * @Route("/", name="projetconsultant_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projetconsultants = $em->getRepository('AppBundle:Projetconsultant')->findAll();

        return $this->render('projetconsultant/index.html.twig', array(
            'projetconsultants' => $projetconsultants,
        ));
    }

    /**
     * Creates a new projetconsultant entity.
     *
     * @Route("/new", name="projetconsultant_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $projetconsultant = new Projetconsultant();
        $form = $this->createForm('AppBundle\Form\ProjetconsultantType', $projetconsultant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projetconsultant);
            $em->flush();

            return $this->redirectToRoute('projetconsultant_show', array('id' => $projetconsultant->getId()));
        }

        return $this->render('projetconsultant/new.html.twig', array(
            'projetconsultant' => $projetconsultant,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a projetconsultant entity.
     *
     * @Route("/{id}", name="projetconsultant_show")
     * @Method("GET")
     */
    public function showAction(Projetconsultant $projetconsultant)
    {
        $deleteForm = $this->createDeleteForm($projetconsultant);

        return $this->render('projetconsultant/show.html.twig', array(
            'projetconsultant' => $projetconsultant,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing projetconsultant entity.
     *
     * @Route("/{id}/edit", name="projetconsultant_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Projetconsultant $projetconsultant)
    {
        $deleteForm = $this->createDeleteForm($projetconsultant);
        $editForm = $this->createForm('AppBundle\Form\ProjetconsultantType', $projetconsultant);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('projetconsultant_edit', array('id' => $projetconsultant->getId()));
        }

        return $this->render('projetconsultant/edit.html.twig', array(
            'projetconsultant' => $projetconsultant,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a projetconsultant entity.
     *
     * @Route("/{id}", name="projetconsultant_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Projetconsultant $projetconsultant)
    {
        $form = $this->createDeleteForm($projetconsultant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projetconsultant);
            $em->flush();
        }

        return $this->redirectToRoute('projetconsultant_index');
    }

    /**
     * Creates a form to delete a projetconsultant entity.
     *
     * @param Projetconsultant $projetconsultant The projetconsultant entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Projetconsultant $projetconsultant)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('projetconsultant_delete', array('id' => $projetconsultant->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


}
