<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facturefournisseur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Facturefournisseur controller.
 *
 * @Route("facturefournisseur")
 */
class FacturefournisseurController extends Controller
{
    /**
     * Lists all facturefournisseur entities.
     *
     * @Route("/", name="facturefournisseur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $facturefournisseurs = $em->getRepository('AppBundle:Facturefournisseur')->findAll();
        dump($facturefournisseurs);
        $facturefournisseurs_sans_facture = $em->getRepository('AppBundle:Facturefournisseur')->findBy([

            'documentName' => null
        ]);

        return $this->render('facturefournisseur/index.html.twig', array(
            'facturefournisseurs' => $facturefournisseurs,
            'facturefournisseurs_sans_facture' => $facturefournisseurs_sans_facture,
        ));
    }

    /**
     * Lists all facturefournisseur entities.
     *
     * @Route("/sans_facture", name="facturefournisseur_sans")
     * @Method("GET")
     */
    public function sansfactureAction()
    {
        $em = $this->getDoctrine()->getManager();


        $facturefournisseurs = $em->getRepository('AppBundle:Facturefournisseur')->findBy([

            'documentName' => null
        ]);

        return $this->render('facturefournisseur/sans_facture.html.twig', array(
            'facturefournisseurs' => $facturefournisseurs,

        ));
    }

    /**
     * Creates a new facturefournisseur entity.
     *
     * @Route("/new", name="facturefournisseur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $facturefournisseur = new Facturefournisseur();
        $form = $this->createForm('AppBundle\Form\FacturefournisseurType', $facturefournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($facturefournisseur);
            $em->flush();

            return $this->redirectToRoute('facturefournisseur_show', array('id' => $facturefournisseur->getId()));
        }

        return $this->render('facturefournisseur/new.html.twig', array(
            'facturefournisseur' => $facturefournisseur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a facturefournisseur entity.
     *
     * @Route("/{id}", name="facturefournisseur_show")
     * @Method("GET")
     */
    public function showAction(Facturefournisseur $facturefournisseur)
    {
        $deleteForm = $this->createDeleteForm($facturefournisseur);

        return $this->render('facturefournisseur/show.html.twig', array(
            'facturefournisseur' => $facturefournisseur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing facturefournisseur entity.
     *
     * @Route("/{id}/edit", name="facturefournisseur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Facturefournisseur $facturefournisseur)
    {
        $deleteForm = $this->createDeleteForm($facturefournisseur);
        $editForm = $this->createForm('AppBundle\Form\FacturefournisseurType', $facturefournisseur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('facturefournisseur_edit', array('id' => $facturefournisseur->getId()));
        }

        return $this->render('facturefournisseur/edit.html.twig', array(
            'facturefournisseur' => $facturefournisseur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing facturefournisseur entity.
     *
     * @Route("/{id}/add_facture", name="facturefournisseur_update")
     * @Method({"GET", "POST"})
     */
    public function updateAction(Request $request, Facturefournisseur $facturefournisseur)
    {
        $deleteForm = $this->createDeleteForm($facturefournisseur);
        $editForm = $this->createForm('AppBundle\Form\Facturefournisseur1Type', $facturefournisseur);
        $editForm->handleRequest($request);
        $facturefournisseur->setEtat('Payé');
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('facturefournisseur_index');
        }

        return $this->render('facturefournisseur/edit.html.twig', array(
            'facturefournisseur' => $facturefournisseur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a facturefournisseur entity.
     *
     * @Route("/{id}", name="facturefournisseur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Facturefournisseur $facturefournisseur)
    {
        $form = $this->createDeleteForm($facturefournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($facturefournisseur);
            $em->flush();
        }

        return $this->redirectToRoute('facturefournisseur_index');
    }

    /**
     * Creates a form to delete a facturefournisseur entity.
     *
     * @param Facturefournisseur $facturefournisseur The facturefournisseur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Facturefournisseur $facturefournisseur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('facturefournisseur_delete', array('id' => $facturefournisseur->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
