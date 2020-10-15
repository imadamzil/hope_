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
        //statistiques

        $query_production = $em->createQuery('
        SELECT f.mois,avg(f.totalHT) as total  FROM AppBundle:Facture f 
        WHERE f.etat = :etat
                
        GROUP BY f.mois
        ')->setParameter(':etat','payé')->execute();

        dump($query_production);

        $arr=[];
        foreach ($query_production as $item){


            $arr[]= [$item['mois'],$item['total']];

        }
dump($arr);
        $histogram = new Histogram();
        $histogram->getData()->setArrayToDataTable([
            ['Population'],
            [12000000],
            [13000000],
            [100000000],
            [1000000000],
            [25000000],
            [600000],

        ]);
        $histogram->getOptions()->setTitle('Country Populations');
        $histogram->getOptions()->setWidth(900);
        $histogram->getOptions()->setHeight(500);
        $histogram->getOptions()->getLegend()->setPosition('none');
        $histogram->getOptions()->setColors(['#e7711c']);
        $histogram->getOptions()->getHistogram()->setLastBucketPercentile(10);
        $histogram->getOptions()->getHistogram()->setBucketSize(10000000);
        $area = new AreaChart();
        $area->getData()->setArrayToDataTable([
            ['Year', 'Sales', 'Expenses'],
            ['2013',  1000,      400],
            ['2014',  1170,      460],
            ['2015',  660,       1120],
            ['2016',  1030,      540]
        ]);
        $area->getOptions()->setTitle('Production Test');
        $area->getOptions()->getHAxis()->setTitle('Year');
        $area->getOptions()->getHAxis()->getTitleTextStyle()->setColor('#333');
        $area->getOptions()->getVAxis()->setMinValue(0);
        return $this->render('default/index.html.twig', [
            'nb_client'=>count($clients),
            'virements'=>$virements_att,
            'nb_fournisseur'=>count($fournisseurs),
            'nb_consultant'=>count($consultants),
            'nb_mission'=>count($missions),
         //   'virements'=>$virements,
            'factures'=>count($facturess),
            'histogram' => $histogram,
            'area'=>$area,


        ]);
    }
}
