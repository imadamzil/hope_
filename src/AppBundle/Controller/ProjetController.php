<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facture;
use AppBundle\Entity\LigneFacture;
use AppBundle\Entity\Projet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Projet controller.
 *
 * @Route("projet")
 */
class ProjetController extends Controller
{
    /**
     * Lists all projet entities.
     *
     * @Route("/", name="projet_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projets = $em->getRepository('AppBundle:Projet')->findAll();

        return $this->render('projet/index.html.twig', array(
            'projets' => $projets,
        ));
    }

    /**
     * Creates a new projet entity.
     *
     * @Route("/new", name="projet_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $projet = new Projet();
        $form = $this->createForm('AppBundle\Form\ProjetType', $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projet);


            foreach ($projet->getProjetconsultants() as $projetconsultant) {

                $projetconsultant->setProjet($projet);
            }
            $em->flush();
            return $this->redirectToRoute('projet_show', array('id' => $projet->getId()));
        }

        return $this->render('projet/new.html.twig', array(
            'projet' => $projet,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}", name="projet_show")
     * @Method("GET")
     */
    public function showAction(Projet $projet)
    {
        $deleteForm = $this->createDeleteForm($projet);

        //dump($projet);

        return $this->render('projet/show.html.twig', array(
            'projet' => $projet,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}/facturation", name="projet_facturation")
     * @Method({"GET", "POST"})
     */
    public function FacturationAction(Projet $projet, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $facture = new Facture();
        $facture->setEtat('non payé');
        $facture->setClient($projet->getClient());

        if ($projet->getProjetconsultants()) {

            foreach ($projet->getProjetconsultants() as $projetconsultant) {
                $ligne = new LigneFacture();
                $ligne->setFacture($facture);
                $ligne->setProjetconsultant($projetconsultant);
                $em->persist($ligne);
                $em->flush();
                $facture->addLigne($ligne);

            }
        }

        $form = $this->createForm('AppBundle\Form\Facture2Type', $facture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $totalHt = null;
            $facture->setProjet($projet);

            foreach ($facture->getLignes() as $ligne) {

                $totalHt += $ligne->getNbjour() * $ligne->getProjetconsultant()->getVente();

            }
            $taxe = $totalHt * 0.2;

            // num facture
            $nb = count($em->getRepository('AppBundle:Facture')->findBy(array(

                'mois' => $facture->getMois(),
                'year' => $facture->getYear(),
            )));
            $facture->setNumero('H3K-' . substr($facture->getYear(), -2) . '-' . str_pad($facture->getMois(), 2, '0', STR_PAD_LEFT) . '-' . str_pad($nb, 3, '0', STR_PAD_LEFT));

            dump($facture, $facture->getLignes(), $totalHt);

            
            die();

        }
//        dump($projet,$facture);

        return $this->render('projet/facturation.html.twig', array(
            'projet' => $projet,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}/end", name="projet_end")
     * @Method("GET")
     */
    public function endAction(Projet $projet)
    {
        $projet->setStatut('Terminé');
        $em = $this->getDoctrine()->getManager();
        $em->persist($projet);
        $em->flush();


        return $this->redirectToRoute('projet_show', array('id' => $projet->getId()));

    }

    /**
     * Displays a form to edit an existing projet entity.
     *
     * @Route("/{id}/edit", name="projet_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Projet $projet)
    {
        $deleteForm = $this->createDeleteForm($projet);
        $editForm = $this->createForm('AppBundle\Form\ProjetType', $projet);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('projet_edit', array('id' => $projet->getId()));
        }

        return $this->render('projet/edit.html.twig', array(
            'projet' => $projet,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a projet entity.
     *
     * @Route("/{id}", name="projet_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Projet $projet)
    {
        $form = $this->createDeleteForm($projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projet);
            $em->flush();
        }

        return $this->redirectToRoute('projet_index');
    }

    /**
     * Creates a form to delete a projet entity.
     *
     * @param Projet $projet The projet entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Projet $projet)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('projet_delete', array('id' => $projet->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
