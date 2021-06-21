<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Parametrage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Parametrage controller.
 *
 * @Route("parametrage")
 */
class ParametrageController extends Controller
{
    /**
     * Lists all parametrage entities.
     *
     * @Route("/", name="parametrage_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $parametrages = $em->getRepository('AppBundle:Parametrage')->findAll();

        return $this->render('parametrage/index.html.twig', array(
            'parametrages' => $parametrages,
        ));
    }

    /**
     * Creates a new parametrage entity.
     *
     * @Route("/new", name="parametrage_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $parametrage = new Parametrage();
        $form = $this->createForm('AppBundle\Form\ParametrageType', $parametrage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($parametrage);
            $em->flush();

            return $this->redirectToRoute('parametrage_show', array('id' => $parametrage->getId()));
        }

        return $this->render('parametrage/new.html.twig', array(
            'parametrage' => $parametrage,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a parametrage entity.
     *
     * @Route("/{id}", name="parametrage_show")
     * @Method("GET")
     */
    public function showAction(Parametrage $parametrage)
    {
        $deleteForm = $this->createDeleteForm($parametrage);

        return $this->render('parametrage/show.html.twig', array(
            'parametrage' => $parametrage,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing parametrage entity.
     *
     * @Route("/{id}/edit", name="parametrage_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Parametrage $parametrage)
    {
        $deleteForm = $this->createDeleteForm($parametrage);
        $editForm = $this->createForm('AppBundle\Form\ParametrageType', $parametrage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parametrage_edit', array('id' => $parametrage->getId()));
        }

        return $this->render('parametrage/edit.html.twig', array(
            'parametrage' => $parametrage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a parametrage entity.
     *
     * @Route("/{id}", name="parametrage_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Parametrage $parametrage)
    {
        $form = $this->createDeleteForm($parametrage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($parametrage);
            $em->flush();
        }

        return $this->redirectToRoute('parametrage_index');
    }

    /**
     * Creates a form to delete a parametrage entity.
     *
     * @param Parametrage $parametrage The parametrage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Parametrage $parametrage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parametrage_delete', array('id' => $parametrage->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
