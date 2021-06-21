<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Echeance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Echeance controller.
 *
 * @Route("echeance")
 */
class EcheanceController extends Controller
{
    /**
     * Lists all echeance entities.
     *
     * @Route("/", name="echeance_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $echeances = $em->getRepository('AppBundle:Echeance')->findAll();

        return $this->render('echeance/index.html.twig', array(
            'echeances' => $echeances,
        ));
    }

    /**
     * Creates a new echeance entity.
     *
     * @Route("/new", name="echeance_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $echeance = new Echeance();
        $form = $this->createForm('AppBundle\Form\EcheanceType', $echeance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($echeance);
            $em->flush();

            return $this->redirectToRoute('echeance_show', array('id' => $echeance->getId()));
        }

        return $this->render('echeance/new.html.twig', array(
            'echeance' => $echeance,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a echeance entity.
     *
     * @Route("/{id}", name="echeance_show")
     * @Method("GET")
     */
    public function showAction(Echeance $echeance)
    {
        $deleteForm = $this->createDeleteForm($echeance);

        return $this->render('echeance/show.html.twig', array(
            'echeance' => $echeance,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing echeance entity.
     *
     * @Route("/{id}/edit", name="echeance_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Echeance $echeance)
    {
        $deleteForm = $this->createDeleteForm($echeance);
        $editForm = $this->createForm('AppBundle\Form\EcheanceType', $echeance);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('echeance_edit', array('id' => $echeance->getId()));
        }

        return $this->render('echeance/edit.html.twig', array(
            'echeance' => $echeance,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a echeance entity.
     *
     * @Route("/{id}", name="echeance_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Echeance $echeance)
    {
        $form = $this->createDeleteForm($echeance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($echeance);
            $em->flush();
        }

        return $this->redirectToRoute('echeance_index');
    }

    /**
     * Creates a form to delete a echeance entity.
     *
     * @param Echeance $echeance The echeance entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Echeance $echeance)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('echeance_delete', array('id' => $echeance->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
