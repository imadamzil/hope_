<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bcfournisseur;
use AppBundle\Entity\Facture;
use AppBundle\Entity\Facturefournisseur;
use AppBundle\Entity\FactureHsup;
use AppBundle\Entity\Mission;
use AppBundle\Entity\Production;
use AppBundle\Form\FactureHsupType;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Facture controller.
 *
 * @Route("facture")
 */
class FactureController extends Controller
{
    /**
     * Lists all facture entities.
     *
     * @Route("/", name="facture_index",options={"expose"=true})
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

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
                case 0:
                    return "Décembre";
                    break;

            }
        }

        $factures = $em->getRepository('AppBundle:Facture')->findAll();
        $factures_sans_timesheet = $em->getRepository('AppBundle:Facture')->findBy([
            'documentName' => null
        ]);
//        dump($factures);
        $missions = $em->getRepository('AppBundle:Mission')->findAll();

        $date = new \DateTime('now');
        $mois = intval($date->format('m')) - 1;
        if ($mois == 0) {
            $mois = 12;
        }
        $day = intval($date->format('d'));
        if ($day >= 10) {

            foreach ($missions as $mission) {

                $facture = $em->getRepository('AppBundle:Facture')->findBy(

                    [
                        'mission' => $mission,
                        'mois' => $mois
                    ]


                );
                if ($facture != null) {

                    $missions_factured[] = $mission;
                    $diff = array_diff_assoc($missions, $missions_factured);
                    $nb_non_factured_missions = count($diff);
                } else {

                    $missions_factured[] = null;
                    $diff = array_diff_assoc($missions, $missions_factured);
                    $nb_non_factured_missions = count($diff);
                }

            }


            //  dump($diff, $missions, $missions_factured, count($diff));

            // $nb_non_factured_missions = count($diff);
        } else {

            $nb_non_factured_missions = null;
            //$diff = array_diff_assoc($missions, $missions_factured);
        }
        // $nb_non_factured_missions = null;
        //dump($factures);
        return $this->render('facture/index.html.twig', array(
            'factures' => $factures,
            'nb_non_factured_missions' => $nb_non_factured_missions,
            'facture_sans_timesheet' => $factures_sans_timesheet,
            'mois' => mois_convert($mois)
        ,
        ));
    }

    /**
     * Lists all facture entities.
     *
     * @Route("/sans_timesheet", name="facture_sans_timesheet",options={"expose"=true})
     * @Method("GET")
     */
    public function sanstimesheetAction()
    {
        $em = $this->getDoctrine()->getManager();


        $factures = $em->getRepository('AppBundle:Facture')->findBy([

            'documentName' => null
        ]);
//        dump($factures);

        return $this->render('facture/sans_timesheet.twig', array(
            'factures' => $factures,


        ));
    }

