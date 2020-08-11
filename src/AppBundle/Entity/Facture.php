<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureRepository")
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
     * @var integer
     *
     * @ORM\Column(name="nb_jours", type="integer", nullable=true)
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
     * @ORM\Column(name="année", type="integer", nullable=true)
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
     * @ORM\ManyToOne(targetEntity="Consultant", inversedBy="facture")
     * @ORM\JoinColumn(name="id_consultant", referencedColumnName="id")
     */
    private $consultant;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="facture")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;
    /**
     * @ORM\ManyToOne(targetEntity="Mission", inversedBy="facture")
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
     * @ORM\ManyToOne(targetEntity="Bcclient", inversedBy="facture")
     * @ORM\JoinColumn(name="id_bcclient", referencedColumnName="id")
     */
    private $bcclient;
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
     * Set nbjour
     *
     * @param integer $nbjour
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
     * @return integer
     */
    public function getNbjour()
    {
        return $this->nbjour;
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
}
