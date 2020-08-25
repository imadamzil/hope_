<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bcfournisseur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Bcfournisseur controller.
 *
 * @Route("bcfournisseur")
 */
class BcfournisseurController extends Controller
{
    /**
     * Lists all bcfournisseur entities.
     *
     * @Route("/", name="bcfournisseur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bcfournisseurs = $em->getRepository('AppBundle:Bcfournisseur')->findAll();
        dump($bcfournisseurs);
        return $this->render('bcfournisseur/index.html.twig', array(
            'bcfournisseurs' => $bcfournisseurs,
        ));
    }

    /**
     * Creates a new bcfournisseur entity.
     *
     * @Route("/new", name="bcfournisseur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $bcfournisseur = new Bcfournisseur();
        $form = $this->createForm('AppBundle\Form\BcfournisseurType', $bcfournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bcfournisseur);
            $em->flush();

            return $this->redirectToRoute('bcfournisseur_show', array('id' => $bcfournisseur->getId()));
        }

        return $this->render('bcfournisseur/new.html.twig', array(
            'bcfournisseur' => $bcfournisseur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a bcfournisseur entity.
     *
     * @Route("/{id}", name="bcfournisseur_show")
     * @Method("GET")
     */
    public function showAction(Bcfournisseur $bcfournisseur)
    {
        $deleteForm = $this->createDeleteForm($bcfournisseur);

        return $this->render('bcfournisseur/show.html.twig', array(
            'bcfournisseur' => $bcfournisseur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing bcfournisseur entity.
     *
     * @Route("/{id}/edit", name="bcfournisseur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Bcfournisseur $bcfournisseur)
    {
        $deleteForm = $this->createDeleteForm($bcfournisseur);
        $editForm = $this->createForm('AppBundle\Form\BcfournisseurType', $bcfournisseur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bcfournisseur_edit', array('id' => $bcfournisseur->getId()));
        }

        return $this->render('bcfournisseur/edit.html.twig', array(
            'bcfournisseur' => $bcfournisseur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a bcfournisseur entity.
     *
     * @Route("/{id}", name="bcfournisseur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Bcfournisseur $bcfournisseur)
    {
        $form = $this->createDeleteForm($bcfournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bcfournisseur);
            $em->flush();
        }

        return $this->redirectToRoute('bcfournisseur_index');
    }

    /**
     * Creates a form to delete a bcfournisseur entity.
     *
     * @param Bcfournisseur $bcfournisseur The bcfournisseur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Bcfournisseur $bcfournisseur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bcfournisseur_delete', array('id' => $bcfournisseur->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
