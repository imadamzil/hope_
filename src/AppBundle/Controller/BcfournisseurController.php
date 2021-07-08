<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bcfournisseur;
use AppBundle\Entity\Facturefournisseur;
use AppBundle\Entity\Virement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bcfournisseur controller.
 *
 * @Route("bcfournisseur")
 */
class BcfournisseurController extends Controller
{

    /**
     * Lists all bcfournisseur entities.
     *
     * @Route("/", name="bcfournisseur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();


        $bcfournisseurs = $em->getRepository('AppBundle:Bcfournisseur')->findAll();

        if (empty($bcfournisseurs)) {

            $bcfournisseurs = [];
        }
        //dump($bcfournisseurs);
        return $this->render('bcfournisseur/index.html.twig', array(
            'bcfournisseurs' => array_reverse($bcfournisseurs),
        ));
    }

    /**
     * Creates a new bcfournisseur entity.
     *
     * @Route("/new", name="bcfournisseur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $bcfournisseur = new Bcfournisseur();
        $bcfournisseur->setYear(intval((new \DateTime('now'))->format('Y')));
        $bcfournisseur->setMois(intval((new \DateTime('now'))->format('m')));
        $form = $this->createForm('AppBundle\Form\BcfournisseurType', $bcfournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $projet = $bcfournisseur->getProjet();

            if ($projet and $projet->getProjetconsultants()->count() == 1) {

                if ($projet->getProjetconsultants()->count() == 1) {
                    $projet->getFactures()->count() == 1 ? $facture = $projet->getFactures()->last() : $facture = null;
                    $projet_consultant = $projet->getProjetconsultants()->first();
                    $tjm_achat = $projet_consultant->getAchat();
                    $tjm_vente = $projet_consultant->getVente();
                    $totalAchat = $bcfournisseur->getNbjours() * $tjm_achat;
                    $bc_client = $projet_consultant->getBcclient();
                    $bc_client->setNbJrsR($bc_client->getNbJrsR() - $bcfournisseur->getNbjours());
                    //bc_fournisseur
                    $bcfournisseur->setConsultant($projet_consultant->getConsultant());
                    $bcfournisseur->setFacture($facture);
                    $bcfournisseur->setAchatHT($totalAchat);
                    $bcfournisseur->setAchatTTC($bcfournisseur->getAchatHT() * 1.2);
                    $bcfournisseur->setTaxe($bcfournisseur->getAchatHT() * 0.2);
                    //set code to bcfournisseur
                    $nb = count($em->getRepository('AppBundle:Bcfournisseur')->findBy(array(

                        'mois' => $facture->getMois(),
                        'year' => $facture->getYear(),
                    )));
                    $bcfournisseur->setCode('BC-' . substr($facture->getYear(), -2) . '-' . str_pad($facture->getMois(), 2, '0', STR_PAD_LEFT) . '-' . str_pad($nb + 1, 3, '0', STR_PAD_LEFT));

                    //end bc_fournisseur

                    //facture_fournisseur
                    $facturefournisseur = new Facturefournisseur();
                    $facturefournisseur->setBcfournisseur($bcfournisseur);
                    $facturefournisseur->setYear($bcfournisseur->getYear());
                    $facturefournisseur->setDate($bcfournisseur->getDate());
                    $facturefournisseur->setTaxe($bcfournisseur->getTaxe());
                    $facturefournisseur->setAchatTTC($bcfournisseur->getAchatTTC());
                    $facturefournisseur->setAchatHT($bcfournisseur->getAchatHT());
                    $facturefournisseur->setNbjours($bcfournisseur->getNbjours());
                    $facturefournisseur->setMois($bcfournisseur->getMois());
                    $facturefournisseur->setFacture($facture);
                    $facturefournisseur->setProjet($projet);
                    $facturefournisseur->setAchatHT($totalAchat);
                    $facturefournisseur->setAchatTTC($facturefournisseur->getAchatHT() * 1.2);
                    $facturefournisseur->setTaxe($facturefournisseur->getAchatHT() * 0.2);
                    $facturefournisseur->setFournisseur($bcfournisseur->getFournisseur());
                    $facturefournisseur->setConsultant($projet_consultant->getConsultant());

                    $em->persist($facturefournisseur);
                    $virement = new Virement();
                    $virement->setBcfournisseur($bcfournisseur);
                    $virement->setAchat($bcfournisseur->getAchatTTC());
                    $virement->setDate($bcfournisseur->getDate());
                    $virement->setEtat('en attente');

                    $virement->setConsultant($bcfournisseur->getConsultant());
                    $virement->setFacturefournisseur($facturefournisseur);
                    $em->persist($virement);
                    $em->flush();
//                    $em->flush();
// end facture_fournisseur
                } else {

                    return $this->redirectToRoute('bcfournisseur_index');
                }

            }
            $nb = count($em->getRepository('AppBundle:Bcfournisseur')->findBy(array(

                'mois' => $bcfournisseur->getMois(),
                'year' => $bcfournisseur->getYear(),
            )));
            $bcfournisseur->setCode('BC-' . substr($bcfournisseur->getYear(), -2) . '-' . str_pad($bcfournisseur->getMois(), 2, '0', STR_PAD_LEFT) . '-' . str_pad($nb + 1, 3, '0', STR_PAD_LEFT));


            $em->persist($bcfournisseur);
            $em->flush();
            $facturefournisseur = new Facturefournisseur();
            $facturefournisseur->setBcfournisseur($bcfournisseur);
            $facturefournisseur->setYear($bcfournisseur->getYear());
            $facturefournisseur->setDate($bcfournisseur->getDate());
            $facturefournisseur->setTaxe($bcfournisseur->getTaxe());
            $facturefournisseur->setAchatTTC($bcfournisseur->getAchatTTC());
            $facturefournisseur->setAchatHT($bcfournisseur->getAchatHT());
            $facturefournisseur->setNbjours($bcfournisseur->getNbjours());
            $facturefournisseur->setMois($bcfournisseur->getMois());
            $facturefournisseur->setProjet($projet);
            $facturefournisseur->setAchatHT($bcfournisseur->getAchatHT());
            $facturefournisseur->setAchatTTC($facturefournisseur->getAchatHT() * 1.2);
            $facturefournisseur->setTaxe($facturefournisseur->getAchatHT() * 0.2);
            $facturefournisseur->setFournisseur($bcfournisseur->getFournisseur());
            $facturefournisseur->setConsultant($bcfournisseur->getConsultant());

            $em->persist($facturefournisseur);
            $em->flush();
            $virement = new Virement();
            $virement->setBcfournisseur($bcfournisseur);
            $virement->setAchat($bcfournisseur->getAchatTTC());
            $virement->setDate($bcfournisseur->getDate());
            $virement->setEtat('en attente');

            $virement->setConsultant($bcfournisseur->getConsultant());
            $virement->setFacturefournisseur($facturefournisseur);
            $em->persist($virement);
            $em->flush();

            return $this->redirectToRoute('bcfournisseur_show', array('id' => $bcfournisseur->getId()));
        }

        return $this->render('bcfournisseur/new.html.twig', array(
            'bcfournisseur' => $bcfournisseur,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/get_bc_infos", name="route_bc_getinfo",options={"expose"=true})
     ** @Method({"GET", "POST"})
     */
    public function validateAction(Request $request)

