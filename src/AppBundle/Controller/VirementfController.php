<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Virementf;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Virementf controller.
 *
 * @Route("virementf")
 */
class VirementfController extends Controller
{
    /**
     * Lists all virementf entities.
     *
     * @Route("/", name="virementf_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $virementfs = $em->getRepository('AppBundle:Virementf')->findAll();

        $arr = [];


        $query = $em->createQuery('
SELECT p,sum(p.achat) as total,c FROM AppBundle:Virement p 
JOIN AppBundle:Bcfournisseur c 
WHERE p.bcfournisseur = c.id
        
GROUP BY c.fournisseur
        ')->execute();

        dump($query);
        return $this->render('virementf/index.html.twig', array(
            'virementfs' => $virementfs,
        ));
    }

    /**
     * Creates a new virementf entity.
     *
     * @Route("/new", name="virementf_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $virementf = new Virementf();
        $form = $this->createForm('AppBundle\Form\VirementfType', $virementf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($virementf);
            $em->flush();

            return $this->redirectToRoute('virementf_show', array('id' => $virementf->getId()));
        }

        return $this->render('virementf/new.html.twig', array(
            'virementf' => $virementf,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a virementf entity.
     *
     * @Route("/{id}", name="virementf_show",options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(Request $request,Virementf $virementf)
    {
        $deleteForm = $this->createDeleteForm($virementf);
        $em = $this->getDoctrine()->getManager();
        $arr = [];

        /* $virements = $virementf->getVirements();

         foreach ($virements as $virement) {

             $arr[] = $virement->getId();
         }
                     $query = $em->createQuery('
             SELECT p as fournisseur,sum(p.achat) as total FROM AppBundle:Virement p
             JOIN AppBundle:Bcfournisseur c
             WHERE p.bcfournisseur = c.id AND p.id IN (:ids)

             GROUP BY c.fournisseur
                     ')->setParameter('ids', $arr)->getResult(Query::HYDRATE_OBJECT);

         var_dump($query);*/
        $details = $em->getRepository('AppBundle:Detailvirement')->findBy(
            array('virementf' => $virementf),
            array('priorite' => 'ASC')
        );


        $query = $em->createQuery('
SELECT sum(p.total) as total FROM AppBundle:Detailvirement p 
WHERE p.virementf = :id
        ')->setParameter('id', $virementf->getId())->getSingleResult();
        dump($details, $query);
        return $this->render('virementf/show.html.twig', array(
            'virementf' => $virementf,
            'details' => $details,
            'total' => $query,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a virementf entity.
     *
     * @Route("/{id}/print", name="virementf_print",options={"expose"=true})
     * @Method("GET")
     */
    public function printAction(Request $request,Virementf $virementf)
    {
        $deleteForm = $this->createDeleteForm($virementf);
        $em = $this->getDoctrine()->getManager();
        $arr = [];

        /* $virements = $virementf->getVirements();

         foreach ($virements as $virement) {

             $arr[] = $virement->getId();
         }
                     $query = $em->createQuery('
             SELECT p as fournisseur,sum(p.achat) as total FROM AppBundle:Virement p
             JOIN AppBundle:Bcfournisseur c
             WHERE p.bcfournisseur = c.id AND p.id IN (:ids)

             GROUP BY c.fournisseur
                     ')->setParameter('ids', $arr)->getResult(Query::HYDRATE_OBJECT);

         var_dump($query);*/
        $details = $em->getRepository('AppBundle:Detailvirement')->findBy(
            array('virementf' => $virementf),
            array('priorite' => 'ASC')
        );


        $query = $em->createQuery('
SELECT sum(p.total) as total FROM AppBundle:Detailvirement p 
WHERE p.virementf = :id
        ')->setParameter('id', $virementf->getId())->getSingleResult();
        dump($details, $query);
        return $this->render('virementf/print.html.twig', array(
            'virementf' => $virementf,
            'details' => $details,
            'total' => $query,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing virementf entity.
     *
     * @Route("/{id}/edit", name="virementf_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Virementf $virementf)
    {
        $deleteForm = $this->createDeleteForm($virementf);
        $editForm = $this->createForm('AppBundle\Form\VirementfType', $virementf);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('virementf_edit', array('id' => $virementf->getId()));
        }

        return $this->render('virementf/edit.html.twig', array(
            'virementf' => $virementf,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a virementf entity.
     *
     * @Route("/{id}", name="virementf_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Virementf $virementf)
    {
        $form = $this->createDeleteForm($virementf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($virementf);
            $em->flush();
        }

        return $this->redirectToRoute('virementf_index');
    }

    /**
     * Creates a form to delete a virementf entity.
     *
     * @param Virementf $virementf The virementf entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Virementf $virementf)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('virementf_delete', array('id' => $virementf->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
