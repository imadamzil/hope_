<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Detailvirement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Detailvirement controller.
 *
 * @Route("detailvirement")
 */
class DetailvirementController extends Controller
{
    /**
     * Lists all detailvirement entities.
     *
     * @Route("/", name="detailvirement_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $detailvirements = $em->getRepository('AppBundle:Detailvirement')->findAll();

        return $this->render('detailvirement/index.html.twig', array(
            'detailvirements' => $detailvirements,
        ));
    }

    /**
     * Creates a new detailvirement entity.
     *
     * @Route("/new", name="detailvirement_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $detailvirement = new Detailvirement();
        $form = $this->createForm('AppBundle\Form\DetailvirementType', $detailvirement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($detailvirement);
            $em->flush();

            return $this->redirectToRoute('detailvirement_show', array('id' => $detailvirement->getId()));
        }

        return $this->render('detailvirement/new.html.twig', array(
            'detailvirement' => $detailvirement,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a detailvirement entity.
     *
     * @Route("/{id}", name="detailvirement_show")
     * @Method("GET")
     */
    public function showAction(Detailvirement $detailvirement)
    {
        $deleteForm = $this->createDeleteForm($detailvirement);

        return $this->render('detailvirement/show.html.twig', array(
            'detailvirement' => $detailvirement,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing detailvirement entity.
     *
     * @Route("/{id}/edit", name="detailvirement_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Detailvirement $detailvirement)
    {
        $deleteForm = $this->createDeleteForm($detailvirement);
        $editForm = $this->createForm('AppBundle\Form\DetailvirementType', $detailvirement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('detailvirement_edit', array('id' => $detailvirement->getId()));
        }

        return $this->render('detailvirement/edit.html.twig', array(
            'detailvirement' => $detailvirement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a detailvirement entity.
     *
     * @Route("/{id}", name="detailvirement_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Detailvirement $detailvirement)
    {
        $form = $this->createDeleteForm($detailvirement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($detailvirement);
            $em->flush();
        }

        return $this->redirectToRoute('detailvirement_index');
    }

    /**
     * Creates a form to delete a detailvirement entity.
     *
     * @param Detailvirement $detailvirement The detailvirement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Detailvirement $detailvirement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('detailvirement_delete', array('id' => $detailvirement->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
