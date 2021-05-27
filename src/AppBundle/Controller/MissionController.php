<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mission;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mission controller.
 *
 * @Route("mission")
 */
class MissionController extends Controller
{

    /**
     *
     *
     * @Route("/missions_archived" , name="missions_archived")
     * @Method("GET")
     */
    public function getMissionArchived()
    {


        $em = $this->getDoctrine()->getManager();
        $missions = $em->getRepository('AppBundle:Mission')->findBy([
            'statut' => 'Terminée'

        ]);

        dump($missions);
        return $this->render('mission/missions_archived.html.twig', [

            'missions' => $missions
        ]);
    }

    /**
     * Lists all mission entities.
     *
     * @Route("/", name="mission_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT m
    FROM AppBundle:Mission m
    WHERE m.statut != :statut'
        )->setParameter('statut', 'Terminée');

        $missions = $query->getResult();
//        $missions = $em->getRepository('AppBundle:Mission')->findAll();
        $missions_sans_contratF = $em->getRepository('AppBundle:Mission')->findBy([
            'contratFName' => null

        ]);
        $missions_sans_contratC = $em->getRepository('AppBundle:Mission')->findBy([
            'contratCName' => null

        ]);
        $missions_sans_BC = $em->getRepository('AppBundle:Mission')->findBy([
            'bcName' => null

        ]);
        $nb_sans_contratF = count($missions_sans_contratF);
        $nb_sans_contratC = count($missions_sans_contratC);
        $nb_sans_BC = count($missions_sans_BC);

        $query = $em->createQuery('
SELECT COUNT(m) FROM AppBundle:Mission m 
JOIN AppBundle:Client c 
WHERE m.client = c.id AND m.bcName IS NULL AND c.contratCadre IS null 
        
        ')->execute();

        //dump($query);

        return $this->render('mission/index.html.twig', array(
            'missions' => $missions,
            'nb_F' => $nb_sans_contratF,
            'nb_C' => $nb_sans_contratC,
            'nb_BC' => $query[0][1],


        ));
    }

    /**
     * Lists all mission entities.
     *
     * @Route("/mission_sans_contract_client", name="mission_sans_contrat_client")
     * @Method("GET")
     */
    public function missionssanscontracclientAction()
    {
        $em = $this->getDoctrine()->getManager();

        $missions = $em->getRepository('AppBundle:Mission')->findAll();

        $missions_sans_contratC = $em->getRepository('AppBundle:Mission')->findBy([
            'contratCName' => null

        ]);
    }

    /**
     * Lists all mission entities.
     *
     * @Route("/mission_termine", name="mission_termine")
     * @Method("GET")
     */
    public function missionstermineAction()
    {
        $em = $this->getDoctrine()->getManager();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT m
    FROM AppBundle:Mission m
    WHERE m.statut == :statut'
        )->setParameter('statut', 'Terminée');

        $missions = $query->getResult();


