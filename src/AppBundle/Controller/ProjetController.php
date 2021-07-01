<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bcfournisseur;
use AppBundle\Entity\Facture;
use AppBundle\Entity\Facturefournisseur;
use AppBundle\Entity\LigneFacture;
use AppBundle\Entity\Production;
use AppBundle\Entity\Projet;
use AppBundle\Form\LignebcfournisseurType;
use AppBundle\Form\LigneFactureType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


/**
 * Projet controller.
 *
 * @Route("projet")
 */
class ProjetController extends Controller
{
    /**
     * Lists all projet entities.
     *
     * @Route("/", name="projet_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projets = $em->getRepository('AppBundle:Projet')->findAll();

        return $this->render('projet/index.html.twig', array(
            'projets' => $projets,
        ));
    }

    /**
     * Creates a new projet entity.
     *
     * @Route("/new", name="projet_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $projet = new Projet();
        $form = $this->createForm('AppBundle\Form\ProjetType', $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($projet);


            foreach ($projet->getProjetconsultants() as $projetconsultant) {

                $projetconsultant->setProjet($projet);
            }
            $em->flush();
            return $this->redirectToRoute('projet_show', array('id' => $projet->getId()));
        }

        return $this->render('projet/new.html.twig', array(
            'projet' => $projet,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}", name="projet_show")
     * @Method("GET")
     */
    public function showAction(Projet $projet)
    {
        $deleteForm = $this->createDeleteForm($projet);

        //dump($projet);

        return $this->render('projet/show.html.twig', array(
            'projet' => $projet,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}/facturation", name="projet_facturation")
     * @Method({"GET", "POST"})
     */
    public function FacturationAction(Projet $projet, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $facture = new Facture();
        $facture->setAddedby($this->getUser());
        $facture->setEtat('non payé');
        $facture->setClient($projet->getClient());

        if ($projet->getProjetconsultants()) {
            $projetconsultants = $em->createQuery('
          SELECT p   
          From AppBundle:Projetconsultant p
         
          WHERE p.projet = :projet
                  ORDER By p.consultant,p.job
          ')->setParameter('projet', $projet)->execute();

//dump($projetconsultants);
            foreach ($projetconsultants as $projetconsultant) {
                $ligne = new LigneFacture();
                $ligne->setFacture($facture);
                $ligne->setProjetconsultant($projetconsultant);
//                $em->persist($ligne);
//                $em->flush();
                $facture->addLigne($ligne);

            }
        }

        $form = $this->createForm('AppBundle\Form\Facture2Type', $facture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $totalHt = null;
            $facture->setProjet($projet);
            $facture->setDatetimesheet($facture->getDate());

            foreach ($facture->getLignes() as $ligne) {

                $totalHt += $ligne->getNbjour() * $ligne->getProjetconsultant()->getVente();
//
                $ligne->setNbjourVente($ligne->getNbjour());

                $ligne->setTotalHT($ligne->getNbjour() * $ligne->getProjetconsultant()->getVente());
                $ligne->setTotalTTC($ligne->getNbjour() * $ligne->getProjetconsultant()->getVente() * 1.2);

            }
            $taxe = $totalHt * 0.2;
//            dump($form->getData(),$totalHt);die();
            // num facture
            $mois = intval($facture->getDate()->format('m'));
            $year = intval($facture->getDate()->format('Y'));
            $yearmini = intval($facture->getDate()->format('y'));
            $nbb = $em->createQuery('           
            SELECT COUNT(f) as total FROM AppBundle:Facture f 
            WHERE MONTH(f.date) = :moi AND YEAR(f.date) = :annee
            ')->setParameters([
                'moi' => $mois,
                'annee' => $year,
            ])->getResult();


            $count_factures = (int)$nbb[0]['total'] + 1;
            $facture->setNumero('H3K-' . substr($year, -2) . '-' . str_pad($mois, 2, '0', STR_PAD_LEFT) . '-' . str_pad($count_factures, 3, '0', STR_PAD_LEFT));


            $facture->setTotalHT($totalHt);
            $facture->setTaxe($taxe);
            $facture->setTotalTTC($taxe + $totalHt);
            $em->persist($facture);
            $em->flush();
            // orange
            if ($facture->getProjet()->getClient()->getNom() == 'MEDI TELECOM') {
                $this->arrondFacture($facture);

            }
            return $this->redirectToRoute('projet_bcfournisseur', array('id' => $facture->getId()));


        }
//        dump($projet,$facture);

        return $this->render('projet/facturation.html.twig', array(
            'projet' => $projet,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}/end", name="projet_end")
     * @Method("GET")
     */
    public function endAction(Projet $projet)
    {
        $projet->setStatut('Terminé');
        $em = $this->getDoctrine()->getManager();
        $em->persist($projet);
        $em->flush();


        return $this->redirectToRoute('projet_show', array('id' => $projet->getId()));

    }

    /**
     * Displays a form to edit an existing projet entity.
     *
     * @Route("/{id}/edit", name="projet_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Projet $projet)
    {
        $deleteForm = $this->createDeleteForm($projet);
        $editForm = $this->createForm('AppBundle\Form\ProjetType', $projet);
        $editForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            foreach ($projet->getProjetconsultants() as $bloc) {

                $bloc->setProjet($projet);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('projet_show', array('id' => $projet->getId()));
        }

        return $this->render('projet/edit.html.twig', array(
            'projet' => $projet,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a projet entity.
     *
     * @Route("/{id}", name="projet_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Projet $projet)
    {
        $form = $this->createDeleteForm($projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projet);
            $em->flush();
        }

        return $this->redirectToRoute('projet_index');
    }

    /**
     * Creates a form to delete a projet entity.
     *
     * @param Projet $projet The projet entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Projet $projet)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('projet_delete', array('id' => $projet->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Finds and displays a projetconsultant entity.
     *
     * @Route("/{id}/bcfournisseur", name="projet_bcfournisseur")
     * @Method({"GET", "POST"})
     */
    public function bcfournisseurAction(Facture $facture, Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $query = $em->createQuery('
             SELECT l  FROM AppBundle:LigneFacture l
             JOIN AppBundle:Projetconsultant p

             WHERE l.facture = :id and l.projetconsultant = p.id AND p.consultant IS NOT NULL AND l.nbjour IS NOT NULL 
            ')->setParameter('id', $facture)->getResult();
//dump($query);
//collection of lignes
        $lignes_collection = new \Doctrine\Common\Collections\ArrayCollection();
//        $nbjours = [];
        foreach ($query as $item) {
//            $nbjours[] = $item->getNbjour();

            $lignes_collection[] = $item;

            $item->setNbjour(null);

        }

        $defaultData = [
            'lignes' => $lignes_collection,
            'mois' => $facture->getMois(),
            'year' => $facture->getYear()

        ];

//         dump($lignes_collection, $defaultData, $query);
        $form = $this->createFormBuilder($defaultData)
            ->add('mois', ChoiceType::class, array(
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'choices' => array(
                    'Janvier' => 1,
                    'Fevrier' => 2,
                    'Mars' => 3,
                    'Avril' => 4,
                    'Mai' => 5,
                    'Juin' => 6,
                    'Juillet' => 7,
                    'Aout' => 8,
                    'Septembre' => 9,
                    'Octobre' => 10,
                    'Novembre' => 11,
                    'Décembre' => 12,
                ),
            ))
            ->add('year')
            ->add('lignes', CollectionType::class, [
                'entry_type' => LignebcfournisseurType::class,
                'entry_options' => ['label' => false],
                'attr' => array(
                    'class' => 'my-selector inl ',
                    'label' => 'consultants list :',
                ),
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => false,

                'by_reference' => true,

            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $lignes = $form->get('lignes')->getData();

            $mois = $form->get('mois')->getData();
            $year = $form->get('year')->getData();

            foreach ($lignes as $ligne) {

                $bcclient = $ligne->getProjetconsultant()->getBcclient();
                if ($bcclient) {

                    $bcclient->setNbJrsR($bcclient->getNbJrsR() - $ligne->getNbjour());
                    $em->persist($bcclient);
                    $em->flush();
                }
                $bc = new Bcfournisseur();
                $bc->setFacture($facture);
                $factureFournisseur = new Facturefournisseur();
                $factureFournisseur->setFacture($facture);
                $achatht = $ligne->getNbjour() * $ligne->getProjetconsultant()->getAchat();
                $venteht = $ligne->getNbjour() * $ligne->getProjetconsultant()->getVente();
                $taxe = 0.2 * $achatht;
                $bc->setAchatHT($achatht);
                $factureFournisseur->setAchatHT($achatht);
                $bc->setVenteHT($venteht);
                $bc->setTaxe($taxe);
                $factureFournisseur->setTaxe($taxe);
                $bc->setNbjours($ligne->getNbjour());
                $factureFournisseur->setNbjours($ligne->getNbjour());
                $bc->setMois($mois);
                $factureFournisseur->setMois($mois);
                $bc->setYear($year);
                $factureFournisseur->setYear($year);
                $bc->setConsultant($ligne->getProjetconsultant()->getConsultant());
                $factureFournisseur->setConsultant($ligne->getProjetconsultant()->getConsultant());
                $bc->setAchatTTC($achatht + $taxe);
                $factureFournisseur->setAchatTTC($achatht + $taxe);
                if ($mois != null and $year != null) {
                    $nb = count($em->getRepository('AppBundle:Bcfournisseur')->findBy([

                            'mois' => $mois,
                            'year' => $year
                        ])) + 1;
                    $nb1 = count($em->getRepository('AppBundle:Facturefournisseur')->findBy([

                            'mois' => $mois,
                            'year' => $year
                        ])) + 1;
                    $bc->setCode('BC-' . substr($year, -2) . '-' . str_pad($mois, 2, '0', STR_PAD_LEFT) . '-' . str_pad($nb, 3, '0', STR_PAD_LEFT));
                    $factureFournisseur->setNumero('F-' . substr($year, -2) . '-' . str_pad($mois, 2, '0', STR_PAD_LEFT) . '-' . str_pad($nb1, 3, '0', STR_PAD_LEFT));

                }
                $bc->setFournisseur($ligne->getProjetconsultant()->getFournisseur());
                $factureFournisseur->setFournisseur($ligne->getProjetconsultant()->getFournisseur());
                $bc->setDate(new \DateTime());
                $factureFournisseur->setDate(new \DateTime());
                $factureFournisseur->setProjet($facture->getProjet());
                $bc->setProjet($facture->getProjet());

                $em->persist($bc);
                $em->flush();
                $factureFournisseur->setBcfournisseur($bc);

                $em->persist($factureFournisseur);
                $em->flush();
//                $production
                // add new production

                // if is orange project


                //end add production

                // return nb jours


            }
            foreach ($facture->getLignes() as $ligne) {
                $achatht = $ligne->getNbjour() * $ligne->getProjetconsultant()->getAchat();
                $venteht = $ligne->getNbjourVente() * $ligne->getProjetconsultant()->getVente();
                $taxe = 0.2 * $achatht;
                if ($facture->getProjet()->getClient() == 'MEDI TELECOM') {

                    $production = new Production();
                    $production->setConsultant(($ligne->getProjetconsultant()->getConsultant()));
                    $production->setProjet($facture->getProjet());
                    $production->setFournisseur($ligne->getProjetconsultant()->getFournisseur());
                    $production->setYear($year);
                    $production->setMois($mois);
                    $production->setFacture($facture);

                    $production->setClient($facture->getClient());
                    $production->setTjmAchat($ligne->getProjetconsultant()->getAchat());
                    $production->setTjmVente($ligne->getProjetconsultant()->getVente());
                    $production->setNbjour($ligne->getNbjourVente());
                    $production->setAchatHT($achatht);
                    $production->setLigne($ligne);

                    $production->setAchatTTC($achatht + $taxe);
                    $production->setVenteHT($venteht);
                    $production->setVenteTTC($venteht * 1.2);
                    $em->persist($production);

//                    dump($production, $ligne);
                    $em->flush();
                } else {
                    // normal project

                    $production = new Production();
                    $production->setConsultant(($ligne->getProjetconsultant()->getConsultant()));
                    $production->setProjet($facture->getProjet());
                    $production->setFournisseur($ligne->getProjetconsultant()->getFournisseur());
                    $production->setYear($year);
                    $production->setLigne($ligne);
                    $production->setMois($mois);
                    $production->setFacture($facture);
                    $production->setClient($facture->getClient());
                    $production->setTjmAchat($ligne->getProjetconsultant()->getAchat());
                    $production->setTjmVente($ligne->getProjetconsultant()->getVente());
                    $production->setNbjour($ligne->getNbjour());
                    $production->setAchatHT($achatht);
                    $production->setAchatTTC($achatht + $taxe);
                    $production->setVenteHT($venteht);
                    $production->setVenteTTC($venteht * 1.2);
                    $em->persist($production);
                    $em->flush();
                }

            }


//            die();
//            dump($bc, $facture, $factureFournisseur,$production);
            return $this->redirectToRoute('projet_show', array('id' => $facture->getProjet()->getId()));


        }

        return $this->render('projet/bcfournisseur.html.twig', array(
            'facture' => $facture,
            'form' => $form->createView(),
        ));
    }

    private function arrondFacture(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();
//dump($facture);
        $items = $em->createQuery('
          SELECT p as ligne,SUM (l.nbjourVente) AS nbjours, SUM(l.totalHt) as total,SUM(l.totalTTC) as totalTTC ,p.vente as tjm   From AppBundle:LigneFacture l
          JOIN AppBundle:Projetconsultant p
          WHERE l.facture = :facture
          AND l.projetconsultant = p.id
           AND l.nbjour>0 AND l.totalHt>0
          GROUP BY p.job
          
          ')->setParameter('facture', $facture)->execute();
        $totalHT = 0;
        foreach ($items as $item) {

            $totalHT += round(floatval($item['nbjours']), 2) * $item['tjm'];


        }
        $facture->setTotalHT($totalHT);
        $facture->setTaxe($facture->getTotalHT() * 0.2);
        $facture->setTotalTTC($facture->getTotalHT() + $facture->getTaxe());

        $em->persist($facture);
        $em->flush();

//        dump($totalHT, $facture, $items);
//        die();
    }
}
