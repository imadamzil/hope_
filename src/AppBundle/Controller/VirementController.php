<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Virement;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;


/**
 * Virement controller.
 *
 * @Route("virement")
 */
class VirementController extends Controller
{
    /**
     * Lists all virement entities.
     *
     * @Route("/", name="virement_index",options={"expose"=true}))
     * @Method("GET")
     */
    public function indexAction()
    {
        // export excel



        $em = $this->getDoctrine()->getManager();

        $virements = $em->getRepository('AppBundle:Virement')->findAll();
        dump($virements);
        return $this->render('virement/index.html.twig', array(
            'virements' => $virements,
        ));
    }

    /**
     * Creates a new virement entity.
     *
     * @Route("/new", name="virement_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $virement = new Virement();
        $virement->setEtat('en attente');
        $form = $this->createForm('AppBundle\Form\VirementType', $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $virement->setEtat('en attente');
            $em->persist($virement);
            $em->flush();

            return $this->redirectToRoute('virement_show', array('id' => $virement->getId()));
        }

        return $this->render('virement/new.html.twig', array(
            'virement' => $virement,
            'form' => $form->createView(),
        ));
    }
    /**
     *
     * @Route("/tests", name="route_to_retrieve_bc",options={"expose"=true})
     ** @Method({"GET", "POST"})
     */
    public function newFromBcfournisseurAction(Request $request)

    {


        $Ids = $request->get('idBCfournisseur');
        $em = $this->getDoctrine()->getManager();

        $bcfournisseurs = $em->getRepository('AppBundle:Bcfournisseur')->findBy(array('id' => $Ids));

//        $form = $this->createForm('AppBundle\Form\VirementType', $virement);
//        $form->handleRequest($request);
        foreach ($bcfournisseurs as $bc) {
            $virement = new Virement();
            $virement->setEtat('en attente');
            $virement->setBcfournisseur($bc);
            $virement->setConsultant($bc->getMission()->getConsultant());
            $virement->setAchat($bc->getAchatTTC());
            $virement->setDate( new \DateTime('now'));
            $em->persist($virement);

            $em->flush() ;

        }

        $response = json_encode(array('data' => $Ids,'bc'=>$bcfournisseurs));

        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));
       /* if ($form->isSubmitted() && $form->isValid()) {


        }

        return $this->render('virement/new.html.twig', array(
            'virement' => $virement,
            'form' => $form->createView(),
        ));*/
    }
 /**
     *
     * @Route("/validate_virement", name="route_to_validate_virement",options={"expose"=true})
     ** @Method({"GET", "POST"})
     */
    public function validateAction(Request $request)

    {


        $Ids = $request->get('idVirments');
        $em = $this->getDoctrine()->getManager();

        $virements = $em->getRepository('AppBundle:Virement')->findBy(array('id' => $Ids));

//        $form = $this->createForm('AppBundle\Form\VirementType', $virement);
//        $form->handleRequest($request);
        foreach ($virements as $virement) {

            $virement->setEtat('validé');


            $em->persist($virement);

            $em->flush() ;

        }

        $response = json_encode(array('data' => $Ids,'bc'=>"ok"));

        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));
       /* if ($form->isSubmitted() && $form->isValid()) {


        }

        return $this->render('virement/new.html.twig', array(
            'virement' => $virement,
            'form' => $form->createView(),
        ));*/
    }

    /**
     * Finds and displays a virement entity.
     *
     * @Route("/{id}", name="virement_show")
     * @Method("GET")
     */
    public function showAction(Virement $virement)
    {
        $deleteForm = $this->createDeleteForm($virement);

        return $this->render('virement/show.html.twig', array(
            'virement' => $virement,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing virement entity.
     *
     * @Route("/{id}/edit", name="virement_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Virement $virement)
    {
        $deleteForm = $this->createDeleteForm($virement);
        $editForm = $this->createForm('AppBundle\Form\VirementType', $virement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('virement_edit', array('id' => $virement->getId()));
        }

        return $this->render('virement/edit.html.twig', array(
            'virement' => $virement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a virement entity.
     *
     * @Route("/{id}", name="virement_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Virement $virement)
    {
        $form = $this->createDeleteForm($virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($virement);
            $em->flush();
        }

        return $this->redirectToRoute('virement_index');
    }

    /**
     * Creates a form to delete a virement entity.
     *
     * @param Virement $virement The virement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Virement $virement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('virement_delete', array('id' => $virement->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