        return $this->render('mission/missions_sans_contract_client.html.twig', array(
            'missions' => $missions,


        ));
    }

    /**
     * Lists all mission entities.
     *
     * @Route("/mission_sans_contract_fournisseur", name="mission_sans_contrat_fournisseur")
     * @Method("GET")
     */
    public function missionssanscontratfournisseurAction()
    {
        $em = $this->getDoctrine()->getManager();

        $missions_sans_contratF = $em->getRepository('AppBundle:Mission')->findBy([
            'contratFName' => null

        ]);


        return $this->render('mission/missions_sans_contract_fournisseur.html.twig', array(
            'missions' => $missions_sans_contratF,


        ));
    }

    /**
     * Lists all mission entities.
     *
     * @Route("/mission_sans_bc_client", name="mission_sans_bc_client")
     * @Method("GET")
     */
    public function missionssansbcclientAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('
SELECT m FROM AppBundle:Mission m 
JOIN AppBundle:Client c 
WHERE m.client = c.id AND m.bcName IS NULL AND c.contratCadre IS null 
        
        ')->execute();


        $missions_sans_BC = $em->getRepository('AppBundle:Mission')->findBy([
            'bcName' => null

        ]);


        return $this->render('mission/missions_sans_bc_client.html.twig', array(
            'missions' => $query,


        ));
    }


    /**
     * Creates a new mission entity.
     *
     * @Route("/new", name="mission_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $mission = new Mission();
        $form = $this->createForm('AppBundle\Form\MissionType', $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //dump($request);

            $cc = $request->request->get('switch-field-1');


            // die();
            $em = $this->getDoctrine()->getManager();
            $em->persist($mission);
            $em->flush();
            $miss = $em->getRepository('AppBundle:Mission')->find($mission->getId());

            if ($cc == 'on') {

                $miss->setcontratCName($miss->getClient()->getContratCadre());
                $em->persist($miss);
                $em->flush();
                /*dump($miss);
                die();*/
            } else {
//                $p = 'ok';
//                dump($p);
//                die();

            }
            return $this->redirectToRoute('mission_show', array('id' => $mission->getId()));
        }

        return $this->render('mission/new.html.twig', array(
            'mission' => $mission,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a mission entity.
     *
     * @Route("/{id}", name="mission_show")
     * @Method("GET")
     */
    public function showAction(Mission $mission)
    {
        $deleteForm = $this->createDeleteForm($mission);

        return $this->render('mission/show.html.twig', array(
            'mission' => $mission,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a mission entity.
     *
     * @Route("/{id}/end", name="mission_end")
     * @Method("GET")
     */
    public function terminerAction(Mission $mission)
    {
        $mission->setStatut('Terminé');
        $mission->setClosedAt(new \DateTime('now'));
        $mission->setUpdatedAt(new \DateTime('now'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($mission);
        $em->flush();

        return $this->redirectToRoute('mission_show', array('id' => $mission->getId()));

    }

    /**
     * Displays a form to edit an existing mission entity.
     *
     * @Route("/{id}/edit", name="mission_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Mission $mission)
    {
        $deleteForm = $this->createDeleteForm($mission);
        $editForm = $this->createForm('AppBundle\Form\MissionType', $mission);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mission_edit', array('id' => $mission->getId()));
        }

        return $this->render('mission/edit.html.twig', array(
            'mission' => $mission,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing mission entity.
     *
     * @Route("/{id}/upload", name="mission_upload")
     * @Method({"GET", "POST"})
     */
    public function uploadAction(Request $request, Mission $mission)
    {
        $deleteForm = $this->createDeleteForm($mission);
        $editForm = $this->createForm('AppBundle\Form\MissionType', $mission);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mission_show', array('id' => $mission->getId()));
        }

        return $this->render('mission/upload.html.twig', array(
            'mission' => $mission,
            'form' => $editForm->createView(),

        ));
    }

    /**
     * Deletes a mission entity.
     *
     * @Route("/{id}", name="mission_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Mission $mission)
    {
        $form = $this->createDeleteForm($mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mission);
            $em->flush();
        }

        return $this->redirectToRoute('mission_index');
    }

    /**
     * Creates a form to delete a mission entity.
     *
     * @param Mission $mission The mission entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Mission $mission)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('mission_delete', array('id' => $mission->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     *
     * @Route("/dep", name="route_to_retrieve_departement",options={"expose"=true})
     ** @Method({"GET", "POST"})
     */
    public function getDepartement(Request $request)
    {
        $Id = $request->get('idClient');
        $departement_exist = false;
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('AppBundle:Client')->find($Id);

        if ($client != null) {
            $departements = $client->getDepartements();

            if ($client->getDepartements() != null and $client->getDepartements()->count() >> 0) {
                $departement_exist = true;
                $count = $client->getDepartements()->count();

            } else {

                $departement_exist = false;
                $count = 0;
            }
        } else {

            $departements = null;
        }


        $response = json_encode(array('exist' => $departement_exist,
            'count' => $count,
            'departements' => $departements

        ));


        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));

    }

    /**
     *
     * @Route("/bc_date_fin", name="route_to_retrieve_date_fin",options={"expose"=true})
     ** @Method({"GET", "POST"})
     */
    public function getDateFin(Request $request)
    {
        $Id = $request->get('idBClient');
        //  $departement_exist = false;
        $em = $this->getDoctrine()->getManager();
        $bclient = $em->getRepository('AppBundle:Bcclient')->find($Id);

        if ($bclient != null) {

            $nbr_joursR = $bclient->getNbJrsR();

        } else {

            $nbr_joursR = null;
        }


        $response = json_encode(array('nb_jrs' => $nbr_joursR,


        ));


        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));

    }

    /**
     *
     * @Route("{id}/reactivate/missions" ,name="mission_open")
     * @Method("GET")
     */
    public function reactivateMission(Mission $mission)
    {
        $em = $this->getDoctrine()->getManager();
        $mission->setStatut('En cours');
        $mission->setUpdatedAt(new \DateTime('now'));

        $em->persist($mission);
        $em->flush();

        return $this->redirectToRoute('mission_index');
    }
}
