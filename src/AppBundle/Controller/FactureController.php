<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bcfournisseur;
use AppBundle\Entity\Facture;
use AppBundle\Entity\Facturefournisseur;
use AppBundle\Entity\Mission;
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
     * @Route("/", name="facture_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $factures = $em->getRepository('AppBundle:Facture')->findAll();
        $missions = $em->getRepository('AppBundle:Mission')->findAll();

        $date = new \DateTime('now');
        $mois = intval($date->format('m')) - 1;
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


            // dump($diff, $missions, $missions_factured, count($diff));

            // $nb_non_factured_missions = count($diff);
        } else {

            $nb_non_factured_missions = null;
            //$diff = array_diff_assoc($missions, $missions_factured);
        }

        dump($factures);
        return $this->render('facture/index.html.twig', array(
            'factures' => $factures,
            'nb_non_factured_missions' => $nb_non_factured_missions,
            'mois' => $mois
        ,
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

        $factures = $em->getRepository('AppBundle:Facture')->findAll();
        $missions = $em->getRepository('AppBundle:Mission')->findAll();
        dump($factures);
        $date = new \DateTime('now');
        $mois = intval($date->format('m')) - 1;
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


            // dump($diff, $missions, $missions_factured, count($diff));

            // $nb_non_factured_missions = count($diff);
        } else {

            $nb_non_factured_missions = null;
            //$diff = array_diff_assoc($missions, $missions_factured);
        }


        return $this->render('facture/missionsansfacture.html.twig', array(
            'factures' => $factures,
            'mois' => $mois,
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
        $bcfournisseur = new Bcfournisseur();
        $facturefournisseur = new Facturefournisseur();

        $date = new \DateTime('now');
        $mois = intval($date->format('m')) - 1;
        $year = intval($date->format('y'));
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

            $em->persist($facture);
            $em->flush();
            $facture = $em->getRepository('AppBundle:Facture')->find($facture->getId());
            $nb = count($em->getRepository('AppBundle:Facture')->findBy(array(

                'mois' => $facture->getMois(),
                'year' => $facture->getYear(),
            )));
            $mission = $facture->getMission();
            dump($mission, $nb);
            $nb_facture = $nb + 1;
            $facture->setNumero('H3K-' . substr($facture->getYear(), -2) . '-' . str_pad($facture->getMois(), 2, '0', STR_PAD_LEFT) . '-' . str_pad($nb, 3, '0', STR_PAD_LEFT));
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
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facture->setTotalHT($totalHT);
                    $facture->setTaxe($TVA);
                    $facture->setTotalTTC($TVA + $totalHT);
                    $bcclient = $facture->getBcclient();
                    dump($bcclient);
                    if ($bcclient != null) {

                        $bcclient->setNbJrsR($bcclient->getNbJrs() - $facture->getNbjour());
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
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facture->setTotalHT($totalHT);
                    $facture->setTaxe($TVA);
                    $facture->setTotalTTC($TVA + $totalHT);
                    $bcclient = $facture->getBcclient();
                    dump($bcclient);
                    if ($bcclient != null) {

                        $bcclient->setNbJrsR($bcclient->getNbJrs() - $facture->getNbjour());
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
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $TVA = 0;
                    $facture->setTaxe($TVA);

                    $facture->setTotalHT($totalHT);
                    $facture->setTotalTTC($TVA + $totalHT);

                }


            }
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
            $em->persist($bcfournisseur);
            $em->persist($facturefournisseur);
            $em->flush();


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

        $bcfournisseur = new Bcfournisseur();
        $facturefournisseur = new Facturefournisseur();
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

            $em->persist($facture);
            $em->flush();

            $facture = $em->getRepository('AppBundle:Facture')->find($facture->getId());
            $nb = count($em->getRepository('AppBundle:Facture')->findBy(array(

                'mois' => $facture->getMois(),
                'year' => $facture->getYear(),
            )));
            $mission = $facture->getMission();
            $bcfournisseur->setMission($mission);
            $facturefournisseur->setMission($mission);
            dump($mission, $nb);
            $nb_facture = $nb + 1;
            $facture->setNumero('H3K-' . substr($facture->getYear(), -2) . '-' . str_pad($facture->getMois(), 2, '0', STR_PAD_LEFT) . '-' . str_pad($nb, 3, '0', STR_PAD_LEFT));

            if ($mission->getDevise() == 'DH') {

                if ($mission->getType() == 'journaliere') {

                    $totalHT = $prixVenteHT * $facture->getNbjour();
                    $achatHT = $prixAchatHT * $facture->getNbjour();
                    $TVA = ($prixVenteHT * $facture->getNbjour()) * 0.2;
                    $TVA_Achat = $achatHT * 0.2;
                    $bcfournisseur->setAchatHT($achatHT);
                    $bcfournisseur->setTaxe($TVA_Achat);
                    $bcfournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facturefournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facture->setTotalHT($totalHT);
                    $facture->setClient($mission->getClient());
                    $facture->setTaxe($TVA);
                    $facture->setTotalTTC($TVA + $totalHT);
                    $bcclient = $facture->getBcclient();
                    $facture->setClient($mission->getClient());
                    dump($bcclient);
                    if ($bcclient != null) {

                        $bcclient->setNbJrsR($bcclient->getNbJrs() - $facture->getNbjour());
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
                    $facturefournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $facture->setTotalHT($totalHT);
                    $facture->setTaxe($TVA);
                    $facture->setClient($mission->getClient());
                    $facture->setTotalTTC($TVA + $totalHT);
                    $bcclient = $facture->getBcclient();
                    dump($bcclient);
                    if ($bcclient != null) {

                        $bcclient->setNbJrsR($bcclient->getNbJrs() - $facture->getNbjour());
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
                    $facturefournisseur->setAchatHT($achatHT);
                    $facturefournisseur->setTaxe($TVA_Achat);
                    $facturefournisseur->setAchatTTC($achatHT + $TVA_Achat);
                    $TVA = 0;
                    $facture->setTaxe($TVA);

                    $facture->setTotalHT($totalHT);
                    $facture->setClient($mission->getClient());
                    $facture->setTotalTTC($TVA + $totalHT);

                }


            }
            $facture->setClient($mission->getClient());
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

            $em->persist($bcfournisseur);
            $em->persist($facturefournisseur);
            $em->flush();


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

        return $this->render('facture/show.html.twig', array(
            'facture' => $facture,
            'delete_form' => $deleteForm->createView(),
            'total' => int2str($facture->getTotalTTC()),
            'mois' => mois_convert($facture->getMois()),

        ));
    }

    /**
     * Finds and displays a facture entity.
     *
     * @Route("/{id}/print", name="facture_print")
     * @Method("GET")
     */
    public function showfactureAction(Facture $facture)
    {
        //  $deleteForm = $this->createDeleteForm($facture);
        dump($facture);

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
            // 'delete_form' => $deleteForm->createView(),
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
        $deleteForm = $this->createDeleteForm($facture);
        $editForm = $this->createForm('AppBundle\Form\FactureType', $facture);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('facture_edit', array('id' => $facture->getId()));
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
}