    {
        $em = $this->getDoctrine()->getManager();

        $nbjour = $request->get('nbjour');

        $id_mission = $request->get('id_mission');
        $id_projet = $request->get('id_project');
//        $id_projet = 5;

        if ($id_projet) {

//            $mission = $em->getRepository('AppBundle:Mission')->find($id_mission);
            $projet = $em->getRepository('AppBundle:Projet')->find($id_projet);

            $total_consultant = $projet->getProjetconsultants()->count();

            if ($total_consultant == 1) {

                $projet_consultant = $projet->getProjetconsultants()->first();
                $bc_client = $projet_consultant->getBcclient();
                $nbjours_r = $bc_client->getNbJrsR();
                $nbjours_r_maj = $nbjours_r - $nbjour;
            }
        } else {

//            $mission = null;
            $projet = null;
            $bc_client = null;
        }


        $response = json_encode([
            'data' => [
                'nbjour' => $nbjour,
                'bcclient' => $bc_client->getCode(),
                'nbjour_r' => $bc_client->getNbJrsR()

                , 'nbjour_r_maj' => $nbjours_r_maj]


        ]);

        return new Response($response, 200, array(
            'Content-Type' => 'application/json'
        ));
    }

    /**
     * Finds and displays a bcfournisseur entity.
     *
     * @Route("/{id}", name="bcfournisseur_show")
     * @Method("GET")
     */
    public function showAction(Bcfournisseur $bcfournisseur)
    {
        $deleteForm = $this->createDeleteForm($bcfournisseur);

        return $this->render('bcfournisseur/show.html.twig', array(
            'bcfournisseur' => $bcfournisseur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a bcfournisseur entity.
     *
     * @Route("/{id}/print", name="bcfournisseur_print")
     * @Method("GET")
     */
    public function printAction(Bcfournisseur $bcfournisseur)
    {
        $em = $this->getDoctrine()->getManager();
        $fiche = $em->getRepository('AppBundle:Fiche')->find(1);
//        dump($bcfournisseur);

        if (!empty($bcfournisseur->getHeures())) {
            $nb = null;
            foreach ($bcfournisseur->getHeures() as $heure)
                $nb += $heure->getNbjour();
        } else {
            $nb = 0;

        }
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

        if ($bcfournisseur->getMission()) {

            return $this->render('bcfournisseur/print.html.twig', array(
                'bcfournisseur' => $bcfournisseur,
                'fiche' => $fiche,
                'mois' => mois_convert($bcfournisseur->getMois()),
                'nb' => $nb,
            ));
        } else {
            return $this->render('bcfournisseur/print_projet.html.twig', array(
                'bcfournisseur' => $bcfournisseur,
                'fiche' => $fiche,
                'mois' => mois_convert($bcfournisseur->getMois()),
                'nb' => $nb,
            ));

        }

    }

    /**
     * Displays a form to edit an existing bcfournisseur entity.
     *
     * @Route("/{id}/edit", name="bcfournisseur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Bcfournisseur $bcfournisseur)
    {
        $deleteForm = $this->createDeleteForm($bcfournisseur);
        $editForm = $this->createForm('AppBundle\Form\BcfournisseurType', $bcfournisseur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bcfournisseur_edit', array('id' => $bcfournisseur->getId()));
        }

        return $this->render('bcfournisseur/edit.html.twig', array(
            'bcfournisseur' => $bcfournisseur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a bcfournisseur entity.
     *
     * @Route("/{id}", name="bcfournisseur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Bcfournisseur $bcfournisseur)
    {
        $form = $this->createDeleteForm($bcfournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bcfournisseur);
            $em->flush();
        }

        return $this->redirectToRoute('bcfournisseur_index');
    }

    /**
     * Creates a form to delete a bcfournisseur entity.
     *
     * @param Bcfournisseur $bcfournisseur The bcfournisseur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Bcfournisseur $bcfournisseur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bcfournisseur_delete', array('id' => $bcfournisseur->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }


}
