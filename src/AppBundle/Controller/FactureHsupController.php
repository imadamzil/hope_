<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FactureHsup;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Facturehsup controller.
 *
 * @Route("facturehsup")
 */
class FactureHsupController extends Controller
{
    /**
     * Lists all factureHsup entities.
     *
     * @Route("/", name="facturehsup_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $factureHsups = $em->getRepository('AppBundle:FactureHsup')->findAll();

        return $this->render('facturehsup/index.html.twig', array(
            'factureHsups' => $factureHsups,
        ));
    }

    /**
     * Creates a new factureHsup entity.
     *
     * @Route("/new", name="facturehsup_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $factureHsup = new Facturehsup();
        $form = $this->createForm('AppBundle\Form\FactureHsupType', $factureHsup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($factureHsup);
            $em->flush();

            return $this->redirectToRoute('facturehsup_show', array('id' => $factureHsup->getId()));
        }

        return $this->render('facturehsup/new.html.twig', array(
            'factureHsup' => $factureHsup,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a factureHsup entity.
     *
     * @Route("/{id}", name="facturehsup_show")
     * @Method("GET")
     */
    public function showAction(FactureHsup $factureHsup)
    {
        $deleteForm = $this->createDeleteForm($factureHsup);

        return $this->render('facturehsup/show.html.twig', array(
            'factureHsup' => $factureHsup,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing factureHsup entity.
     *
     * @Route("/{id}/edit", name="facturehsup_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FactureHsup $factureHsup)
    {
        $deleteForm = $this->createDeleteForm($factureHsup);
        $editForm = $this->createForm('AppBundle\Form\FactureHsupType', $factureHsup);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('facturehsup_edit', array('id' => $factureHsup->getId()));
        }

        return $this->render('facturehsup/edit.html.twig', array(
            'factureHsup' => $factureHsup,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a factureHsup entity.
     *
     * @Route("/{id}", name="facturehsup_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FactureHsup $factureHsup)
    {
        $form = $this->createDeleteForm($factureHsup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($factureHsup);
            $em->flush();
        }

        return $this->redirectToRoute('facturehsup_index');
    }

    /**
     * Creates a form to delete a factureHsup entity.
     *
     * @param FactureHsup $factureHsup The factureHsup entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FactureHsup $factureHsup)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('facturehsup_delete', array('id' => $factureHsup->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
