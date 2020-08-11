<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
/*
        $clients = $em->getRepository('AppBundle:Client')->findAll();
        $fournisseurs = $em->getRepository('AppBundle:Fournisseur')->findAll();
        $consultants = $em->getRepository('AppBundle:Consultant')->findAll();
        $missions = $em->getRepository('AppBundle:Mission')->findAll();
        $virements = $em->getRepository('AppBundle:Virement')->findAll();*/

        $Id = 6;
        $year = 2020;
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('AppBundle:Mission')->find($Id);
        $factures = $em->getRepository('AppBundle:Facture')->findBy(

            [
                'mission' => $mission,
                'year' => $year
            ]


        );
        dump($factures);

        return $this->render('default/index.html.twig', [
            'nb_client'=>count($clients),
            'nb_fournisseur'=>count($fournisseurs),
            'nb_consultant'=>count($consultants),
            'nb_mission'=>count($missions),
            'virements'=>$virements,


        ]);
    }
}
