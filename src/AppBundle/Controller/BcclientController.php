<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bcclient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Bcclient controller.
 *
 * @Route("bcclient")
 */
class BcclientController extends Controller
{
    /**
     * Lists all bcclient entities.
     *
     * @Route("/", name="bcclient_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT p
    FROM AppBundle:Bcclient p
    WHERE p.nbJrsR < :nbJrsR'
        )->setParameter('nbJrsR', 30);

        $alerts = $query->getResult();
        $count = count($query->getResult());

        $bcclients = $em->getRepository('AppBundle:Bcclient')->findAll();
dump($bcclients,$alerts);
        return $this->render('bcclient/index.html.twig', array(
            'bcclients' => $bcclients,
            'count'=>$count,

        ));
    }
 /**
     * Lists all bcclient entities.
     *
     * @Route("/bcclient_alerts", name="bcclient_alert")
     * @Method("GET")
     */
    public function alertAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT p
    FROM AppBundle:Bcclient p
    WHERE p.nbJrsR < :nbJrsR'
        )->setParameter('nbJrsR', 30);

        $bcalerts = $query->getResult();
        $count = count($query->getResult());


        return $this->render('bcclient/bcalert.html.twig', array(
            'bcclients' => $bcalerts,


        ));
    }

    /**
     * Creates a new bcclient entity.
     *
     * @Route("/new", name="bcclient_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $bcclient = new Bcclient();
        $form = $this->createForm('AppBundle\Form\BcclientType', $bcclient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bcclient);
            $em->flush();

            return $this->redirectToRoute('bcclient_show', array('id' => $bcclient->getId()));
        }

        return $this->render('bcclient/new.html.twig', array(
            'bcclient' => $bcclient,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a bcclient entity.
     *
     * @Route("/{id}", name="bcclient_show")
     * @Method("GET")
     */
    public function showAction(Bcclient $bcclient)
    {
        $deleteForm = $this->createDeleteForm($bcclient);

        return $this->render('bcclient/show.html.twig', array(
            'bcclient' => $bcclient,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing bcclient entity.
     *
     * @Route("/{id}/edit", name="bcclient_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Bcclient $bcclient)
    {
        $deleteForm = $this->createDeleteForm($bcclient);
        $editForm = $this->createForm('AppBundle\Form\BcclientType', $bcclient);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bcclient_edit', array('id' => $bcclient->getId()));
        }

        return $this->render('bcclient/edit.html.twig', array(
            'bcclient' => $bcclient,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a bcclient entity.
     *
     * @Route("/{id}", name="bcclient_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Bcclient $bcclient)
    {
        $form = $this->createDeleteForm($bcclient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bcclient);
            $em->flush();
        }

        return $this->redirectToRoute('bcclient_index');
    }

    /**
     * Creates a form to delete a bcclient entity.
     *
     * @param Bcclient $bcclient The bcclient entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Bcclient $bcclient)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bcclient_delete', array('id' => $bcclient->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
