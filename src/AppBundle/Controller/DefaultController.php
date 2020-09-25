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
    public function indexAction(Request $request, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('AppBundle:Client')->findAll();
        $fournisseurs = $em->getRepository('AppBundle:Fournisseur')->findAll();
        $consultants = $em->getRepository('AppBundle:Consultant')->findAll();
        $missions = $em->getRepository('AppBundle:Mission')->findAll();
        $virements = $em->getRepository('AppBundle:Virement')->findAll();
        $virements_att = $em->getRepository('AppBundle:Virement')->findBy(['etat'=>'en attente']);
        $facturess = $em->getRepository('AppBundle:Facture')->findAll();
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


        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('aaaimad@gmail.com')
            ->setTo('aaaimad@gmail.com')
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'default/mail.html.twig'

                ),
                'text/html'
            )

            // you can remove the following code if you don't define a text version for your emails

        ;

        $mailer->send($message);

        return $this->render('default/index.html.twig', [
            'nb_client'=>count($clients),
            'virements'=>$virements_att,
            'nb_fournisseur'=>count($fournisseurs),
            'nb_consultant'=>count($consultants),
            'nb_mission'=>count($missions),
         //   'virements'=>$virements,
            'factures'=>count($facturess),


        ]);
    }
}