    /**
     * Lists all facture entities.
     *
     * @Route("/mission_sans_facture", name="mission_sans_facture")
     * @Method("GET")
     */
    public function missionsSansFactureAction()
    {
        $em = $this->getDoctrine()->getManager();
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
                case 0:
                    return "Décembre";
                    break;

            }
        }

        $factures = $em->getRepository('AppBundle:Facture')->findAll();
        $missions = $em->getRepository('AppBundle:Mission')->findAll();
        //dump($factures);
        $date = new \DateTime('now');
        $mois = intval($date->format('m')) - 1;
        if ($mois == 0) {
            $mois = 12;
        }
        $day = intval($date->format('d'));
        if ($day >= 10) {

            foreach ($missions as $mission) {

                $facture = $em->getRepository('AppBundle:Facture')->findBy(

                    [
                        'mission' => $mission,
                        'mois' => $mois
                    ]


                );
                if ($facture != null) {

                    $missions_factured[] = $mission;
                    $diff = array_diff_assoc($missions, $missions_factured);
                    $nb_non_factured_missions = count($diff);
                } else {

                    $missions_factured[] = null;
                    $diff = array_diff_assoc($missions, $missions_factured);
                    $nb_non_factured_missions = count($diff);
                }

            }


            // //dump($diff, $missions, $missions_factured, count($diff));

            // $nb_non_factured_missions = count($diff);
        } else {

            $nb_non_factured_missions = null;
            //$diff = array_diff_assoc($missions, $missions_factured);
        }


        return $this->render('facture/missionsansfacture.html.twig', array(
            'factures' => $factures,
            'mois' => mois_convert($mois),
            'missions' => $diff
        ));
    }

    /**
     * Creates a new facture entity.
     *
     * @Route("/new", name="facture_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $facture = new Facture();
        $facture->setAddedby($this->getUser());


        $bcfournisseur = new Bcfournisseur();
        $facturefournisseur = new Facturefournisseur();

        $date = new \DateTime('now');

        $em = $this->getDoctrine()->getManager();

        /* $nb = count($em->getRepository('AppBundle:Facture')->findBy(array(

             'mois'=>$mois,
             'year'=>$year
         )));*/


        $facturefournisseur->setEtat('non payé');


        $facture->setEtat('non payé');

        $form = $this->createForm('AppBundle\Form\FactureType', $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $facture->setEtat('non payé');
            $em->persist($facture);
            $em->flush();
//            dump($facture);
//            die();

            $mois = intval($facture->getDate()->format('m'));
            $year = intval($facture->getDate()->format('y'));
            $facture = $em->getRepository('AppBundle:Facture')->find($facture->getId());
            $nb = count($em->getRepository('AppBundle:Facture')->findBy(array(

                'mois' => $mois,
                'year' => $year,
            )));

            $mission = $facture->getMission();
            //dump($mission, $nb);
            $nb_facture = $nb + 1;
            $facture->setNumero('H3K-' . substr($year, -2) . '-' . str_pad($mois, 2, '0', STR_PAD_LEFT) . '-' . str_pad($nb, 3, '0', STR_PAD_LEFT));
            $facture->setBcclient($mission->getBcclient());
            $facture->setBcclient($mission->getBcclient());

            $facture->setClient($mission->getClient());
            $facture->setMission($mission);

            $prixAchatHT = $mission->getPrixAchat();
            $prixVenteHT = $mission->getPrixVente();
            $bcfournisseur->setMission($mission);
            $facturefournisseur->setMission($mission);
            $facture->setConsultant($mission->getConsultant());
            if ($mission->getDevise() == 'DH') {

                if ($mission->getType() == 'journaliere') {

                    $totalHT = $prixVenteHT * $facture->getNbjour();
                    $achatHT = $prixAchatHT * $facture->getNbjour();
                    $TVA = ($prixVenteHT * $facture->getNbjour()) * 0.2;
                    $TVA_Achat = $achatHT * 0.2;
                    $bcfournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $bcfournisseur->setVenteHT($totalHT);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facture->setTotalHT($totalHT);
                    $facture->setTaxe($TVA);
                    $facture->setTotalTTC($TVA + $totalHT);
                    $bcclient = $facture->getBcclient();
                    //dump($bcclient);
                    if ($bcclient != null) {

                        $bcclient->setNbJrsR($bcclient->getNbJrsR() - $facture->getNbjour());
                        $em->persist($bcclient);
                        $em->flush();
                    }
                } else {
                    $totalHT = $prixVenteHT;
                    $achatHT = $prixAchatHT;
                    $TVA_Achat = $achatHT * 0.2;
                    $bcfournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $bcfournisseur->setVenteHT($totalHT);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $TVA = ($prixVenteHT) * 0.2;
                    $facture->setTaxe($TVA);

                    $facture->setTotalHT($totalHT);
                    $facture->setTotalTTC($TVA + $totalHT);

                }


            } else {
                if ($mission->getType() == 'journaliere') {

                    $totalHT = $prixVenteHT * $facture->getNbjour();
                    $achatHT = $prixAchatHT * $facture->getNbjour();
                    $TVA = 0;
                    $TVA_Achat = 0;
                    $bcfournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $bcfournisseur->setVenteHT($totalHT);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facture->setTotalHT($totalHT);
                    $facture->setTaxe($TVA);
                    $facture->setTotalTTC($TVA + $totalHT);
                    $bcclient = $facture->getBcclient();
                    //dump($bcclient);
                    if ($bcclient != null) {

                        $bcclient->setNbJrsR($bcclient->getNbJrsR() - $facture->getNbjour());
                        $em->persist($bcclient);
                        $em->flush();
                    }
                } else {
                    $totalHT = $prixVenteHT;
                    $achatHT = $prixAchatHT;
                    $TVA_Achat = 0;
                    $bcfournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $bcfournisseur->setVenteHT($totalHT);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facturefournisseur->setBcfournisseur($bcfournisseur);
                    $TVA = 0;
                    $facture->setTaxe($TVA);

                    $facture->setTotalHT($totalHT);
                    $facture->setTotalTTC($TVA + $totalHT);

                }


            }
            $facture->setEtat('non payé');
            $em->persist($facture);
            $em->flush();


            $facture = $em->getRepository('AppBundle:Facture')->find($facture->getId());


            $bcfournisseur->setFournisseur($facture->getMission()->getFournisseur());
            $bcfournisseur->setNbjours($facture->getNbjour());
            $bcfournisseur->setMois($facture->getMois());
            $bcfournisseur->setYear($facture->getYear());
            $bcfournisseur->setDate(new \DateTime('now'));
            $facturefournisseur->setFournisseur($facture->getMission()->getFournisseur());
            $facturefournisseur->setNbjours($facture->getNbjour());
            $facturefournisseur->setMois($facture->getMois());
            $facturefournisseur->setYear($facture->getYear());
            $facturefournisseur->setDate(new \DateTime('now'));
            $facturefournisseur->setBcfournisseur($bcfournisseur);

            $em->persist($bcfournisseur);
            $em->flush();
            $em->persist($facturefournisseur);
            $em->flush();
            $heures = $form->get('facturehsups')->getData();
            if (!empty($heures)) {

                foreach ($heures as $heure) {
                    $nb_jour_sup = $heure->getNbheure() / 10;
                    $heure->setNbjour($nb_jour_sup);
                    $heure->setFacture($facture);
                    $heuresup = $heure->getHeuresup();
                    $heure->setTotalHT($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $facture->getMission()->getVente());
                    $heure->setTotalTTC(($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $facture->getMission()->getVente()) * 1.2);

                    $em->persist($heure);
                    $em->flush();
                }
            }
//            dump($facture);
//            die();
            return $this->redirectToRoute('facture_show', array('id' => $facture->getId()));
        }

        return $this->render('facture/new.html.twig', array(
            'facture' => $facture,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing facture entity.
     *
     * @Route("/{id}/facture", name="facture_mission")
     * @Method({"GET", "POST"})
     */
    public function newfromMissionAction(Request $request, Mission $mission)
    {
        $facture = new Facture();
        $facture->setAddedby($this->getUser());


        $bcfournisseur = new Bcfournisseur();
        $bcfournisseur->setFacture($facture);
        $facturefournisseur = new Facturefournisseur();
        $facturefournisseur->setFacture($facture);
        $date = new \DateTime('now');
        $mois = intval($date->format('m')) - 1;
        $year = intval($date->format('y')) - 1;
        $facturefournisseur->setEtat('non payé');
        $facture->setEtat('non payé');
        $facture->setBcclient($mission->getBcclient());
        $facture->setClient($mission->getClient());
        $facture->setMission($mission);
        /*dump($mission->getClient(), $facture);
        die();*/
        $prixAchatHT = $mission->getPrixAchat();
        $prixVenteHT = $mission->getPrixVente();
        $facture->setConsultant($mission->getConsultant());


        $form = $this->createForm('AppBundle\Form\FactureType', $facture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $facture->setEtat('non payé');
            $em->persist($facture);
            $em->flush();

            $facture = $em->getRepository('AppBundle:Facture')->find($facture->getId());

            $mois = intval($facture->getDate()->format('m'));
            $year = intval($facture->getDate()->format('Y'));
            $yearmini = intval($facture->getDate()->format('y'));

            $nb = count($em->getRepository('AppBundle:Bcfournisseur')->findBy(array(

                'mois' => $facture->getMois(),
                'year' => $facture->getYear(),
            )));

            $nbb = $em->createQuery('
            
            SELECT COUNT(f) as total FROM AppBundle:Facture f 
            WHERE MONTH(f.date) = :moi AND YEAR(f.date) = :annee
            ')
                ->setParameters([

                    'moi' => $mois,
                    'annee' => $year,
                ])->getResult();


            $count_factures = (int)$nbb[0]['total'];
            $mission = $facture->getMission();
            $bcfournisseur->setMission($mission);
            $facturefournisseur->setMission($mission);
            $bcfournisseur->setConsultant($mission->getConsultant());
            $facturefournisseur->setConsultant($mission->getConsultant());
            //dump($mission, $nb);
            $nb_facture = $nb + 1;
            $facture->setNumero('H3K-' . substr($year, -2) . '-' . str_pad($mois, 2, '0', STR_PAD_LEFT) . '-' . str_pad($count_factures, 3, '0', STR_PAD_LEFT));
            $bcfournisseur->setCode('BC-' . substr($facture->getYear(), -2) . '-' . str_pad($facture->getMois(), 2, '0', STR_PAD_LEFT) . '-' . str_pad($nb + 1, 3, '0', STR_PAD_LEFT));
            if ($mission->getDevise() == 'DH') {

                if ($mission->getType() == 'journaliere') {
                    $totalHT = $prixVenteHT * $facture->getNbjour();


                    $achatHT = $prixAchatHT * $facture->getNbjour();
//                    dump($prixVenteHT,$facture->getNbjour()) ; die();

                    $TVA = ($prixVenteHT * $facture->getNbjour()) * 0.2;
                    $TVA_Achat = $achatHT * 0.2;
                    $bcfournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $bcfournisseur->setVenteHT($totalHT);
                    $facturefournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facture->setTotalHT($totalHT);
                    $facture->setClient($mission->getClient());
                    $facture->setTaxe($TVA);
                    $facture->setTotalTTC($TVA + $totalHT);
                    $bcclient = $facture->getBcclient();
                    $facture->setClient($mission->getClient());
                    //dump($bcclient);
                    if ($bcclient != null) {
                        $bcclient->setNbJrsR($bcclient->getNbJrsR() - $facture->getNbjour());
                        $em->persist($bcclient);
                        $em->flush();
                    }
                } else {

                    $totalHT = $prixVenteHT;
                    $achatHT = $prixAchatHT;
                    $TVA_Achat = $achatHT * 0.2;
                    $bcfournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $bcfournisseur->setVenteHT($totalHT);
                    $facturefournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $TVA = ($prixVenteHT) * 0.2;
                    $facture->setTaxe($TVA);

                    $facture->setTotalHT($totalHT);
                    $facture->setTotalTTC($TVA + $totalHT);

                }


            } else {

                if ($mission->getType() == 'journaliere') {
                    $totalHT = $prixVenteHT * $facture->getNbjour();
                    $achatHT = $prixAchatHT * $facture->getNbjour();
                    $TVA = 0;
                    $TVA_Achat = 0;
                    $bcfournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $bcfournisseur->setVenteHT($totalHT);
                    $facturefournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facture->setTotalHT($totalHT);
                    $facture->setTaxe($TVA);
                    $facture->setClient($mission->getClient());
                    $facture->setTotalTTC($TVA + $totalHT);
                    $bcclient = $facture->getBcclient();
                    //dump($bcclient);
                    if ($bcclient != null) {
                        $bcclient->setNbJrsR($bcclient->getNbJrsR() - $facture->getNbjour());
                        $em->persist($bcclient);
                        $em->flush();
                    }
                } else {
                    $totalHT = $prixVenteHT;
                    $achatHT = $prixAchatHT;
                    $TVA_Achat = 0;
                    $bcfournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $bcfournisseur->setVenteHT($totalHT);
                    $facturefournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $TVA = 0;
                    $facture->setTaxe($TVA);

                    $facture->setTotalHT($totalHT);
                    $facture->setClient($mission->getClient());
                    $facture->setTotalTTC($TVA + $totalHT);
//                    dump($facture);die();
                }
            }
            $facture->setClient($mission->getClient());
            $facture->setEtat('non payé');

            $em->persist($facture);
            $em->flush();

            if ($mission->getDevise() == 'DH') {


                $totalHT_hs = null;
                $totalTTC_hs = null;
                $totalHT_hs_fournisseur = null;
                $totalTTC_hs_fournisseur = null;
                $nb_total_jrs = null;

                $heures = $form->get('facturehsups')->getData();
                if (!empty($heures)) {
                    $totalHT_hs = null;
                    $totalTTC_hs = null;
                    $totalHT_hs_fournisseur = null;
                    $totalTTC_hs_fournisseur = null;
                    $nb_total_jrs = null;
                    foreach ($heures as $heure) {

                        $nb_jour_sup = $heure->getNbheure() / 10;
                        $heure->setNbjour($nb_jour_sup);
                        $heure->setFacture($facture);
                        $heure->setBcfournisseur(null);
//                    $facture->addFacturehsup($heure);
                        $heuresup = $heure->getHeuresup();
                        $heure->setTotalHT($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixVente());
                        $heure->setTotalTTC(($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixVente()) * 1.2);
                        $totalHT_hs += ($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixVente());
                        $totalTTC_hs += $nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixVente() * 1.2;
                        $totalHT_hs_fournisseur += ($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixAchat());
                        $totalTTC_hs_fournisseur += $nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixAchat() * 1.2;
                        $nb_total_jrs += $heure->getNbheure() / 10;

                        $heure_bc = new FactureHsup();
                        $heure_bc->setBcfournisseur($bcfournisseur);
                        $heure_bc->setFacturefournisseur($facturefournisseur);
//                    $heure_bc->setFacture($facture);
                        $heure_bc->setNbjour($nb_jour_sup);
                        $heure_bc->setHeuresup($heure->getHeuresup());
                        $heure_bc->setTotalHT($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixAchat());
                        $heure_bc->setTotalTTC(($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixAchat()) * 1.2);
                        $heure_bc->setNbheure($heure->getNbheure());
                        $bcfournisseur->addHeure($heure_bc);
                        $facturefournisseur->addHeure($heure_bc);
                        $em->persist($heure);
                        $em->flush();

                    }
                } else {
                    $totalHT_hs = null;
                    $totalTTC_hs = null;
                    $totalHT_hs_fournisseur = null;
                    $totalTTC_hs_fournisseur = null;
                    $nb_total_jrs = null;

                }


                $facture = $em->getRepository('AppBundle:Facture')->find($facture->getId());
                $facture->setTotalHT($facture->getTotalHT() + $totalHT_hs);
                $facture->setTotalTTC(($facture->getTotalHT()) * 1.2);

                $bcfournisseur->setFournisseur($facture->getMission()->getFournisseur());
//            $bcfournisseur->setNbjours($facture->getNbjour());
                $bcfournisseur->setMois($facture->getMois());
                $bcfournisseur->setYear($facture->getYear());
                $bcfournisseur->setDate(new \DateTime('now'));
                $facturefournisseur->setFournisseur($facture->getMission()->getFournisseur());
                $facturefournisseur->setNbjours($facture->getNbjour() + $nb_total_jrs);
                $bcfournisseur->setNbjours($facture->getNbjour() + $nb_total_jrs);

                $facturefournisseur->setMois($facture->getMois());
                $facturefournisseur->setYear($facture->getYear());
                $facturefournisseur->setDate(new \DateTime('now'));
                $facturefournisseur->setBcfournisseur($bcfournisseur);


                $em->persist($bcfournisseur);
                $em->flush();
                $em->persist($facturefournisseur);
                $em->flush();
                $bcfournisseur->setVenteHT($facture->getTotalHT());
//            $facturefournisseur->setVenteHT($facture->getTotalHT());
                $bcfournisseur->setAchatHT($bcfournisseur->getAchatHT() + $totalHT_hs_fournisseur);
                $bcfournisseur->setAchatTTC($bcfournisseur->getAchatTTC() + $totalTTC_hs_fournisseur);
                $facturefournisseur->setAchatHT($facturefournisseur->getAchatHT() + $totalHT_hs_fournisseur);
                $bcfournisseur->setAchatTTC($facturefournisseur->getAchatTTC() + $totalTTC_hs_fournisseur);
                $facturefournisseur->setAchatTTC(($facturefournisseur->getAchatHT() + $totalTTC_hs_fournisseur) * 1.2);
                $bcfournisseur->setTaxe($bcfournisseur->getAchatTTC() - $bcfournisseur->getAchatHT());
                $facturefournisseur->setTaxe($facturefournisseur->getAchatTTC() - $facturefournisseur->getAchatHT());
                $em->persist($bcfournisseur);
                $em->flush();
                $em->persist($facturefournisseur);
                $em->flush();
                $production = new Production();
                $production->setConsultant($mission->getConsultant());
                $production->setClient($mission->getClient());
                $production->setNbjour($facturefournisseur->getNbjours());
                $production->setAchatHT($bcfournisseur->getAchatHT());
                $production->setAchatTTC($bcfournisseur->getAchatHT() * 1.2);
                $production->setFournisseur($bcfournisseur->getFournisseur());
                $production->setVenteHT($facture->getTotalHT());
                $production->setVenteTTC($facture->getTotalHT() * 1.2);
                $production->setMission($mission);

                $production->setMois($facture->getMois());
                $production->setTjmVente($mission->getPrixVente());
                $production->setTjmAchat($mission->getPrixAchat());
                $production->setYear($facture->getYear());
                $production->setFacture($facture);

                $em->persist($production);
                $em->flush();
                $facture->setEtat('non payé');
                $em->persist($facture);
                $em->flush();
            }

//            dump($facture, $bcfournisseur, $facturefournisseur, $production);

            return $this->redirectToRoute('facture_show', array('id' => $facture->getId()));
        }

        return $this->render('facture/facture_mission.html.twig', array(
            'facture' => $facture,
            'mission' => $mission,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{id}", name="facture_show")
     * @Method("GET")
     */
    public function showAction(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();
        $fiche = $em->getRepository('AppBundle:Fiche')->find(1);
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

//        dump($facture);
        /*function int2str($a)
        {
            $convert = explode('.', $a);
            if (isset($convert[1]) && $convert[1] != '') {
                return int2str($convert[0]) . 'Dinars' . ' et ' . int2str($convert[1]) . 'Centimes';
            }
            if ($a < 0) return 'moins ' . int2str(-$a);
            if ($a < 17) {
                switch ($a) {
                    case 0:
                        return 'zero';
                    case 1:
                        return 'un';
                    case 2:
                        return 'deux';
                    case 3:
                        return 'trois';
                    case 4:
                        return 'quatre';
                    case 5:
                        return 'cinq';
                    case 6:
                        return 'six';
                    case 7:
                        return 'sept';
                    case 8:
                        return 'huit';
                    case 9:
                        return 'neuf';
                    case 10:
                        return 'dix';
                    case 11:
                        return 'onze';
                    case 12:
                        return 'douze';
                    case 13:
                        return 'treize';
                    case 14:
                        return 'quatorze';
                    case 15:
                        return 'quinze';
                    case 16:
                        return 'seize';
                }
            } else if ($a < 20) {
                return 'dix-' . int2str($a - 10);
            } else if ($a < 100) {
                if ($a % 10 == 0) {
                    switch ($a) {
                        case 20:
                            return 'vingt';
                        case 30:
                            return 'trente';
                        case 40:
                            return 'quarante';
                        case 50:
                            return 'cinquante';
                        case 60:
                            return 'soixante';
                        case 70:
                            return 'soixante-dix';
                        case 80:
                            return 'quatre-vingt';
                        case 90:
                            return 'quatre-vingt-dix';
                    }
                } elseif (substr($a, -1) == 1) {
                    if (((int)($a / 10) * 10) < 70) {
                        return int2str((int)($a / 10) * 10) . '-et-un';
                    } elseif ($a == 71) {
                        return 'soixante-et-onze';
                    } elseif ($a == 81) {
                        return 'quatre-vingt-un';
                    } elseif ($a == 91) {
                        return 'quatre-vingt-onze';
                    }
                } elseif ($a < 70) {
                    return int2str($a - $a % 10) . '-' . int2str($a % 10);
                } elseif ($a < 80) {
                    return int2str(60) . '-' . int2str($a % 20);
                } else {
                    return int2str(80) . '-' . int2str($a % 20);
                }
            } else if ($a == 100) {
                return 'cent';
            } else if ($a < 200) {
                return int2str(100) . ' ' . int2str($a % 100);
            } else if ($a < 1000) {
                return int2str((int)($a / 100)) . ' ' . int2str(100) . ' ' . int2str($a % 100);
            } else if ($a == 1000) {
                return 'mille';
            } else if ($a < 2000) {
                return int2str(1000) . ' ' . int2str($a % 1000) . ' ';
            } else if ($a < 1000000) {
                return int2str((int)($a / 1000)) . ' ' . int2str(1000) . ' ' . int2str($a % 1000);
            } else if ($a == 1000000) {
                return 'millions';
            } else if ($a < 2000000) {
                return int2str(1000000) . ' ' . int2str($a % 1000000) . ' ';
            } else if ($a < 1000000000) {
                return int2str((int)($a / 1000000)) . ' ' . int2str(1000000) . ' ' . int2str($a % 1000000);
            }
        }*/
        function int2str($a)
        {
            if ($a < 17) {
                switch ($a) {
                    case 1:
                        return 'UN';
                    case 2:
                        return 'DEUX';
                    case 3:
                        return 'TROIS';
                    case 4:
                        return 'QUATRE';
                    case 5:
                        return 'CINQ';
                    case 6:
                        return 'SIX';
                    case 7:
                        return 'SEPT';
                    case 8:
                        return 'HUIT';
                    case 9:
                        return 'NEUF';
                    case 10:
                        return 'DIX';
                    case 11:
                        return 'ONZE';
                    case 12:
                        return 'DOUZE';
                    case 13:
                        return 'TREIZE';
                    case 14:
                        return 'QUATORZE';
                    case 15:
                        return 'QUINZE';
                    case 16:
                        return 'SEIZE';
                }
            } else {
                if ($a < 20) {
                    return 'DIX-' . int2str($a - 10);
                } else {
                    if ($a < 100) {
                        if ($a % 10 == 0) {
                            switch ($a) {
                                case 20:
                                    return 'VINGT';
                                case 30:
                                    return 'TRENTE';
                                case 40:
                                    return 'QUARANTE';
                                case 50:
                                    return 'CINQUANTE';
                                case 60:
                                    return 'SOIXANTE';
                                case 70:
                                    return 'SOINXANTE-DIX';
                                case 80:
                                    return 'QUATRE-VINGT';
                                case 90:
                                    return 'QUATRE-VINGT-DIX';
                            }
                        } elseif (substr($a, -1) == 1) {
                            if ((int)($a / 10) * 10 < 70) {
                                return int2str((int)($a / 10) * 10) . '-ET-UN';
                            } elseif ($a == 71) {
                                return 'SOIXANTE ET ONZE';
                            } elseif ($a == 81) {
                                return 'QUATRE VINGT UN';
                            } elseif ($a == 91) {
                                return 'QUATRE VINGT ONZE';
                            }
                        } elseif ($a < 70) {
                            return int2str($a - $a % 10) . '-' . int2str($a % 10);
                        } elseif ($a < 80) {
                            return int2str(60) . '-' . int2str($a % 20);
                        } else {
                            return int2str(80) . '-' . int2str($a % 20);
                        }
                    } else {
                        if ($a == 100) {
                            return 'CENT';
                        } else {
                            if ($a < 200) {
                                return int2str(100) . ' ' . int2str($a % 100);
                            } else {
                                if ($a < 1000) {
                                    return int2str((int)($a / 100)) . ' ' . int2str(100) . ' ' . int2str($a % 100);
                                } else {
                                    if ($a == 1000) {
                                        return 'MILLE';
                                    } else {
                                        if ($a < 2000) {
                                            return int2str(1000) . ' ' . int2str($a % 1000) . ' ';
                                        } else {
                                            if ($a < 1000000) {
                                                return int2str((int)($a / 1000)) . ' ' . int2str(1000) . ' ' . int2str($a % 1000);
                                            } else {
                                                if ($a == 1000000) {
                                                    return 'MILLION';
                                                } else {
                                                    if ($a < 2000000) {
                                                        return int2str(1000000) . ' ' . int2str($a % 1000000) . ' ';
                                                    } else {
                                                        if ($a < 1000000000) {
                                                            return int2str((int)($a / 1000000)) . ' ' . int2str(1000000) . ' ' . int2str($a % 1000000);
                                                        } else {
                                                            if ($a == 1000000000) {
                                                                return 'MILLIARD';
                                                            } else {
                                                                if ($a < 2000000000) {
                                                                    return int2str(1000000000) . ' ' . int2str($a % 1000000000) . ' ';
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $deleteForm = $this->createDeleteForm($facture);
//dump($facture);
        return $this->render('facture/show.html.twig', array(
            'facture' => $facture,
            'delete_form' => $deleteForm->createView(),
            'total' => int2str($facture->getTotalTTC()),
            'mois' => mois_convert($facture->getMois()),
            'fiche' => $fiche

        ));
    }

    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{id}/paye", name="facture_payer")
     * @Method("GET")
     */
    public function setPayedAction(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();

        $facture->setEtat('payé');
        $em->persist($facture);
        $em->flush();


        return $this->redirectToRoute('facture_index');
    }

    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{id}/change", name="facture_change",options={"expose"=true})
     * @Method("GET")
     */
    public function changeAction(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();

        $facture->setEtat('payé');
        $em->persist($facture);
        $em->flush();


        return $this->redirectToRoute('facture_index');
    }

    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{id}/print", name="facture_print")
     * @Method("GET")
     */
    public function showfactureAction(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();
        $fiche = $em->getRepository('AppBundle:Fiche')->find(1);

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

        /* function int2str($a)
         {
             $convert = explode('.', $a);
             if (isset($convert[1]) && $convert[1] != '') {
                 return int2str($convert[0]) . 'Dinars' . ' et ' . int2str($convert[1]) . 'Centimes';
             }
             if ($a < 0) return 'moins ' . int2str(-$a);
             if ($a < 17) {
                 switch ($a) {
                     case 0:
                         return 'zero';
                     case 1:
                         return 'un';
                     case 2:
                         return 'deux';
                     case 3:
                         return 'trois';
                     case 4:
                         return 'quatre';
                     case 5:
                         return 'cinq';
                     case 6:
                         return 'six';
                     case 7:
                         return 'sept';
                     case 8:
                         return 'huit';
                     case 9:
                         return 'neuf';
                     case 10:
                         return 'dix';
                     case 11:
                         return 'onze';
                     case 12:
                         return 'douze';
                     case 13:
                         return 'treize';
                     case 14:
                         return 'quatorze';
                     case 15:
                         return 'quinze';
                     case 16:
                         return 'seize';
                 }
             } else if ($a < 20) {
                 return 'dix-' . int2str($a - 10);
             } else if ($a < 100) {
                 if ($a % 10 == 0) {
                     switch ($a) {
                         case 20:
                             return 'vingt';
                         case 30:
                             return 'trente';
                         case 40:
                             return 'quarante';
                         case 50:
                             return 'cinquante';
                         case 60:
                             return 'soixante';
                         case 70:
                             return 'soixante-dix';
                         case 80:
                             return 'quatre-vingt';
                         case 90:
                             return 'quatre-vingt-dix';
                     }
                 } elseif (substr($a, -1) == 1) {
                     if (((int)($a / 10) * 10) < 70) {
                         return int2str((int)($a / 10) * 10) . '-et-un';
                     } elseif ($a == 71) {
                         return 'soixante-et-onze';
                     } elseif ($a == 81) {
                         return 'quatre-vingt-un';
                     } elseif ($a == 91) {
                         return 'quatre-vingt-onze';
                     }
                 } elseif ($a < 70) {
                     return int2str($a - $a % 10) . '-' . int2str($a % 10);
                 } elseif ($a < 80) {
                     return int2str(60) . '-' . int2str($a % 20);
                 } else {
                     return int2str(80) . '-' . int2str($a % 20);
                 }
             } else if ($a == 100) {
                 return 'cent';
             } else if ($a < 200) {
                 return int2str(100) . ' ' . int2str($a % 100);
             } else if ($a < 1000) {
                 return int2str((int)($a / 100)) . ' ' . int2str(100) . ' ' . int2str($a % 100);
             } else if ($a == 1000) {
                 return 'mille';
             } else if ($a < 2000) {
                 return int2str(1000) . ' ' . int2str($a % 1000) . ' ';
             } else if ($a < 1000000) {
                 return int2str((int)($a / 1000)) . ' ' . int2str(1000) . ' ' . int2str($a % 1000);
             } else if ($a == 1000000) {
                 return 'millions';
             } else if ($a < 2000000) {
                 return int2str(1000000) . ' ' . int2str($a % 1000000) . ' ';
             } else if ($a < 1000000000) {
                 return int2str((int)($a / 1000000)) . ' ' . int2str(1000000) . ' ' . int2str($a % 1000000);
             }
         }*/
        function int2str($a)
        {
            if ($a < 17) {
                switch ($a) {
                    case 1:
                        return 'UN';
                    case 2:
                        return 'DEUX';
                    case 3:
                        return 'TROIS';
                    case 4:
                        return 'QUATRE';
                    case 5:
                        return 'CINQ';
                    case 6:
                        return 'SIX';
                    case 7:
                        return 'SEPT';
                    case 8:
                        return 'HUIT';
                    case 9:
                        return 'NEUF';
                    case 10:
                        return 'DIX';
                    case 11:
                        return 'ONZE';
                    case 12:
                        return 'DOUZE';
                    case 13:
                        return 'TREIZE';
                    case 14:
                        return 'QUATORZE';
                    case 15:
                        return 'QUINZE';
                    case 16:
                        return 'SEIZE';
                }
            } else {
                if ($a < 20) {
                    return 'DIX-' . int2str($a - 10);
                } else {
                    if ($a < 100) {
                        if ($a % 10 == 0) {
                            switch ($a) {
                                case 20:
                                    return 'VINGT';
                                case 30:
                                    return 'TRENTE';
                                case 40:
                                    return 'QUARANTE';
                                case 50:
                                    return 'CINQUANTE';
                                case 60:
                                    return 'SOIXANTE';
                                case 70:
                                    return 'SOINXANTE-DIX';
                                case 80:
                                    return 'QUATRE-VINGT';
                                case 90:
                                    return 'QUATRE-VINGT-DIX';
                            }
                        } elseif (substr($a, -1) == 1) {
                            if ((int)($a / 10) * 10 < 70) {
                                return int2str((int)($a / 10) * 10) . '-ET-UN';
                            } elseif ($a == 71) {
                                return 'SOIXANTE ET ONZE';
                            } elseif ($a == 81) {
                                return 'QUATRE VINGT UN';
                            } elseif ($a == 91) {
                                return 'QUATRE VINGT ONZE';
                            }
                        } elseif ($a < 70) {
                            return int2str($a - $a % 10) . '-' . int2str($a % 10);
                        } elseif ($a < 80) {
                            return int2str(60) . '-' . int2str($a % 20);
                        } else {
                            return int2str(80) . '-' . int2str($a % 20);
                        }
                    } else {
                        if ($a == 100) {
                            return 'CENT';
                        } else {
                            if ($a < 200) {
                                return int2str(100) . ' ' . int2str($a % 100);
                            } else {
                                if ($a < 1000) {
                                    return int2str((int)($a / 100)) . ' ' . int2str(100) . ' ' . int2str($a % 100);
                                } else {
                                    if ($a == 1000) {
                                        return 'MILLE';
                                    } else {
                                        if ($a < 2000) {
                                            return int2str(1000) . ' ' . int2str($a % 1000) . ' ';
                                        } else {
                                            if ($a < 1000000) {
                                                return int2str((int)($a / 1000)) . ' ' . int2str(1000) . ' ' . int2str($a % 1000);
                                            } else {
                                                if ($a == 1000000) {
                                                    return 'MILLION';
                                                } else {
                                                    if ($a < 2000000) {
                                                        return int2str(1000000) . ' ' . int2str($a % 1000000) . ' ';
                                                    } else {
                                                        if ($a < 1000000000) {
                                                            return int2str((int)($a / 1000000)) . ' ' . int2str(1000000) . ' ' . int2str($a % 1000000);
                                                        } else {
                                                            if ($a == 1000000000) {
                                                                return 'MILLIARD';
                                                            } else {
                                                                if ($a < 2000000000) {
                                                                    return int2str(1000000000) . ' ' . int2str($a % 1000000000) . ' ';
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->render('facture/print.html.twig', array(
            'facture' => $facture,
            'total' => int2str($facture->getTotalTTC()),
            'mois' => mois_convert($facture->getMois()),
            'fiche' => $fiche
            // 'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{id}/print_hs", name="facture_print_hs")
     * @Method("GET")
     */
    public function showhsfactureAction(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();
        $fiche = $em->getRepository('AppBundle:Fiche')->find(1);

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

        /* function int2str($a)
         {
             $convert = explode('.', $a);
             if (isset($convert[1]) && $convert[1] != '') {
                 return int2str($convert[0]) . 'Dinars' . ' et ' . int2str($convert[1]) . 'Centimes';
             }
             if ($a < 0) return 'moins ' . int2str(-$a);
             if ($a < 17) {
                 switch ($a) {
                     case 0:
                         return 'zero';
                     case 1:
                         return 'un';
                     case 2:
                         return 'deux';
                     case 3:
                         return 'trois';
                     case 4:
                         return 'quatre';
                     case 5:
                         return 'cinq';
                     case 6:
                         return 'six';
                     case 7:
                         return 'sept';
                     case 8:
                         return 'huit';
                     case 9:
                         return 'neuf';
                     case 10:
                         return 'dix';
                     case 11:
                         return 'onze';
                     case 12:
                         return 'douze';
                     case 13:
                         return 'treize';
                     case 14:
                         return 'quatorze';
                     case 15:
                         return 'quinze';
                     case 16:
                         return 'seize';
                 }
             } else if ($a < 20) {
                 return 'dix-' . int2str($a - 10);
             } else if ($a < 100) {
                 if ($a % 10 == 0) {
                     switch ($a) {
                         case 20:
                             return 'vingt';
                         case 30:
                             return 'trente';
                         case 40:
                             return 'quarante';
                         case 50:
                             return 'cinquante';
                         case 60:
                             return 'soixante';
                         case 70:
                             return 'soixante-dix';
                         case 80:
                             return 'quatre-vingt';
                         case 90:
                             return 'quatre-vingt-dix';
                     }
                 } elseif (substr($a, -1) == 1) {
                     if (((int)($a / 10) * 10) < 70) {
                         return int2str((int)($a / 10) * 10) . '-et-un';
                     } elseif ($a == 71) {
                         return 'soixante-et-onze';
                     } elseif ($a == 81) {
                         return 'quatre-vingt-un';
                     } elseif ($a == 91) {
                         return 'quatre-vingt-onze';
                     }
                 } elseif ($a < 70) {
                     return int2str($a - $a % 10) . '-' . int2str($a % 10);
                 } elseif ($a < 80) {
                     return int2str(60) . '-' . int2str($a % 20);
                 } else {
                     return int2str(80) . '-' . int2str($a % 20);
                 }
             } else if ($a == 100) {
                 return 'cent';
             } else if ($a < 200) {
                 return int2str(100) . ' ' . int2str($a % 100);
             } else if ($a < 1000) {
                 return int2str((int)($a / 100)) . ' ' . int2str(100) . ' ' . int2str($a % 100);
             } else if ($a == 1000) {
                 return 'mille';
             } else if ($a < 2000) {
                 return int2str(1000) . ' ' . int2str($a % 1000) . ' ';
             } else if ($a < 1000000) {
                 return int2str((int)($a / 1000)) . ' ' . int2str(1000) . ' ' . int2str($a % 1000);
             } else if ($a == 1000000) {
                 return 'millions';
             } else if ($a < 2000000) {
                 return int2str(1000000) . ' ' . int2str($a % 1000000) . ' ';
             } else if ($a < 1000000000) {
                 return int2str((int)($a / 1000000)) . ' ' . int2str(1000000) . ' ' . int2str($a % 1000000);
             }
         }*/
        function int2str($a)
        {
            if ($a < 17) {
                switch ($a) {
                    case 1:
                        return 'UN';
                    case 2:
                        return 'DEUX';
                    case 3:
                        return 'TROIS';
                    case 4:
                        return 'QUATRE';
                    case 5:
                        return 'CINQ';
                    case 6:
                        return 'SIX';
                    case 7:
                        return 'SEPT';
                    case 8:
                        return 'HUIT';
                    case 9:
                        return 'NEUF';
                    case 10:
                        return 'DIX';
                    case 11:
                        return 'ONZE';
                    case 12:
                        return 'DOUZE';
                    case 13:
                        return 'TREIZE';
                    case 14:
                        return 'QUATORZE';
                    case 15:
                        return 'QUINZE';
                    case 16:
                        return 'SEIZE';
                }
            } else {
                if ($a < 20) {
                    return 'DIX-' . int2str($a - 10);
                } else {
                    if ($a < 100) {
                        if ($a % 10 == 0) {
                            switch ($a) {
                                case 20:
                                    return 'VINGT';
                                case 30:
                                    return 'TRENTE';
                                case 40:
                                    return 'QUARANTE';
                                case 50:
                                    return 'CINQUANTE';
                                case 60:
                                    return 'SOIXANTE';
                                case 70:
                                    return 'SOINXANTE-DIX';
                                case 80:
                                    return 'QUATRE-VINGT';
                                case 90:
                                    return 'QUATRE-VINGT-DIX';
                            }
                        } elseif (substr($a, -1) == 1) {
                            if ((int)($a / 10) * 10 < 70) {
                                return int2str((int)($a / 10) * 10) . '-ET-UN';
                            } elseif ($a == 71) {
                                return 'SOIXANTE ET ONZE';
                            } elseif ($a == 81) {
                                return 'QUATRE VINGT UN';
                            } elseif ($a == 91) {
                                return 'QUATRE VINGT ONZE';
                            }
                        } elseif ($a < 70) {
                            return int2str($a - $a % 10) . '-' . int2str($a % 10);
                        } elseif ($a < 80) {
                            return int2str(60) . '-' . int2str($a % 20);
                        } else {
                            return int2str(80) . '-' . int2str($a % 20);
                        }
                    } else {
                        if ($a == 100) {
                            return 'CENT';
                        } else {
                            if ($a < 200) {
                                return int2str(100) . ' ' . int2str($a % 100);
                            } else {
                                if ($a < 1000) {
                                    return int2str((int)($a / 100)) . ' ' . int2str(100) . ' ' . int2str($a % 100);
                                } else {
                                    if ($a == 1000) {
                                        return 'MILLE';
                                    } else {
                                        if ($a < 2000) {
                                            return int2str(1000) . ' ' . int2str($a % 1000) . ' ';
                                        } else {
                                            if ($a < 1000000) {
                                                return int2str((int)($a / 1000)) . ' ' . int2str(1000) . ' ' . int2str($a % 1000);
                                            } else {
                                                if ($a == 1000000) {
                                                    return 'MILLION';
                                                } else {
                                                    if ($a < 2000000) {
                                                        return int2str(1000000) . ' ' . int2str($a % 1000000) . ' ';
                                                    } else {
                                                        if ($a < 1000000000) {
                                                            return int2str((int)($a / 1000000)) . ' ' . int2str(1000000) . ' ' . int2str($a % 1000000);
                                                        } else {
                                                            if ($a == 1000000000) {
                                                                return 'MILLIARD';
                                                            } else {
                                                                if ($a < 2000000000) {
                                                                    return int2str(1000000000) . ' ' . int2str($a % 1000000000) . ' ';
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->render('facture/print_hs.html.twig', array(
            'facture' => $facture,
            'total' => int2str($facture->getTotalTTC()),
            'mois' => mois_convert($facture->getMois()),
            'fiche' => $fiche
            // 'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{id}/print_projet", name="facture_print_projet")
     * @Method("GET")
     */
    public function printFactureProjetAction(Facture $facture)
    {

//        dump($facture);
        $em = $this->getDoctrine()->getManager();
        $fiche = $em->getRepository('AppBundle:Fiche')->find(1);
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


        function int2str($a)
        {
            if ($a < 17) {
                switch ($a) {
                    case 1:
                        return 'UN';
                    case 2:
                        return 'DEUX';
                    case 3:
                        return 'TROIS';
                    case 4:
                        return 'QUATRE';
                    case 5:
                        return 'CINQ';
                    case 6:
                        return 'SIX';
                    case 7:
                        return 'SEPT';
                    case 8:
                        return 'HUIT';
                    case 9:
                        return 'NEUF';
                    case 10:
                        return 'DIX';
                    case 11:
                        return 'ONZE';
                    case 12:
                        return 'DOUZE';
                    case 13:
                        return 'TREIZE';
                    case 14:
                        return 'QUATORZE';
                    case 15:
                        return 'QUINZE';
                    case 16:
                        return 'SEIZE';
                }
            } else {
                if ($a < 20) {
                    return 'DIX-' . int2str($a - 10);
                } else {
                    if ($a < 100) {
                        if ($a % 10 == 0) {
                            switch ($a) {
                                case 20:
                                    return 'VINGT';
                                case 30:
                                    return 'TRENTE';
                                case 40:
                                    return 'QUARANTE';
                                case 50:
                                    return 'CINQUANTE';
                                case 60:
                                    return 'SOIXANTE';
                                case 70:
                                    return 'SOINXANTE-DIX';
                                case 80:
                                    return 'QUATRE-VINGT';
                                case 90:
                                    return 'QUATRE-VINGT-DIX';
                            }
                        } elseif (substr($a, -1) == 1) {
                            if ((int)($a / 10) * 10 < 70) {
                                return int2str((int)($a / 10) * 10) . '-ET-UN';
                            } elseif ($a == 71) {
                                return 'SOIXANTE ET ONZE';
                            } elseif ($a == 81) {
                                return 'QUATRE VINGT UN';
                            } elseif ($a == 91) {
                                return 'QUATRE VINGT ONZE';
                            }
                        } elseif ($a < 70) {
                            return int2str($a - $a % 10) . '-' . int2str($a % 10);
                        } elseif ($a < 80) {
                            return int2str(60) . '-' . int2str($a % 20);
                        } else {
                            return int2str(80) . '-' . int2str($a % 20);
                        }
                    } else {
                        if ($a == 100) {
                            return 'CENT';
                        } else {
                            if ($a < 200) {
                                return int2str(100) . ' ' . int2str($a % 100);
                            } else {
                                if ($a < 1000) {
                                    return int2str((int)($a / 100)) . ' ' . int2str(100) . ' ' . int2str($a % 100);
                                } else {
                                    if ($a == 1000) {
                                        return 'MILLE';
                                    } else {
                                        if ($a < 2000) {
                                            return int2str(1000) . ' ' . int2str($a % 1000) . ' ';
                                        } else {
                                            if ($a < 1000000) {
                                                return int2str((int)($a / 1000)) . ' ' . int2str(1000) . ' ' . int2str($a % 1000);
                                            } else {
                                                if ($a == 1000000) {
                                                    return 'MILLION';
                                                } else {
                                                    if ($a < 2000000) {
                                                        return int2str(1000000) . ' ' . int2str($a % 1000000) . ' ';
                                                    } else {
                                                        if ($a < 1000000000) {
                                                            return int2str((int)($a / 1000000)) . ' ' . int2str(1000000) . ' ' . int2str($a % 1000000);
                                                        } else {
                                                            if ($a == 1000000000) {
                                                                return 'MILLIARD';
                                                            } else {
                                                                if ($a < 2000000000) {
                                                                    return int2str(1000000000) . ' ' . int2str($a % 1000000000) . ' ';
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // AND l.nbjour>0 AND l.totalHt>0
        // orange
        if ($facture->getProjet()->getClient()->getNom() == 'MEDI TELECOM') {
            $items = $em->createQuery('
          SELECT p as ligne,SUM (l.nbjourVente) AS nbjours, SUM(l.totalHt) as total,SUM(l.totalTTC) as totalTTC ,p.vente as tjm   
          From AppBundle:LigneFacture l
          JOIN AppBundle:Projetconsultant p
          WHERE l.facture = :facture
          AND l.projetconsultant = p.id
         
          GROUP BY p.job
         ')->setParameter('facture', $facture)->execute();

//  dump($items);
//            die();
            return $this->render('facture/print_orange.html.twig', array(
                'facture' => $facture,
                'total' => int2str($facture->getTotalTTC()),
                'mois' => mois_convert($facture->getMois()),
                'fiche' => $fiche,
                'items' => $items

            ));
        }

        if ($facture->getProjet()->getClient()->getId() == 14) {
            //Pcs
            $items = $em->createQuery('
          SELECT IDENTITY(p.job) as job,p as ligne,SUM (l.nbjour) as nbjours, SUM (l.totalHt) as total,SUM (l.totalTTC) as totalTTC ,p.vente as tjm    
          From AppBundle:LigneFacture l
          JOIN AppBundle:Projetconsultant p
          
          WHERE l.facture = :facture
         
          AND l.projetconsultant = p.id
          AND l.nbjour>0 AND l.totalHt>0
          GROUP By tjm
                    
          ')->setParameter('facture', $facture)->execute();
//            dump($items);
            return $this->render('facture/print_pcs.html.twig', array(
                'facture' => $facture,
                'total' => int2str($facture->getTotalTTC()),
                'mois' => mois_convert($facture->getMois()),
                'fiche' => $fiche,
                'items' => $items

            ));

        } else {

            //Other clients
            $items = $em->createQuery('
          SELECT p as ligne,l.nbjour as nbjours, l.totalHt as total,l.totalTTC as totalTTC   From AppBundle:LigneFacture l
          JOIN AppBundle:Projetconsultant p
          WHERE l.facture = :facture
          AND l.projetconsultant = p.id
          
                    
          ')->setParameter('facture', $facture)->execute();
//            dump($items);
            return $this->render('facture/print_projet.html.twig', array(
                'facture' => $facture,
                'total' => int2str($facture->getTotalTTC()),
                'mois' => mois_convert($facture->getMois()),
                'fiche' => $fiche,
                'items' => $items

            ));
        }


    }

    /**
     * Displays a form to edit an existing facture entity.
     *
     * @Route("/{id}/edit_hs", name="facture_edit_hs")
     * @Method({"GET", "POST"})
     */
    public function editHsupAction(Request $request, Facture $facture)
    {
        if ($facture->getId() << 140) {

            return $this->redirectToRoute('facture_index');
        }
//        dump($facture->getFacturehsups()->count());
//        $deleteForm = $this->createDeleteForm($facture);
        $editForm = $this->createForm('AppBundle\Form\FactureType', $facture);
        $editForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $totalHT_hs = null;
            $totalTTC_hs = null;
            $totalHT_hs_fournisseur = null;
            $totalTTC_hs_fournisseur = null;
            $nb_total_jrs = null;

            $heures = $editForm->get('facturehsups')->getData();
            $nbjours = $editForm->get('nbjour')->getData();
            if (!empty($heures)) {
                $totalHT_hs = null;
                $totalTTC_hs = null;
                $totalHT_hs_fournisseur = null;
                $totalTTC_hs_fournisseur = null;
                $nb_total_jrs = null;
                $mission = $facture->getMission();
                foreach ($heures as $heure) {

                    $nb_jour_sup = $heure->getNbheure() / 10;
                    $heure->setNbjour($nb_jour_sup);
                    $heure->setFacture($facture);
                    $heure->setBcfournisseur(null);
//                    $facture->addFacturehsup($heure);
                    $heuresup = $heure->getHeuresup();
                    $heure->setTotalHT($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixVente());
                    $heure->setTotalTTC(($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixVente()) * 1.2);
                    $totalHT_hs += ($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixVente());
                    $totalTTC_hs += $nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixVente() * 1.2;
                    $totalHT_hs_fournisseur += ($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixAchat());
                    $totalTTC_hs_fournisseur += $nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixAchat() * 1.2;
                    $nb_total_jrs += $heure->getNbheure() / 10;
                    $bcfournisseur = $em->getRepository('AppBundle:Bcfournisseur')->findOneBy(array(
                        'consultant' => $facture->getConsultant(),
                        'mois' => $facture->getMois(),
//                        'facture' => $facture,

                    ));
                    $facturefournisseur = $em->getRepository('AppBundle:Facturefournisseur')->findOneBy([
                        'consultant' => $facture->getConsultant(),
                        'mois' => $facture->getMois(),
                        'facture' => $facture,

                    ]);
                    $heure_bc = $em->getRepository('AppBundle:FactureHsup')->findOneBy([
                        //    'facture' => $facture,
                        'heuresup' => $heuresup,
                        'bcfournisseur' => $bcfournisseur,

                    ]);

                    $heure_bc->setBcfournisseur($bcfournisseur);
                    $heure_bc->setFacturefournisseur($facturefournisseur);
//                    $heure_bc->setFacture($facture);
                    $heure_bc->setNbjour($nb_jour_sup);
                    $heure_bc->setHeuresup($heure->getHeuresup());

                    $heure_bc->setTotalHT(0);
                    $heure_bc->setTotalHT($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixAchat());
                    $heure_bc->setTotalTTC(($nb_jour_sup * ($heuresup->getPourcentage() / 100 + 1) * $mission->getPrixAchat()) * 1.2);
                    $heure_bc->setNbheure($heure->getNbheure());
                    $bcfournisseur->addHeure($heure_bc);
                    $facturefournisseur->addHeure($heure_bc);
                    $em->persist($heure);
                    $em->flush();

                }
            } else {
                $totalHT_hs = null;
                $totalTTC_hs = null;
                $totalHT_hs_fournisseur = null;
                $totalTTC_hs_fournisseur = null;
                $nb_total_jrs = null;

            }


            $facture = $em->getRepository('AppBundle:Facture')->find($facture->getId());
            $facture->setTotalHT($nbjours * $facture->getMission()->getPrixVente() + $totalHT_hs);
            $facture->setTotalTTC(($facture->getTotalHT()) * 1.2);

            $bcfournisseur->setFournisseur($facture->getMission()->getFournisseur());
//            $bcfournisseur->setNbjours($facture->getNbjour());
            $bcfournisseur->setMois($facture->getMois());
            $bcfournisseur->setYear($facture->getYear());
            $bcfournisseur->setDate(new \DateTime('now'));
            $facturefournisseur->setFournisseur($facture->getMission()->getFournisseur());
            $facturefournisseur->setNbjours($nbjours + $nb_total_jrs);
            $bcfournisseur->setNbjours($nbjours + $nb_total_jrs);

            $facturefournisseur->setMois($facture->getMois());
            $facturefournisseur->setYear($facture->getYear());
            $facturefournisseur->setDate(new \DateTime('now'));
            $facturefournisseur->setBcfournisseur($bcfournisseur);


            $em->persist($bcfournisseur);
            $em->flush();
            $em->persist($facturefournisseur);
            $em->flush();
            $bcfournisseur->setVenteHT($facture->getTotalHT());
//            $facturefournisseur->setVenteHT($facture->getTotalHT());
            $bcfournisseur->setAchatHT($nbjours * $facture->getMission()->getPrixAchat() + $totalHT_hs_fournisseur);
            $bcfournisseur->setAchatTTC($bcfournisseur->getAchatHT() * 1.2);

            $facturefournisseur->setAchatHT($nbjours * $facture->getMission()->getPrixVente() + $totalHT_hs_fournisseur);
            $facturefournisseur->setAchatTTC($facturefournisseur->getAchatHT() * 1.2);
//            $facturefournisseur->setAchatTTC(($facturefournisseur->getAchatHT() + $totalTTC_hs_fournisseur) * 1.2);
            $bcfournisseur->setTaxe($bcfournisseur->getAchatHT() * 0.2);
            $facturefournisseur->setTaxe($bcfournisseur->getAchatHT() * 0.2);
//            $facturefournisseur->setTaxe($facturefournisseur->getAchatTTC() - $facturefournisseur->getAchatHT());
            $em->persist($bcfournisseur);
            $em->flush();
            $em->persist($facturefournisseur);
            $em->flush();
            if ($facture->getProductions()->count() != 0) {

                $production = $facture->getProductions()->first();
                $production->setConsultant($mission->getConsultant());
                $production->setClient($mission->getClient());
                $production->setNbjour($facturefournisseur->getNbjours());
                $production->setAchatHT($bcfournisseur->getAchatHT());
                $production->setAchatTTC($bcfournisseur->getAchatHT() * 1.2);
                $production->setFournisseur($bcfournisseur->getFournisseur());
                $production->setVenteHT($facture->getTotalHT());
                $production->setVenteTTC($facture->getTotalHT() * 1.2);
                $production->setMission($mission);

                $production->setMois($facture->getMois());
                $production->setTjmVente($mission->getPrixVente());
                $production->setTjmAchat($mission->getPrixAchat());
                $production->setYear($facture->getYear());
                $production->setFacture($facture);

                $em->persist($production);
                $em->flush();
            }

//            $facture->setEtat('non payé');
            $em->persist($facture);
            $em->flush();

        }
        return $this->render('facture/edit.html.twig', array(
            'facture' => $facture,
            'edit_form' => $editForm->createView(),

        ));

    }

    /**
     * Displays a form to edit an existing facture entity.
     *
     * @Route("/{id}/edit", name="facture_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Facture $facture)
    {
        if ($facture->getId() << 140) {

            return $this->redirectToRoute('facture_index');
        }
//        dump($facture);
//        $deleteForm = $this->createDeleteForm($facture);
        $editForm = $this->createForm('AppBundle\Form\FactureEditType', $facture);
        $editForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $mission = $facture->getMission();
            $prixAchatHT = $mission->getPrixAchat();
            $prixVenteHT = $mission->getPrixVente();
            $bcfournisseur = $em->getRepository('AppBundle:Bcfournisseur')->findOneBy([
                'mission' => $mission,
                'mois' => $facture->getMois()

            ]);
            $facturefournisseur = $em->getRepository('AppBundle:Facturefournisseur')->findOneBy([
                'mission' => $mission,
                'mois' => $facture->getMois()

            ]);
            $production = $em->getRepository('AppBundle:Production')->findOneBy([
                'mission' => $mission,
                'mois' => $facture->getMois(),
                'facture' => $facture

            ]);
            if (!$production) {

                $production = $em->getRepository('AppBundle:Production')->findOneBy([
                    'mission' => $mission,
                    'mois' => $facture->getMois(),
//                'facture' => $facture

                ]);
            }


            if ($mission->getDevise() == 'DH') {

                if ($mission->getType() == 'journaliere') {

                    $totalHT = $prixVenteHT * $facture->getNbjour();
                    $achatHT = $prixAchatHT * $facture->getNbjour();
                    $TVA = ($prixVenteHT * $facture->getNbjour()) * 0.2;
                    $TVA_Achat = $achatHT * 0.2;
                    $bcfournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $bcfournisseur->setVenteHT($totalHT);
                    $bcfournisseur->setNbjours($facture->getNbjour());
                    $facturefournisseur->setNbjours($facture->getNbjour());
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facture->setTotalHT($totalHT);
                    $facture->setTaxe($TVA);
                    $facture->setTotalTTC($TVA + $totalHT);
                    $bcclient = $facture->getBcclient();

                    if ($bcclient != null) {

                        $bcclient->setNbJrsR($bcclient->getNbJrsR() - $facture->getNbjour());
                        $em->persist($bcclient);
//                        dump($facture);die();

                    }
                } else {
                    $totalHT = $prixVenteHT;
                    $achatHT = $prixAchatHT;
                    $TVA_Achat = $achatHT * 0.2;
                    $bcfournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $bcfournisseur->setVenteHT($totalHT);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $TVA = ($prixVenteHT) * 0.2;
                    $facture->setTaxe($TVA);

                    $facture->setTotalHT($totalHT);
                    $facture->setTotalTTC($TVA + $totalHT);

                }


            } else {
//                dump($facture);die();

                if ($mission->getType() == 'journaliere') {

                    $totalHT = $prixVenteHT * $facture->getNbjour();
                    $achatHT = $prixAchatHT * $facture->getNbjour();
                    $TVA = 0;
                    $TVA_Achat = 0;
                    /* $bcfournisseur->setAchatHT($achatHT);
                     $facturefournisseur->setAchatHT($achatHT);
                     $bcfournisseur->setTaxe($TVA_Achat);
                     $facturefournisseur->setTaxe($TVA_Achat);
                     $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                     $bcfournisseur->setVenteHT($totalHT);
                     $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);*/
                    $facture->setTotalHT($totalHT);
                    $facture->setTaxe($TVA);
                    $facture->setTotalTTC($TVA + $totalHT);
                    $bcclient = $facture->getBcclient();
                    //dump($bcclient);
                    if ($bcclient != null) {

                        $bcclient->setNbJrsR($bcclient->getNbJrsR() - $facture->getNbjour());
                        $em->persist($bcclient);
                        $em->flush();
                    }
//                    dump($facture);die();

                } else {
                    $totalHT = $prixVenteHT;
                    $achatHT = $prixAchatHT;
                    $TVA_Achat = 0;
                    /*$bcfournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $bcfournisseur->setVenteHT($totalHT);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facturefournisseur->setBcfournisseur($bcfournisseur);*/
                    $TVA = 0;
                    $facture->setTaxe($TVA);

                    $facture->setTotalHT($totalHT);
                    $facture->setTotalTTC($TVA + $totalHT);
//                    dump($facture);die();
                }


            }
            if ($production) {

                $production->setConsultant($mission->getConsultant());
                $production->setClient($mission->getClient());
                $production->setNbjour($facture->getNbjour());
                $production->setAchatHT($bcfournisseur->getAchatHT());
                $production->setAchatTTC($bcfournisseur->getAchatHT() * 1.2);
                $production->setFournisseur($bcfournisseur->getFournisseur());
                $production->setVenteHT($facture->getTotalHT());
                $production->setVenteTTC($facture->getTotalHT() * 1.2);
                $production->setMission($mission);

                $production->setMois($facture->getMois());
                $production->setTjmVente($mission->getPrixVente());
                $production->setTjmAchat($mission->getPrixAchat());
                $production->setYear($facture->getYear());
                $production->setFacture($facture);

                $em->persist($production);
                $em->flush();
            }


            $facture->setEditedby($this->getUser());
            $facture->setUpdatedAt(new \DateTime());
//            dump($facture, $bcfournisseur, $facturefournisseur, $mission, $production);

            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('facture_edit', array('id' => $facture->getId()));
        }

        return $this->render('facture/edit.html.twig', array(
            'facture' => $facture,
            'edit_form' => $editForm->createView(),

        ));
    }

    /**
     * Displays a form to edit an existing facture entity.
     *
     * @Route("/{id}/edit_projet", name="facture_edit_projet")
     * @Method({"GET", "POST"})
     */
    public function editProjetOrangeAction(Request $request, Facture $facture)
    {
        if ($facture->getId() << 140) {

            return $this->redirectToRoute('facture_index');
        }
        $em = $this->getDoctrine()->getManager();
        $projet = $facture->getProjet();
//        dump($projet);
//        $bcfournisseurs=$projet->getBcfournisseurs();
//dump($facture);
        $editForm = $this->createForm('AppBundle\Form\Facture2Type', $facture);
        $editForm->handleRequest($request);
        $totalHt = null;
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            foreach ($facture->getLignes() as $ligne) {

                $totalHt += $ligne->getNbjour() * $ligne->getProjetconsultant()->getVente();
//
                $ligne->setNbjourVente($ligne->getNbjour());

                $ligne->setTotalHT($ligne->getNbjour() * $ligne->getProjetconsultant()->getVente());
                $ligne->setTotalTTC($ligne->getNbjour() * $ligne->getProjetconsultant()->getVente() * 1.2);

                if ($ligne->getProductions()->count() != 0) {

                    $production = $ligne->getProductions()->first();
                    $production->setVenteHT($ligne->getTotalHT());
                    $production->setVenteTTC($ligne->getTotalTTC());
                    $production->setNbjour($ligne->getNbjour());
                    $production->setAchatHT($ligne->getNbjour() * $ligne->getProjetconsultant()->getAchat());
                    $production->setAchatTTC($production->getAchatHT() * 1.2);

                    $em->persist($production);
                    $em->flush();

                }


//                dump($production);die();

            }


            $taxe = $totalHt * 0.2;

            $facture->setTotalHT($totalHt);
            $facture->setTaxe($taxe);
            $facture->setTotalTTC($taxe + $totalHt);
            $facture->setProjet($projet);
//            $em->persist($facture);
//            $em->flush();


            $this->getDoctrine()->getManager()->flush();
            $bcfournisseurs = $em->createQuery('
             SELECT l,IDENTITY (p.consultant) as consultant ,SUM ( l.nbjour),SUM (l.totalHt)
             FROM AppBundle:LigneFacture l
             JOIN AppBundle:Projetconsultant p

             WHERE l.facture = :id 
             AND l.projetconsultant = p.id 
            
             GROUP BY p.consultant')
                ->setParameter('id', $facture)
                ->getResult();
            foreach ($bcfournisseurs as $bcfournisseur) {


                $bc = $em->getRepository('AppBundle:Bcfournisseur')->findOneBy(array(
                    'consultant' => intval($bcfournisseur['consultant']),
                    'mois' => $facture->getMois(),
                    'facture' => $facture,
                    'projet' => $facture->getProjet()
                ));
                $facturefournisseur = $em->getRepository('AppBundle:Facturefournisseur')->findOneBy([
                    'consultant' => intval($bcfournisseur['consultant']),
                    'mois' => $facture->getMois(),
                    'facture' => $facture,
                    'projet' => $facture->getProjet()
                ]);
//update bc fournisseur

                $nb_jours = floatval($bcfournisseur[1]);
                $tjm_achat = $bcfournisseur[0]->getProjetconsultant()->getAchat();
                $totalVente = floatval($bcfournisseur[2]);
                if ($bc) {
                    $bc->setNbjours($nb_jours);
                    $bc->setAchatHt($tjm_achat * $nb_jours);
                    $bc->setTaxe($tjm_achat * $nb_jours * 0.2);
                    $bc->setAchatTTC($tjm_achat * $nb_jours * 1.2);
                    $bc->setVenteHt($totalVente);
                }
                if ($facturefournisseur) {
                    $facturefournisseur->setNbjours($nb_jours);
                    $facturefournisseur->setAchatHt($tjm_achat * $nb_jours);
                    $facturefournisseur->setTaxe($tjm_achat * $nb_jours * 0.2);
                    $facturefournisseur->setAchatTTC($tjm_achat * $nb_jours * 1.2);

                }


//                dump($bcfournisseur[0], $bc, $facturefournisseur, $tjm_achat, $nb_jours, $totalVente);
            }
            $facture->setEditedby($this->getUser());
            $facture->setUpdatedAt(new \DateTime());

            $this->getDoctrine()->getManager()->flush();

            /*  dump($facture, $facture->getLignes(), $totalHt, $bcfournisseurs);
              die();*/

            return $this->redirectToRoute('facture_edit', array('id' => $facture->getId()));
        }

        return $this->render('facture/edit_projet.html.twig', array(
            'projet' => $facture->getProjet(),
            'facture' => $facture,
            'form' => $editForm->createView(),

        ));
    }

    /**
     * Displays a form to edit an existing facture entity.
     *
     * @Route("/{id}/addsheet", name="facture_sheet")
     * @Method({"GET", "POST"})
     */
    public function addsheetAction(Request $request, Facture $facture)
    {
//        dump($facture);
        $deleteForm = $this->createDeleteForm($facture);
        $editForm = $this->createForm('AppBundle\Form\FacturesheetType', $facture);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('facture_index');
        }

        return $this->render('facture/edit.html.twig', array(
            'facture' => $facture,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a facture entity.
     *
     * @Route("/{id}", name="facture_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Facture $facture)
    {
        $form = $this->createDeleteForm($facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($facture);
            $em->flush();
        }

        return $this->redirectToRoute('facture_index');
    }

    /**
     * Deletes a facture entity.
     *
     * @Route("/{id}/delete", name="facture_remove")
     * @Method("GET")
     */
    public function supprimerAction(Request $request, Facture $facture)
    {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($facture);
            $em->flush();
        } else {

            echo 'role insuffisant ! ';
            die();
        }


        return $this->redirectToRoute('facture_index');
    }

    /**
     * Creates a form to delete a facture entity.
     *
     * @param Facture $facture The facture entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Facture $facture)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('facture_delete', array('id' => $facture->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     *
     * @Route("/test", name="route_to_retrieve_mission",options={"expose"=true})
     ** @Method({"GET", "POST"})
     */
    public function getNiveau(Request $request)
    {
        $Id = $request->get('idMission');
        $year = $request->get('year');
        /*  $Id = 6;
          $year = 2020;*/
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('AppBundle:Mission')->find($Id);
        $factures = $em->getRepository('AppBundle:Facture')->findBy(

            [
                'mission' => $mission,
                'year' => $year
            ]


        );
        if ($mission->getType()) {
            $type = $mission->getType();
        } else {

            $type = null;
        }


        if ($factures != null) {
            foreach ($factures as $facture) {

                $output[] = array($facture->getMois());
            }

            $response = json_encode(array('data' => $type, 'mois' => $output));
        } else {
            $response = json_encode(array('data' => $type, 'mois' => null));
        }


        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));

    }

    /**
     *
     * @Route("/convert_devise", name="route_to_convert_devise", options={"expose"=true})
     ** @Method({"GET","POST"})
     */
    public function convertDevise(Request $request)
    {
        $id = $request->get('id');
        $montant = $request->get('montant');

        $em = $this->getDoctrine()->getManager();

        $facture = $em->getRepository('AppBundle:Facture')->find($id);
        $facture->setTotalDH($montant);
        $facture->setEtat('payé');
        $em->persist($facture);
        $em->flush();
        $response = json_encode(array('data' => 'ok'));

        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));
    }

    /**
     *
     * @Route("/test/{id}", name="route_test")
     ** @Method({"GET"})
     */
    public function Test(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();

        $mois = intval($facture->getDate()->format('m'));
        $year = intval($facture->getDate()->format('Y'));
        $yearmini = intval($facture->getDate()->format('y'));


        $nb = count($em->getRepository('AppBundle:Facture')->findBy(array(

            'mois' => $mois,
            'year' => $year,
        )));

        $nbb = $em->createQuery('
            
            SELECT COUNT(f) as total FROM AppBundle:Facture f 
            WHERE MONTH(f.date) = :moi AND YEAR(f.date) = :annee
            ')
            ->setParameters([

                'moi' => $mois,
                'annee' => $year,
            ])->getResult();
        $nbb2 = $em->createQuery('
            
            SELECT COUNT(f) as total FROM AppBundle:Facture f 
            WHERE MONTH(f.date) = :moi AND YEAR(f.date) = :annee
            ')
            ->setParameters([

                'moi' => $mois,
                'annee' => $year,
            ])->getResult();


    }


}
