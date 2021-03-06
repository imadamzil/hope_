<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Consultant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Consultant controller.
 *
 * @Route("consultant")
 */
class ConsultantController extends Controller
{
    /**
     * Lists all consultant entities.
     *
     * @Route("/", name="consultant_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $consultants = $em->getRepository('AppBundle:Consultant')->findAll();
//        dump($consultants[0]->calculePoids());
        return $this->render('consultant/index.html.twig', array(
            'consultants' => $consultants,
        ));
    }

    /**
     * Lists all consultant entities.
     *
     * @Route("/update/poids", name="consultant_update_poids")
     * @Method("GET")
     */
    public function updateAction()
    {
        $em = $this->getDoctrine()->getManager();

        $consultants = $em->getRepository('AppBundle:Consultant')->findAll();
        foreach ($consultants as $consultant) {
            $consultant->setPoids($consultant->calculePoids());
            $em->persist($consultant);
            $em->flush();
        }
//        dump($consultants[0]->calculePoids());
        return $this->redirectToRoute('consultant_index');
    }
    /**
     * Creates a new facturefournisseur entity.
     *
     * @Route("/getData", name="consultant_data")
     * @Method({"GET", "POST"})
     */
    public function getDataAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $facturefournisseurs = $em->getRepository('AppBundle:Consultant')->findAll();

        foreach ($facturefournisseurs as $key=>$facturefournisseur) {

//            $array[$key][]= $facturefournisseur->getId();
            $array[$key][]= $facturefournisseur->getNom();
            $array[$key][]= $facturefournisseur->getEmail();
            $array[$key][]= $facturefournisseur->getAdresse();
            $array[$key][]= $facturefournisseur->getTel();
            $array[$key][]= $facturefournisseur->getType();
            $array[$key][]= $facturefournisseur->getRib();
            if ($facturefournisseur->getEcheance()){
                $array[$key][]= $facturefournisseur->getEcheance()->getNom();

            }else{
                $array[$key][]= null;
            }

          if ($facturefournisseur->getAutoVirement()){
              $array[$key][]='--';

          }else{
              if ($facturefournisseur->getAutoVirement() == 1){
                  $array[$key][]='<div class="text-center"><label class="text-center"><input id="gritter-light" value="'.$facturefournisseur->getAutoVirement().'" type="checkbox" checked="checked" data-id="'.$facturefournisseur->getId().'" class="ace ace-switch ace-switch-5 switch"> <span class="lbl middle"></span></label></div>';

              }else{
                  $array[$key][]='<div class="text-center"><label class="text-center"><input id="gritter-light" value="'.$facturefournisseur->getAutoVirement().'" type="checkbox" data-id="'.$facturefournisseur->getId().'" class="ace ace-switch ace-switch-5 switch"> <span class="lbl middle"></span></label></div>';

              }

          }

            $array[$key][]= $facturefournisseur->getAnciennte();
            $array[$key][]= $facturefournisseur->getPoids();
            $array[$key][]= $facturefournisseur->getPoids();



        }

        $response = json_encode(array('data' => $array));

        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));
    }
    /**
     * Creates a new consultant entity.
     *
     * @Route("/new", name="consultant_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $consultant = new Consultant();
        $form = $this->createForm('AppBundle\Form\ConsultantType', $consultant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $consultant->setPoids($consultant->calculePoids());
            $em->persist($consultant);
            $em->flush();

            return $this->redirectToRoute('consultant_show', array('id' => $consultant->getId()));
        }

        return $this->render('consultant/new.html.twig', array(
            'consultant' => $consultant,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a consultant entity.
     *
     * @Route("/{id}", name="consultant_show")
     * @Method("GET")
     */
    public function showAction(Consultant $consultant)
    {
        $deleteForm = $this->createDeleteForm($consultant);

        return $this->render('consultant/show.html.twig', array(
            'consultant' => $consultant,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing consultant entity.
     *
     * @Route("/{id}/edit", name="consultant_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Consultant $consultant)
    {
        $deleteForm = $this->createDeleteForm($consultant);
        $editForm = $this->createForm('AppBundle\Form\ConsultantType', $consultant);
        $editForm->handleRequest($request);
        $consultant->setPoids($consultant->calculePoids());

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('consultant_index');
        }

        return $this->render('consultant/edit.html.twig', array(
            'consultant' => $consultant,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a consultant entity.
     *
     * @Route("/{id}", name="consultant_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Consultant $consultant)
    {
        $form = $this->createDeleteForm($consultant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($consultant);
            $em->flush();
        }

        return $this->redirectToRoute('consultant_index');
    }

    /**
     * Deletes a consultant entity.
     *
     * @Route("/{id}/setAutoVirement", name="make_autovirement_true_or_false",options={"expose"=true}))
     * @Method({"GET", "POST"})
     */
    public function autovirementAction(Request $request, Consultant $consultant)
    {

        $consultant->setAutoVirement(!$consultant->getAutoVirement());
        $em = $this->getDoctrine()->getManager();
        $em->persist($consultant);
        $em->flush();


        return new JsonResponse('ok');
    }

    /**
     * Creates a form to delete a consultant entity.
     *
     * @param Consultant $consultant The consultant entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Consultant $consultant)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('consultant_delete', array('id' => $consultant->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
