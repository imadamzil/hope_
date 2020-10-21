<?php

namespace AppBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\AreaChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\SteppedAreaChart;
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
//function mois
        function mois_convert($m)
        {
            switch ($m) {
                case 1:
                    return "Janvier";
                    break;
                case 2:
                    return "Février";
                    break;
                case 3:
                    return "Mars";
                    break;
                case 4:
                    return "Avril";
                    break;
                case 5:
                    return "Mai";
                    break;
                case 6:
                    return "Juin";
                    break;
                case 7:
                    return "Juillet";
                    break;
                case 8:
                    return "Aout";
                    break;
                case 9:
                    return "Septembre";
                    break;
                case 10:
                    return "Octobre";
                    break;
                case 11:
                    return "Novembre";
                    break;
                case 12:
                    return "Décembre";
                    break;

            }
        }

//end function mois
        // Total last month
        $date = new \DateTime('now');
        $mois = intval($date->format('m')) - 1;
        $mois_string = mois_convert($mois);
        $year = intval($date->format('yy'));
        $ttlastmonth = $em->createQuery('
        
        SELECT avg(f.totalHT) as total 
        FROM AppBundle:Facture f
        WHERE f.mois = :mois and f.year = :year and f.etat = :etat
        
        ')->setParameters([

            'mois' => $mois,
            'year' => $year,
            'etat' => 'payé'
        ])->getResult();

//        dump($mois, $year, $ttlastmonth[0]);

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
        $area->getOptions()->setWidth(900);
        $area->getOptions()->getLegend()->setPosition('top');

        //nbr missions + -

        for ($i = 1; $i <= 5; $i++) {
            $m = intval(date("m", strtotime(date('Y-m-01') . " -$i months")));

            // depart query
            $q = $em->createQuery('
        
        SELECT count(DISTINCT m) from AppBundle:Mission m 
        
        WHERE m.statut =:statut
        AND MONTH( m.createdAt)= :m
        
        ')->setParameters([':statut' => 'En cours',
                ':m' => $m
            ])->getOneOrNullResult();

            //end query
            //   // depart query
            $q1 = $em->createQuery('
        
        SELECT count(DISTINCT m) from AppBundle:Mission m 
        
        WHERE m.statut =:statut
        AND MONTH( m.closedAt)= :m
        
        ')->setParameters([':statut' => 'Terminé',
                ':m' => $m
            ])->getOneOrNullResult();

            //end query
            //   // depart query
            $q2 = $em->createQuery('
        
        SELECT count(DISTINCT m) from AppBundle:Mission m 
        
        WHERE m.statut =:statut
    
        
        ')->setParameters([':statut' => 'En cours',

            ])->getOneOrNullResult();

            //end query

            $first = strtotime('first day this month');

            $months[0] = ['ttt', 'NEW', 'Terminé'];
            $months[] = [date('M', strtotime("-$i month", $first)), intval($q[1]), intval($q1[1])];

        }


        $sarea = new SteppedAreaChart();
        $sarea->getData()->setArrayToDataTable(
            $months
        );
        $sarea->getOptions()->setTitle('');
        $sarea->getOptions()->getVAxis()->setTitle('Nombre');
        $sarea->getOptions()->setIsStacked(false);
        $sarea->getOptions()->setWidth(900);
        $sarea->getOptions()->getLegend()->setPosition('bottom');
//        $sarea->getOptions()->setColors([\'#4374E0\', \'#53A8FB\', \'#F1CA3A\', \'#E49307\']);


        return $this->render('default/index.html.twig', [
            'nb_client' => count($clients),
            'virements' => $virements_att,
            'nb_fournisseur' => count($fournisseurs),
            'nb_consultant' => count($consultants),
            'nb_mission' => count($missions),
            //   'virements'=>$virements,
            'factures' => count($facturess),

            'area' => $area,
            'sarea' => $sarea,
            'mois' => $mois_string,
            'production_last_month' => $ttlastmonth,

        ]);
    }
}
