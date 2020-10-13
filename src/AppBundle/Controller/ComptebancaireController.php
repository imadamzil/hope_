<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comptebancaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Comptebancaire controller.
 *
 * @Route("comptebancaire")
 */
class ComptebancaireController extends Controller
{
    /**
     * Lists all comptebancaire entities.
     *
     * @Route("/", name="comptebancaire_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comptebancaires = $em->getRepository('AppBundle:Comptebancaire')->findAll();

        return $this->render('comptebancaire/index.html.twig', array(
            'comptebancaires' => $comptebancaires,
        ));
    }

    /**
     * Creates a new comptebancaire entity.
     *
     * @Route("/new", name="comptebancaire_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $comptebancaire = new Comptebancaire();
        $form = $this->createForm('AppBundle\Form\ComptebancaireType', $comptebancaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comptebancaire);
            $em->flush();

            return $this->redirectToRoute('comptebancaire_show', array('id' => $comptebancaire->getId()));
        }

        return $this->render('comptebancaire/new.html.twig', array(
            'comptebancaire' => $comptebancaire,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a comptebancaire entity.
     *
     * @Route("/{id}", name="comptebancaire_show")
     * @Method("GET")
     */
    public function showAction(Comptebancaire $comptebancaire)
    {
        $deleteForm = $this->createDeleteForm($comptebancaire);

        return $this->render('comptebancaire/show.html.twig', array(
            'comptebancaire' => $comptebancaire,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing comptebancaire entity.
     *
     * @Route("/{id}/edit", name="comptebancaire_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Comptebancaire $comptebancaire)
    {
        $deleteForm = $this->createDeleteForm($comptebancaire);
        $editForm = $this->createForm('AppBundle\Form\ComptebancaireType', $comptebancaire);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comptebancaire_edit', array('id' => $comptebancaire->getId()));
        }

        return $this->render('comptebancaire/edit.html.twig', array(
            'comptebancaire' => $comptebancaire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a comptebancaire entity.
     *
     * @Route("/{id}", name="comptebancaire_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Comptebancaire $comptebancaire)
    {
        $form = $this->createDeleteForm($comptebancaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comptebancaire);
            $em->flush();
        }

        return $this->redirectToRoute('comptebancaire_index');
    }

    /**
     * Creates a form to delete a comptebancaire entity.
     *
     * @param Comptebancaire $comptebancaire The comptebancaire entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Comptebancaire $comptebancaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comptebancaire_delete', array('id' => $comptebancaire->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
