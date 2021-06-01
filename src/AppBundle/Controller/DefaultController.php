<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bcclient;
use AppBundle\Entity\Bcfournisseur;
use AppBundle\Entity\Client;
use AppBundle\Entity\Consultant;
use AppBundle\Entity\Facture;
use AppBundle\Entity\Facturefournisseur;
use AppBundle\Entity\Fournisseur;
use AppBundle\Entity\Job;
use AppBundle\Entity\Mission;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\AreaChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\SteppedAreaChart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\PieChart\PieSlice;

use PhpOffice\PhpSpreadsheet\IOFactory;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, \Swift_Mailer $mailer)
    {

//        dump($this->getUser());
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
              
        GROUP BY f.mois  ORDER BY f.year,f.mois ASC  
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
        $area->getOptions()->setColors(array('green', 'blue'));
        $area->getOptions()->getLegend()->setPosition('top');

        //nbr missions + -

        for ($i = 1; $i <= 5; $i++) {
            $m = intval(date("m", strtotime(date('Y-m-01') . " -$i months")));
//            dump($m);
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
        $sarea->getOptions()->setTitle('nouvelles missions / missions terminées');
        $sarea->getOptions()->getVAxis()->setTitle('Nombre');
        $sarea->getOptions()->setIsStacked(false);
        $sarea->getOptions()->setWidth(900);
        $sarea->getOptions()->getLegend()->setPosition('bottom');
//        $sarea->getOptions()->setColors([\'#4374E0\', \'#53A8FB\', \'#F1CA3A\', \'#E49307\']);
        $sarea->getOptions()->setColors(array('green', 'red'));


        // consultant stats
        $production_par_consultant = $em->createQuery('
        SELECT  c.nom as nom,avg(f.totalHT) as total
        FROM AppBundle:Facture f 
        JOIN AppBundle:Consultant c 
        WHERE f.consultant = c
        GROUP BY f.consultant
        ORDER BY total DESC 
        
        ')->setParameters([])->execute();
// array 3

        $arr3[] = ['consultant', 'Total'];
        $i = 1;

        foreach ($production_par_consultant as $key => $item) {
            foreach ($item as $k => $v) {
                if ($k == 'total') {

                    $arr3[$i][] = floatval(number_format((float)$v, 2, '.', ''));
//    $arr[$i][]=$v;

                } else {
                    $arr3[$i][] = strval($v);


                }

//    $arr[$i][]=$v;
            }
            $i++;
        }

        //end array 3
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            $arr3
        );
        $pieChart->getOptions()->setPieSliceText('value-and-percentage');
        $pieChart->getOptions()->setTitle('Production par Consultant');
        $pieChart->getOptions()->setPieStartAngle(70);
        $pieChart->getOptions()->setHeight(600);
        $pieChart->getOptions()->setWidth(1000);
        $pieChart->getOptions()->setIs3D(true);

        $pieChart->getOptions()->getLegend()->setPosition('bottom');


        // end consultant stats


        //client stats
        $production_par_client = $em->createQuery('
       SELECT  c.nom as nom,avg(f.totalHT) as total
        FROM AppBundle:Facture f 
        JOIN AppBundle:Client c 
        WHERE f.client = c
        GROUP BY f.client
        ORDER BY total DESC 
        
        ')->setParameters([])->execute();

// array 4

        $arr4[] = ['client', 'Total'];
        $i = 1;

        foreach ($production_par_client as $key => $item) {
            foreach ($item as $k => $v) {
                if ($k == 'total') {

                    $arr4[$i][] = floatval(number_format((float)$v, 2, '.', ''));
//    $arr[$i][]=$v;

                } else {
                    $arr4[$i][] = strval($v);


                }

//    $arr[$i][]=$v;
            }
            $i++;
        }

        //end array 4

        $pieChartClient = new PieChart();
        $pieChartClient->getData()->setArrayToDataTable(
            $arr4
        );
        $pieChartClient->getOptions()->setPieSliceText('value-and-percentage');
        $pieChartClient->getOptions()->setTitle('Production par Client');
        $pieChartClient->getOptions()->setPieStartAngle(70);
        $pieChartClient->getOptions()->setHeight(600);
        $pieChartClient->getOptions()->setWidth(1000);
        $pieChartClient->getOptions()->setIs3D(true);

        $pieChartClient->getOptions()->getLegend()->setPosition('bottom');

        //end client stats


        //fournisseur

        //end fournisseur

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
            'pieChart' => $pieChart,
            'pieChartClient' => $pieChartClient

        ]);
    }

    /**
     * @Route("/production", name="production")
     */
    public function production()
    {


        $em = $this->getDoctrine()->getManager();

        $bcfournisseurs = array_reverse($em->getRepository('AppBundle:Bcfournisseur')->findAll());
//        dump($bcfournisseurs);
        return $this->render('production.html.twig', array(
            'bcfournisseurs' => $bcfournisseurs,
        ));
    }

    /**
     * @Route("/migration/bcclient", name="migration_bcclient")
     */
    public function migrationBcclient()
    {


        $em = $this->getDoctrine()->getManager();

        ini_set('memory_limit', '1024M');
        $inputFileName = $this->get('kernel')->getRootDir() . '\..\web\bcclient.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);

        set_time_limit(10000); //
        ini_set('memory_limit', '1024M');


        $sheetData = $spreadsheet->getActiveSheet()->toArray();
//        dump($sheetData);


        foreach ($sheetData as $row) {

            if ($row[0] == 'id') {


            } else {
                $id = intval($row[0]);
                $code = $row[1];
                if ($row[2]) {
                    $datef = DateTime::createFromFormat('d/m/Y', $row[2])->format('Y-m-d');
                    $date = new \DateTime($datef);
                } else {
                    $date = null;
                }
                $nbJrs = $row[3];
                $nbJrsR = $row[4];
                if ($row[5]) {
                    $client_arr = $em->getRepository('AppBundle:Client')->findBy([
                        'nom' => $row[5]
                    ]);
                    if (!empty($client_arr)) {
                        $client = $client_arr[0];
                    } else {
                        $client = null;
                    }

                } else {

                    $client = null;
                }
                if ($row[6]) {
                    $consultant_arr = $em->getRepository('AppBundle:Consultant')->findBy([
                        'nom' => $row[6]
                    ]);
                    if (!empty($consultant_arr)) {
                        $consultant = $consultant_arr[0];
                    } else {
                        $consultant = null;
                    }
                } else {

                    $consultant = null;
                }
                $type = $row[9];
                $ncontrat = $row[10];
                $application = $row[11];
                $avenant = $row[12];

                $bcclient = new Bcclient();
                $bcclient->setCode($code);
                $bcclient->setDate($date);
                $bcclient->setNbJrs($nbJrs);
                $bcclient->setNbJrs($nbJrsR);
                $bcclient->setClient($client);
                $bcclient->setConsultant($consultant);
                $bcclient->setType($type);
                $bcclient->setNcontrat($ncontrat);
                $bcclient->setApplication($application);
                $bcclient->setAvenant($avenant);
//                dump($date);

                $em->persist($bcclient);
                $em->flush();


            }

        }

        return $this->render('production.html.twig', array());
    }


    /**
     * @Route("/migration/fournisseur", name="migration_fournisseur")
     */
    public function migrationFournisseur()
    {


        $em = $this->getDoctrine()->getManager();

        ini_set('memory_limit', '1024M');
        $inputFileName = $this->get('kernel')->getRootDir() . '\..\web\fournisseur.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);

        set_time_limit(10000); //
        ini_set('memory_limit', '1024M');


        $sheetData = $spreadsheet->getActiveSheet()->toArray();
//        dump($sheetData);

        foreach ($sheetData as $row) {

            if ($row[0] == 'id') {


            } else {
                $id = intval($row[0]);
                $nom = $row[1];
                $tel = $row[2];
                $fax = $row[3];
                $adresse = $row[4];
                $rib = $row[5];
                $rc = $row[6];

                $ice = $row[7];

                $iif = $row[8];
                $fournisseur = new Fournisseur();
                $fournisseur->setNom($nom);
                $fournisseur->setTel($tel);
                $fournisseur->setFax($fax);
                $fournisseur->setAdresse($adresse);
                $fournisseur->setRib($rib);
                $fournisseur->setRc($rc);
                $fournisseur->setIce($ice);
                $fournisseur->setIif($iif);
                $em->persist($fournisseur);
                $em->flush();
            }

        }

        return $this->render('production.html.twig', array());
    }

    /**
     * @Route("/migration/client", name="migration_client")
     */
    public function migrationClient()
    {


        $em = $this->getDoctrine()->getManager();

        ini_set('memory_limit', '1024M');
        $inputFileName = $this->get('kernel')->getRootDir() . '\..\web\client.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);

        set_time_limit(10000); //
        ini_set('memory_limit', '1024M');


        $sheetData = $spreadsheet->getActiveSheet()->toArray();
//        dump($sheetData);

        foreach ($sheetData as $row) {

            if ($row[0] == 'id') {


            } else {
                $id = intval($row[0]);
                $nom = $row[1];
                $tel = $row[2];
                $fax = $row[3];
                $email = $row[4];
                $adresse = $row[5];
                $contrat_cadre = $row[6];

                $ice = $row[9];

                $echeance = $row[10];
                $client = new Client();
                $client->setNom($nom);
                $client->setTel($tel);
                $client->setFax($fax);
                $client->setEmail($email);
                $client->setAdresse($adresse);
                $client->setContratCadre($contrat_cadre);
                $client->setIce($ice);
                $client->setEcheance($echeance);
                $em->persist($client);
                $em->flush();


            }

        }

        return $this->render('production.html.twig', array());
    }

    /**
     * @Route("/migration/consultant", name="migration_consultant")
     */
    public function migrationConsultant()
    {


        $em = $this->getDoctrine()->getManager();

        ini_set('memory_limit', '1024M');
        $inputFileName = $this->get('kernel')->getRootDir() . '\..\web\consultant.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);

        set_time_limit(10000); //
        ini_set('memory_limit', '1024M');


        $sheetData = $spreadsheet->getActiveSheet()->toArray();
//        dump($sheetData);
//        die();

        foreach ($sheetData as $row) {

            if ($row[0] == 'id') {


            } else {
                $id = intval($row[0]);
                $nom = $row[1];
                $type = $row[2];
                $salaire = $row[3];
                $rib = $row[4];
                $tel = $row[5];
                $email = $row[6];
                $adresse = $row[7];
                $tjm = $row[10];

                $cin = $row[11];

                $consultant = new Consultant();
                $consultant->setNom($nom);
                $consultant->setTel($tel);
                $consultant->setType($type);
                $consultant->setEmail($email);
                $consultant->setRib($rib);
                $consultant->setAdresse($adresse);
                $consultant->setSalaire($salaire);
                $consultant->setTjm($tjm);
                $consultant->setCin($cin);

                $em->persist($consultant);
                $em->flush();


            }

        }

        return $this->render('production.html.twig', array());
    }

    /**
     * @Route("/backup", name="bd_backup")
     */
    public function executeBackupDbAction()
    {
        $db = $this->container->getParameter('database_name');
        $user = $this->container->getParameter('database_user');

        $path = $this->get('kernel')->getRootDir() . '/../web/backup/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $date = new DateTime('now');
        $date_string = $date->format('d_m_Y_H_i_s');
        $fileName = $date_string . '.sql';

        $command = 'mysqldump --user=' . $user . ' ' . $db . ' >' . $path . $fileName;
//        dump($command);
//        die();
        $process = new Process($command);
        $pathtomysql = 'C:\wamp64\bin\mysql\mysql5.7.21\bin';

        file_exists($pathtomysql) ? true : $pathtomysql = 'C:\wamp\bin\mysql\mysql5.6.17\bin';
        $process->setWorkingDirectory($pathtomysql);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        echo $process->getOutput();

        return new Response('success');
    }

    /**
     * @Route("/commande", name="commande")
     */
    public function commandeAction()
    {
        $res = 'ok';
        return new Response($res);
    }

    /**
     * @Route("/upgrade/mission", name="migration_mission")
     */
    public function migrationMission()
    {


        $em = $this->getDoctrine()->getManager();

        ini_set('memory_limit', '1024M');
        $inputFileName = $this->get('kernel')->getRootDir() . '\..\web\new_mission.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);

        set_time_limit(10000); //
        ini_set('memory_limit', '1024M');


        $sheetData = $spreadsheet->getActiveSheet()->toArray();
//      dump($sheetData);
//   die();

        foreach ($sheetData as $row) {

            if ($row[0] == 'id') {


            } else {

                $id = intval($row[0]);
                if ($row[2] != null) {
                    $bcclient_arr = $em->createQuery('
                                SELECT  b
                                FROM AppBundle:Bcclient b 
                                WHERE b.code = :valeur OR b.ncontrat= :valeur
        ')->setParameter('valeur', $row[2])->execute();
                    if (!empty($bcclient_arr)) {
                        $bcclient = $bcclient_arr[0];
                    } else {

                        $bcclient = null;
                    }
                } else {

                    $bcclient = null;
                }
                if ($row[1]) {
                    $client_arr = $em->getRepository('AppBundle:Client')->findBy([
                        'nom' => $row[1]
                    ]);
                    if (!empty($client_arr)) {
                        $client = $client_arr[0];
                    } else {
                        $client = null;
                    }

                } else {

                    $client = null;
                }
                $prixVente = floatval($row[5]);

                $prixAchat = floatval($row[6]);
                $etat = $row[7];
                if ($row[8] != null) {

                    $date = DateTime::createFromFormat('m/d/Y', $row[8]);


                    $date ? $date->format('Y-m-d') : false;
//                    dump($row[8]);
                } else {

                    $date = null;
                }


                if ($row[3]) {
                    $consultant_arr = $em->getRepository('AppBundle:Consultant')->findBy([
                        'nom' => $row[3]
                    ]);
                    if (!empty($consultant_arr)) {
                        $consultant = $consultant_arr[0];
                    } else {
                        $consultant = null;
                    }

                } else {

                    $consultant = null;
                }
                if ($row[4]) {
                    $fournisseur_arr = $em->getRepository('AppBundle:Fournisseur')->findBy([
                        'nom' => $row[4]
                    ]);
                    if (!empty($fournisseur_arr)) {
                        $fournisseur = $fournisseur_arr[0];
                    } else {
                        $fournisseur = null;
                    }

                } else {

                    $fournisseur = null;
                }

                $mission = $em->getRepository('AppBundle:Mission')->find($id);
                if ($mission != null) {

                    $mission->setBcclient($bcclient);
                    $mission->setClient($client);
                    $mission->setPrixVente($prixVente);
                    $mission->setPrixAchat($prixAchat);
                    $mission->setConsultant($consultant);
                    $mission->setFournisseur($fournisseur);
                    if ($etat == 'SORT') {
                        $mission->setStatut('Terminée');
                        $mission->setDateFin($date);
                        $mission->setClosedAt($date);
                    }
//                dump($mission);

                    $em->persist($mission);
//                die();
                    $em->flush();
                }
                if ($id == null) {

                    $mission = new Mission();
                    $mission->setBcclient($bcclient);
                    $mission->setClient($client);
                    $mission->setPrixVente($prixVente);
                    $mission->setPrixAchat($prixAchat);
                    $mission->setConsultant($consultant);
                    $mission->setFournisseur($fournisseur);
                    if ($etat == 'SORT') {
                        $mission->setStatut('Terminée');
                        $mission->setDateFin($date);
                    }
//                dump($mission);

                    $em->persist($mission);
//                die();
                    $em->flush();
                }


            }

        }

        return new Response('ok');
    }

    /**
     * @Route("/upgrade/bc_fournisseur", name="migration_bc_fournisseur")
     */
    public function migrationBCfournisseur()
    {


        $em = $this->getDoctrine()->getManager();

        ini_set('memory_limit', '1024M');
        $inputFileName = $this->get('kernel')->getRootDir() . '\..\web\new_bc_fournisseur.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);

        set_time_limit(10000); //
        ini_set('memory_limit', '1024M');


        $sheetData = $spreadsheet->getActiveSheet()->toArray();
//        dump($sheetData);
//     die();

        foreach ($sheetData as $row) {

            if ($row[0] == 'id') {


            } else {

                $id = intval($row[0]);
                $reference = $row[1];
                if ($row[4] != null) {

                    $date = DateTime::createFromFormat('Y-m-d', $row[4]);


                    $date ? $date->format('Y-m-d') : false;

                    /* if ($datecommande){

                         dump($datecommande,$row[9]);die();
                     }*/


                } else {

                    $date = null;
                }
                $mois = intval($row[5]);

                $nbjours = floatval($row[6]);
                $tjm = $row[7];
                $achatHT = floatval($row[8]);
                $achatTTC = floatval($row[9]);


                if ($row[3]) {
                    $consultant_arr = $em->getRepository('AppBundle:Consultant')->findBy([
                        'nom' => $row[3]
                    ]);
                    if (!empty($consultant_arr)) {
                        $consultant = $consultant_arr[0];
                    } else {
                        $consultant = null;
                    }

                } else {

                    $consultant = null;
                }
                if ($row[2]) {
                    $fournisseur_arr = $em->getRepository('AppBundle:Fournisseur')->findBy([
                        'nom' => $row[2]
                    ]);
                    if (!empty($fournisseur_arr)) {
                        $fournisseur = $fournisseur_arr[0];
                    } else {
                        $fournisseur = null;
                    }

                } else {

                    $fournisseur = null;
                }

                $bcfournisseur = $em->getRepository('AppBundle:Bcfournisseur')->find($id);

                if ($bcfournisseur != null) {
                    $bcfournisseur->setCode($reference);
                    $bcfournisseur->setFournisseur($fournisseur);
                    $bcfournisseur->setConsultant($consultant);
                    $bcfournisseur->setDate($date);

                    $bcfournisseur->setMois($mois);
                    $bcfournisseur->setNbjours($nbjours);
                    $bcfournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($bcfournisseur->getAchatHT() * 0.2);
                    $bcfournisseur->setAchatTTC($achatTTC);
//                dump($bcfournisseur);

                    $em->persist($bcfournisseur);
//                die();
                    $em->flush();

                }


            }

        }

        return new Response('ok');
    }

    /**
     * @Route("/upgrade/facture_fournisseur", name="migration_facture_fournisseur")
     */
    public function migrationfacturefournisseur()
    {


        $em = $this->getDoctrine()->getManager();

        ini_set('memory_limit', '1024M');
        $inputFileName = $this->get('kernel')->getRootDir() . '\..\web\facture_fournisseur.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);

        set_time_limit(10000); //
        ini_set('memory_limit', '1024M');


        $sheetData = $spreadsheet->getActiveSheet()->toArray();
//        dump($sheetData);


        foreach ($sheetData as $row) {

            if ($row[0] == 'id') {


            } else {

                $id = intval($row[0]);
                $reference = $row[1];
                if ($row[4] != null) {

                    $date = DateTime::createFromFormat('Y-m-d', $row[4]);


                    $date ? $date->format('Y-m-d') : false;

                    /* if ($datecommande){

                         dump($datecommande,$row[9]);die();
                     }*/


                } else {

                    $date = null;
                }
                $mois = intval($row[5]);

                $nbjours = floatval($row[6]);

                $achatHT = floatval($row[7]);
                $achatTTC = floatval($row[8]);
                $etat = $row[9];


                if ($row[3]) {
                    $consultant_arr = $em->getRepository('AppBundle:Consultant')->findBy([
                        'nom' => $row[3]
                    ]);
                    if (!empty($consultant_arr)) {
                        $consultant = $consultant_arr[0];
                    } else {
                        $consultant = null;
                    }

                } else {

                    $consultant = null;
                }
                if ($row[2]) {
                    $fournisseur_arr = $em->getRepository('AppBundle:Fournisseur')->findBy([
                        'nom' => $row[2]
                    ]);
                    if (!empty($fournisseur_arr)) {
                        $fournisseur = $fournisseur_arr[0];
                    } else {
                        $fournisseur = null;
                    }

                } else {

                    $fournisseur = null;
                }

                $facturefournisseur = $em->getRepository('AppBundle:Facturefournisseur')->find($id);
//                $facturefournisseur = new Facturefournisseur();
                if ($facturefournisseur != null) {
                    $facturefournisseur->setNumero($reference);
                    $facturefournisseur->setFournisseur($fournisseur);
                    $facturefournisseur->setConsultant($consultant);
                    $facturefournisseur->setDate($date);

                    $facturefournisseur->setMois($mois);
                    $facturefournisseur->setNbjours($nbjours);
                    $facturefournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setTaxe($facturefournisseur->getAchatHT() * 0.2);
                    $facturefournisseur->setAchatTTC($achatTTC);
                    $facturefournisseur->setEtat($etat);

//                dump($facturefournisseur);

                    $em->persist($facturefournisseur);
//                die();
                    $em->flush();

                }


            }

        }

        return new Response('ok');
    }

    /**
     * @Route("/commande/execute", name="execute_commande")
     */
    public function executeComandes()
    {
        $command = 'php bin/console doctrine:schema:update --dump-sql';
        $process = new Process($command);

        $path = $this->get('kernel')->getRootDir() . '/../';


        $process->setWorkingDirectory($path);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        echo $process->getOutput();

        return new Response('success');
    }

    /**
     * @Route("/commande/set_commande", name="set_commande")
     */
    public function setComandes(Request $request)
    {
        $data = ['commande' => 'php bin/console'];

        $form = $this->createFormBuilder($data)
            ->add('commande')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $command = $form->get('commande')->getData();
            $process = new Process($command);

            $path = $this->get('kernel')->getRootDir() . '/../';


            $process->setWorkingDirectory($path);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $output = $process->getOutput();
        } else {

            $output = 'Entrez la commande';
        }


        return $this->render('default/commande.html.twig', [
            'form' => $form->createView(),
            'output' => $output

        ]);
    }
}