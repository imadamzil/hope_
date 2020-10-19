<?php

namespace AppBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\AreaChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
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
        $virements_att = $em->getRepository('AppBundle:Virement')->findBy(['etat' => 'en attente']);
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
        //statistiques

        $query_production = $em->createQuery('
        SELECT CONCAT(f.mois, \'-\',f.year) as mois,avg(f.totalHT) as total,avg(f.totalTTC) as totalc  FROM AppBundle:Facture f 
        WHERE f.etat = :etat
              
        GROUP BY f.mois  ORDER BY f.mois ASC  
        ')->setParameter(':etat', 'payé')->execute();


        $arr[] = ['Mois', 'Total', 'TotalTTC'];
        $i = 1;

        foreach ($query_production as $key => $item) {
            foreach ($item as $k => $v) {
                if ($k == 'mois') {

                    $arr[$i][] = strval($v);
//    $arr[$i][]=$v;

                } else {
                    $arr[$i][] = intval($v);


                }

//    $arr[$i][]=$v;
            }
            $i++;
        }


        $area = new AreaChart();
        $area->getData()->setArrayToDataTable($arr);
        $area->getOptions()->setTitle('');
        $area->getOptions()->getHAxis()->setTitle('Mois');
        $area->getOptions()->getHAxis()->getTitleTextStyle()->setColor('#333');
        $area->getOptions()->getVAxis()->setMinValue(0);
        return $this->render('default/index.html.twig', [
            'nb_client' => count($clients),
            'virements' => $virements_att,
            'nb_fournisseur' => count($fournisseurs),
            'nb_consultant' => count($consultants),
            'nb_mission' => count($missions),
            //   'virements'=>$virements,
            'factures' => count($facturess),

            'area' => $area,


        ]);
    }
}
