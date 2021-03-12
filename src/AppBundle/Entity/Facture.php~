<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureRepository")
 * @Vich\Uploadable
 */
class Facture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=true)
     */
    private $etat;
    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=255, nullable=true)
     */
    private $numero;
    /**
     * @var integer
     *
     * @ORM\Column(name="nb_jours", type="float", nullable=true)
     */
    private $nbjour;

    /**
     * @var integer
     *
     * @ORM\Column(name="mois", type="integer", nullable=true)
     */
    private $mois;
    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="integer", nullable=true)
     */
    private $year;
    /**
     * @var float
     *
     * @ORM\Column(name="total_HT", type="float", nullable=true)
     */
    private $totalHT;
    /**
     * @var float
     *
     * @ORM\Column(name="total_TTC", type="float", nullable=true)
     */
    private $totalTTC;
    /**
     * @var float
     *
     * @ORM\Column(name="total_DH", type="float", nullable=true)
     */
    private $totalDH;
    /**
     * @var float
     *
     * @ORM\Column(name="taxe", type="float", nullable=true)
     */
    private $taxe;

    /**
     * @ORM\ManyToOne(targetEntity="Consultant", inversedBy="factures")
     * @ORM\JoinColumn(name="id_consultant", referencedColumnName="id")
     */
    private $consultant;
    /**
     * @ORM\ManyToOne(targetEntity="Comptebancaire", inversedBy="factures")
     * @ORM\JoinColumn(name="id_compte_bancaire", referencedColumnName="id")
     */
    private $comptebancaire;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="factures")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;
    /**
     * @ORM\ManyToOne(targetEntity="Projet", inversedBy="factures")
     * @ORM\JoinColumn(name="id_projet", referencedColumnName="id")
     */
    private $projet;
    /**
     * @ORM\ManyToOne(targetEntity="Mission", inversedBy="factures")
     * @ORM\JoinColumn(name="id_mission", referencedColumnName="id")
     */
    private $mission;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=true)
     */
    private $dateDebut;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=true)
     */
    private $dateFin;
    /**
     * @ORM\ManyToOne(targetEntity="Bcclient", inversedBy="factures")
     * @ORM\JoinColumn(name="id_bcclient", referencedColumnName="id")
     */
    private $bcclient;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="facturesajoutes")
     * @ORM\JoinColumn(name="id_added_by", referencedColumnName="id")
     */
    private $addedby;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="facturesmodifies")
     * @ORM\JoinColumn(name="id_edited_by", referencedColumnName="id")
     */
    private $editedby;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ceation", type="datetime", nullable=true)
     */
    private $createdAt;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\LigneFacture", mappedBy="facture",cascade={"persist", "remove"})
     */
    private $lignes;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Production", mappedBy="facture",cascade={"persist", "remove"})
     */
    private $productions;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Bcfournisseur", mappedBy="facture",cascade={"persist", "remove"})
     */
    private $bcfournisseurs;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Facturefournisseur", mappedBy="facture",cascade={"persist", "remove"})
     */
    private $facturefournisseurs;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FactureHsup", mappedBy="facture",cascade={"persist", "remove"})
     */
    private $facturehsups;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="document_path", fileNameProperty="documentName")
     *
     * @var File
     */
    private $documentFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $documentName;


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $document
     */
    public function setDocumentFile(?File $document = null): void
    {
        $this->documentFile = $document;

        if (null !== $document) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getDocumentFile(): ?File
    {
        return $this->documentFile;
    }

    public function setDocumentName(?string $documentName): void
    {
        $this->documentName = $documentName;
    }

    public function getDocumentName(): ?string
    {
        return $this->documentName;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return Facture
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set mois
     *
     * @param string $mois
     *
     * @return Facture
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return string
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Facture
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set consultant
     *
     * @param \AppBundle\Entity\Consultant $consultant
     *
     * @return Facture
     */
    public function setConsultant(\AppBundle\Entity\Consultant $consultant = null)
    {
        $this->consultant = $consultant;

        return $this;
    }

    /**
     * Get consultant
     *
     * @return \AppBundle\Entity\Consultant
     */
    public function getConsultant()
    {
        return $this->consultant;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Facture
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set bcclient
     *
     * @param \AppBundle\Entity\Bcclient $bcclient
     *
     * @return Facture
     */
    public function setBcclient(\AppBundle\Entity\Bcclient $bcclient = null)
    {
        $this->bcclient = $bcclient;

        return $this;
    }

    /**
     * Get bcclient
     *
     * @return \AppBundle\Entity\Bcclient
     */
    public function getBcclient()
    {
        return $this->bcclient;
    }

    /**
     * Set totalHT
     *
     * @param float $totalHT
     *
     * @return Facture
     */
    public function setTotalHT($totalHT)
    {
        $this->totalHT = $totalHT;

        return $this;
    }

    /**
     * Get totalHT
     *
     * @return float
     */
    public function getTotalHT()
    {
        return $this->totalHT;
    }

    /**
     * Set totalTTC
     *
     * @param float $totalTTC
     *
     * @return Facture
     */
    public function setTotalTTC($totalTTC)
    {
        $this->totalTTC = $totalTTC;

        return $this;
    }

    /**
     * Get totalTTC
     *
     * @return float
     */
    public function getTotalTTC()
    {
        return $this->totalTTC;
    }


    /**
     * Set mission
     *
     * @param \AppBundle\Entity\Mission $mission
     *
     * @return Facture
     */
    public function setMission(\AppBundle\Entity\Mission $mission = null)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return \AppBundle\Entity\Mission
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Set year
     *
     * @param string $year
     *
     * @return Facture
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set taxe
     *
     * @param float $taxe
     *
     * @return Facture
     */
    public function setTaxe($taxe)
    {
        $this->taxe = $taxe;

        return $this;
    }

    /**
     * Get taxe
     *
     * @return float
     */
    public function getTaxe()
    {
        return $this->taxe;
    }

    public function int2str($a)
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
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return Facture
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Facture
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Facture
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set totalDH
     *
     * @param float $totalDH
     *
     * @return Facture
     */
    public function setTotalDH($totalDH)
    {
        $this->totalDH = $totalDH;

        return $this;
    }

    /**
     * Get totalDH
     *
     * @return float
     */
    public function getTotalDH()
    {
        return $this->totalDH;
    }

    /**
     * Set comptebancaire
     *
     * @param \AppBundle\Entity\Comptebancaire $comptebancaire
     *
     * @return Facture
     */
    public function setComptebancaire(\AppBundle\Entity\Comptebancaire $comptebancaire = null)
    {
        $this->comptebancaire = $comptebancaire;

        return $this;
    }

    /**
     * Get comptebancaire
     *
     * @return \AppBundle\Entity\Comptebancaire
     */
    public function getComptebancaire()
    {
        return $this->comptebancaire;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturehsups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime();

        $this->lignes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setEtat('non payé');
    }

    /**
     * Add ligne
     *
     * @param \AppBundle\Entity\LigneFacture $ligne
     *
     * @return Facture
     */
    public function addLigne(\AppBundle\Entity\LigneFacture $ligne)
    {
        $this->lignes[] = $ligne;

        return $this;
    }

    /**
     * Remove ligne
     *
     * @param \AppBundle\Entity\LigneFacture $ligne
     */
    public function removeLigne(\AppBundle\Entity\LigneFacture $ligne)
    {
        $this->lignes->removeElement($ligne);
    }

    /**
     * Get lignes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignes()
    {
        return $this->lignes;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Facture
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Facture
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }


    /**
     * Set projet
     *
     * @param \AppBundle\Entity\Projet $projet
     *
     * @return Facture
     */
    public function setProjet(\AppBundle\Entity\Projet $projet = null)
    {
        $this->projet = $projet;

        return $this;
    }

    /**
     * Get projet
     *
     * @return \AppBundle\Entity\Projet
     */
    public function getProjet()
    {
        return $this->projet;
    }

    public function __toString()
    {
        return '' . $this->getNumero();

    }


    /**
     * Add facturehsup
     *
     * @param \AppBundle\Entity\FactureHsup $facturehsup
     *
     * @return Facture
     */
    public function addFacturehsup(\AppBundle\Entity\FactureHsup $facturehsup)
    {
        $this->facturehsups[] = $facturehsup;

        return $this;
    }

    /**
     * Remove facturehsup
     *
     * @param \AppBundle\Entity\FactureHsup $facturehsup
     */
    public function removeFacturehsup(\AppBundle\Entity\FactureHsup $facturehsup)
    {
        $this->facturehsups->removeElement($facturehsup);
    }

    /**
     * Get facturehsups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturehsups()
    {
        return $this->facturehsups;
    }

    /**
     * Set nbjour
     *
     * @param float $nbjour
     *
     * @return Facture
     */
    public function setNbjour($nbjour)
    {
        $this->nbjour = $nbjour;

        return $this;
    }

    /**
     * Get nbjour
     *
     * @return float
     */
    public function getNbjour()
    {
        return $this->nbjour;
    }

    /**
     * Add production
     *
     * @param \AppBundle\Entity\Production $production
     *
     * @return Facture
     */
    public function addProduction(\AppBundle\Entity\Production $production)
    {
        $this->productions[] = $production;

        return $this;
    }

    /**
     * Remove production
     *
     * @param \AppBundle\Entity\Production $production
     */
    public function removeProduction(\AppBundle\Entity\Production $production)
    {
        $this->productions->removeElement($production);
    }

    /**
     * Get productions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductions()
    {
        return $this->productions;
    }

    /**
     * Add bcfournisseur
     *
     * @param \AppBundle\Entity\Bcfournisseur $bcfournisseur
     *
     * @return Facture
     */
    public function addBcfournisseur(\AppBundle\Entity\Bcfournisseur $bcfournisseur)
    {
        $this->bcfournisseurs[] = $bcfournisseur;

        return $this;
    }

    /**
     * Remove bcfournisseur
     *
     * @param \AppBundle\Entity\Bcfournisseur $bcfournisseur
     */
    public function removeBcfournisseur(\AppBundle\Entity\Bcfournisseur $bcfournisseur)
    {
        $this->bcfournisseurs->removeElement($bcfournisseur);
    }

    /**
     * Get bcfournisseurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBcfournisseurs()
    {
        return $this->bcfournisseurs;
    }

    /**
     * Add facturefournisseur
     *
     * @param \AppBundle\Entity\Facturefournisseur $facturefournisseur
     *
     * @return Facture
     */
    public function addFacturefournisseur(\AppBundle\Entity\Facturefournisseur $facturefournisseur)
    {
        $this->facturefournisseurs[] = $facturefournisseur;

        return $this;
    }

    /**
     * Remove facturefournisseur
     *
     * @param \AppBundle\Entity\Facturefournisseur $facturefournisseur
     */
    public function removeFacturefournisseur(\AppBundle\Entity\Facturefournisseur $facturefournisseur)
    {
        $this->facturefournisseurs->removeElement($facturefournisseur);
    }

    /**
     * Get facturefournisseurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturefournisseurs()
    {
        return $this->facturefournisseurs;
    }



    /**
     * Set addedby
     *
     * @param \AppBundle\Entity\User $addedby
     *
     * @return Facture
     */
    public function setAddedby(\AppBundle\Entity\User $addedby = null)
    {
        $this->addedby = $addedby;

        return $this;
    }

    /**
     * Get addedby
     *
     * @return \AppBundle\Entity\User
     */
    public function getAddedby()
    {
        return $this->addedby;
    }

    /**
     * Set editedby
     *
     * @param \AppBundle\Entity\User $editedby
     *
     * @return Facture
     */
    public function setEditedby(\AppBundle\Entity\User $editedby = null)
    {
        $this->editedby = $editedby;

        return $this;
    }

    /**
     * Get editedby
     *
     * @return \AppBundle\Entity\User
     */
    public function getEditedby()
    {
        return $this->editedby;
    }
}
