<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Detailvirement;
use AppBundle\Entity\Virement;
use AppBundle\Entity\Virementf;
use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Query;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


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

        $em = $this->getDoctrine()->getManager();
        $virements = $em->getRepository('AppBundle:Virement')->findAll();

        $data = [

            'date' => new \DateTime(),

        ];

        $form = $this->createFormBuilder($data)
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'placeholder' => 'Date Début mission',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'required' => false,
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'date-timepicker1'],
            ])
            ->add('client', EntityType::class, array(
                'class' => 'AppBundle:Comptebancaire',
                'multiple' => false,
                'label' => 'Compte bancaire',
                'required' => false,
                'placeholder' => '--',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))
            ->getForm();
        dump($virements);

        return $this->render('virement/index.html.twig', array(
            'virements' => $virements,
            'form' => $form->createView()
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


        $Ids = $request->get('idfacturefournisseur');
//        $Ids = [3,2];
        $em = $this->getDoctrine()->getManager();

        $facturefournisseurs = $em->getRepository('AppBundle:Facturefournisseur')->findBy(array('id' => $Ids));

//        $form = $this->createForm('AppBundle\Form\VirementType', $virement);
//        $form->handleRequest($request);
        foreach ($facturefournisseurs as $bc) {
            $virement = new Virement();
            $virement->setEtat('en attente');

            $virement->setBcfournisseur($bc->getBcfournisseur());
            $virement->setFacturefournisseur($bc);
            if ($bc->getProjet()) {
                $virement->setConsultant($bc->getConsultant());

            } else {

                $virement->setConsultant($bc->getMission()->getConsultant());

            }
            $virement->setAchat($bc->getAchatTTC());
            $virement->setDate(new \DateTime('now'));
            $em->persist($virement);

            $em->flush();

        }

        $response = json_encode(array('data' => $Ids, 'bc' => $facturefournisseurs));

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


        foreach ($virements as $virement) {

            $virement->setEtat('validé');


            $em->persist($virement);

            $em->flush();

        }

        $response = json_encode(array('data' => $Ids, 'bc' => "ok"));

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
     * @Route("/add_virement", name="route_to_add_virement",options={"expose"=true})
     ** @Method({"GET", "POST"})
     */
    public function addVirementsAction(Request $request)

    {


        $Ids = $request->get('idVirments');
        $compte_id = $request->get('compte');
        $date = $request->get('date');
        $datego = DateTime::createFromFormat('Y-m-d H:i', $date);
        $datego ? $datego->format('Y-m-d H:i') : false;
//        $Ids = [22,24];

        $mois = $datego->format('m');
        $year = $datego->format('Y');
        $em = $this->getDoctrine()->getManager();
        $comptebancaire = $em->getRepository('AppBundle:Comptebancaire')->find($compte_id);

        $virements = $em->getRepository('AppBundle:Virement')->findBy(array('id' => $Ids));
        $nb_query = $em->createQuery('
        SELECT v from AppBundle:Virementf v 
        WHERE MONTH (v.date) = :mois and YEAR (v.date) = :year
        ')->setParameters(
            array(
                'mois' => $mois,
                'year' => $year
            )


        )->getResult();
        $virementf = new Virementf();
        $comptebancaire ? $virementf->setComptebancaire($comptebancaire) : true;
        $virementf->setNumero(substr($year, -2) . '-' . str_pad($mois, 2, '0', STR_PAD_LEFT) . '-' . str_pad(count($nb_query) + 1, 3, '0', STR_PAD_LEFT));
        $virementf->setDate($datego);


        foreach ($virements as $virement) {
            $em->persist($virementf);

            $em->flush();
            if ($virement->getFacturefournisseur()) {
                $virement->getFacturefournisseur()->setEtat('Payé');
                $em->persist($virement->getFacturefournisseur());
                $em->flush();

            }
            $virement->setVirementf($virementf);
            $virement->setEtat('executé');
            $em->persist($virement);
            $em->flush();


        }
        $query = $em->createQuery('
            SELECT c as fournisseur,sum(p.achat) as total FROM AppBundle:Virement p 
            JOIN AppBundle:Bcfournisseur c 
            WHERE p.bcfournisseur = c.id AND p.id IN (:ids)
                    
            GROUP BY c.fournisseur
                    ')->setParameter('ids', $Ids)->getResult(Query::HYDRATE_OBJECT);

        foreach ($query as $item) {
            $detail = new Detailvirement();
            $detail->setFournisseur($item['fournisseur']->getFournisseur());
            $detail->setTotal($item['total']);
            $detail->setVirementf($virementf);

            $em->persist($detail);
            $em->flush();
        }

        $response = json_encode(array('data' => $Ids, 'id' => $virementf->getId()));

        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));
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
