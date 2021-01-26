<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Heuresup;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Heuresup controller.
 *
 * @Route("heuresup")
 */
class HeuresupController extends Controller
{
    /**
     * Lists all heuresup entities.
     *
     * @Route("/", name="heuresup_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $heuresups = $em->getRepository('AppBundle:Heuresup')->findAll();

        return $this->render('heuresup/index.html.twig', array(
            'heuresups' => $heuresups,
        ));
    }

    /**
     * Creates a new heuresup entity.
     *
     * @Route("/new", name="heuresup_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $heuresup = new Heuresup();
        $form = $this->createForm('AppBundle\Form\HeuresupType', $heuresup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($heuresup);
            $em->flush();

            return $this->redirectToRoute('heuresup_show', array('id' => $heuresup->getId()));
        }

        return $this->render('heuresup/new.html.twig', array(
            'heuresup' => $heuresup,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a heuresup entity.
     *
     * @Route("/{id}", name="heuresup_show")
     * @Method("GET")
     */
    public function showAction(Heuresup $heuresup)
    {
        $deleteForm = $this->createDeleteForm($heuresup);

        return $this->render('heuresup/show.html.twig', array(
            'heuresup' => $heuresup,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing heuresup entity.
     *
     * @Route("/{id}/edit", name="heuresup_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Heuresup $heuresup)
    {
        $deleteForm = $this->createDeleteForm($heuresup);
        $editForm = $this->createForm('AppBundle\Form\HeuresupType', $heuresup);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('heuresup_edit', array('id' => $heuresup->getId()));
        }

        return $this->render('heuresup/edit.html.twig', array(
            'heuresup' => $heuresup,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a heuresup entity.
     *
     * @Route("/{id}", name="heuresup_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Heuresup $heuresup)
    {
        $form = $this->createDeleteForm($heuresup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($heuresup);
            $em->flush();
        }

        return $this->redirectToRoute('heuresup_index');
    }

    /**
     * Creates a form to delete a heuresup entity.
     *
     * @param Heuresup $heuresup The heuresup entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Heuresup $heuresup)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('heuresup_delete', array('id' => $heuresup->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
