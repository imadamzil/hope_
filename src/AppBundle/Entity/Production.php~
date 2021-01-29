<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Production
 *
 * @ORM\Table(name="production")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductionRepository")
 */
class Production
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
     * @ORM\ManyToOne(targetEntity="Consultant", inversedBy="productions")
     * @ORM\JoinColumn(name="id_consultant", referencedColumnName="id")
     */
    private $consultant;
    /**
     * @ORM\ManyToOne(targetEntity="Projet", inversedBy="productions")
     * @ORM\JoinColumn(name="id_projet", referencedColumnName="id")
     */
    private $projet;
    /**
     * @ORM\ManyToOne(targetEntity="Mission", inversedBy="productions")
     * @ORM\JoinColumn(name="id_mission", referencedColumnName="id")
     */
    private $mission;
    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="productions")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;
    /**
     * @ORM\ManyToOne(targetEntity="Fournisseur", inversedBy="productions")
     * @ORM\JoinColumn(name="id_fournisseur", referencedColumnName="id")
     */
    private $fournisseur;

    /**
     * @var float
     *
     * @ORM\Column(name="tjmVente", type="float", nullable=true)
     */
    private $tjmVente;
    /**
     * @var float
     *
     * @ORM\Column(name="tjmAchat", type="float", nullable=true)
     */
    private $tjmAchat;

    /**
     * @var float
     *
     * @ORM\Column(name="mois", type="float", nullable=true)
     */
    private $mois;

    /**
     * @var float
     *
     * @ORM\Column(name="year", type="float", nullable=true)
     */
    private $year;

    /**
     * @var float
     *
     * @ORM\Column(name="nbjour", type="float", nullable=true)
     */
    private $nbjour;

    /**
     * @var float
     *
     * @ORM\Column(name="venteHT", type="float", nullable=true)
     */
    private $venteHT;

    /**
     * @var float
     *
     * @ORM\Column(name="venteTTC", type="float", nullable=true)
     */
    private $venteTTC;

    /**
     * @var float
     *
     * @ORM\Column(name="achatHT", type="float", nullable=true)
     */
    private $achatHT;

    /**
     * @var float
     *
     * @ORM\Column(name="achatTTC", type="float", nullable=true)
     */
    private $achatTTC;


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
     * Set tjmAchat
     *
     * @param float $tjmAchat
     *
     * @return Production
     */
    public function setTjmAchat($tjmAchat)
    {
        $this->tjmAchat = $tjmAchat;

        return $this;
    }

    /**
     * Get tjmAchat
     *
     * @return float
     */
    public function getTjmAchat()
    {
        return $this->tjmAchat;
    }

    /**
     * Set tjmVente
     *
     * @param float $tjmVente
     *
     * @return Production
     */
    public function setTjmVente($tjmVente)
    {
        $this->tjmVente = $tjmVente;

        return $this;
    }

    /**
     * Get tjmVente
     *
     * @return float
     */
    public function getTjmVente()
    {
        return $this->tjmVente;
    }

    /**
     * Set mois
     *
     * @param float $mois
     *
     * @return Production
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return float
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set year
     *
     * @param float $year
     *
     * @return Production
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return float
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set nbjour
     *
     * @param float $nbjour
     *
     * @return Production
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
     * Set venteHT
     *
     * @param float $venteHT
     *
     * @return Production
     */
    public function setVenteHT($venteHT)
    {
        $this->venteHT = $venteHT;

        return $this;
    }

    /**
     * Get venteHT
     *
     * @return float
     */
    public function getVenteHT()
    {
        return $this->venteHT;
    }

    /**
     * Set venteTTC
     *
     * @param float $venteTTC
     *
     * @return Production
     */
    public function setVenteTTC($venteTTC)
    {
        $this->venteTTC = $venteTTC;

        return $this;
    }

    /**
     * Get venteTTC
     *
     * @return float
     */
    public function getVenteTTC()
    {
        return $this->venteTTC;
    }

    /**
     * Set achatHT
     *
     * @param float $achatHT
     *
     * @return Production
     */
    public function setAchatHT($achatHT)
    {
        $this->achatHT = $achatHT;

        return $this;
    }

    /**
     * Get achatHT
     *
     * @return float
     */
    public function getAchatHT()
    {
        return $this->achatHT;
    }

    /**
     * Set achatTTC
     *
     * @param float $achatTTC
     *
     * @return Production
     */
    public function setAchatTTC($achatTTC)
    {
        $this->achatTTC = $achatTTC;

        return $this;
    }

    /**
     * Get achatTTC
     *
     * @return float
     */
    public function getAchatTTC()
    {
        return $this->achatTTC;
    }

    /**
     * Set consultant
     *
     * @param \AppBundle\Entity\Consultant $consultant
     *
     * @return Production
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
     * Set projet
     *
     * @param \AppBundle\Entity\Projet $projet
     *
     * @return Production
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

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Production
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
     * Set fournisseur
     *
     * @param \AppBundle\Entity\Fournisseur $fournisseur
     *
     * @return Production
     */
    public function setFournisseur(\AppBundle\Entity\Fournisseur $fournisseur = null)
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * Get fournisseur
     *
     * @return \AppBundle\Entity\Fournisseur
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }

    /**
     * Set mission
     *
     * @param \AppBundle\Entity\Mission $mission
     *
     * @return Production
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
}
