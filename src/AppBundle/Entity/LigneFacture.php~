<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneFacture
 *
 * @ORM\Table(name="ligne_facture")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LigneFactureRepository")
 */
class LigneFacture
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
     * @ORM\Column(name="numero", type="string", length=255, nullable=true)
     */
    private $numero;

    /**
     * @var float
     *
     * @ORM\Column(name="nbjour", type="float", nullable=true)
     */
    private $nbjour;

    /**
     * @var int
     *
     * @ORM\Column(name="mois", type="integer", nullable=true)
     */
    private $mois;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer", nullable=true)
     */
    private $year;

    /**
     * @var float
     *
     * @ORM\Column(name="totalHt", type="float", nullable=true)
     */
    private $totalHt;

    /**
     * @var float
     *
     * @ORM\Column(name="totalTTC", type="float", nullable=true)
     */
    private $totalTTC;

    /**
     * @ORM\ManyToOne(targetEntity="Facture", inversedBy="lignes",cascade={"persist"})
     * @ORM\JoinColumn(name="id_facture", referencedColumnName="id")
     */
    private $facture;
    /**
     * @ORM\ManyToOne(targetEntity="Projetconsultant", inversedBy="lignes")
     * @ORM\JoinColumn(name="id_projetconsultant", referencedColumnName="id")
     */
    private $projetconsultant;
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
     * Set numero
     *
     * @param string $numero
     *
     * @return LigneFacture
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
     * Set nbjour
     *
     * @param float $nbjour
     *
     * @return LigneFacture
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
     * Set mois
     *
     * @param integer $mois
     *
     * @return LigneFacture
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return int
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return LigneFacture
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set totalHt
     *
     * @param float $totalHt
     *
     * @return LigneFacture
     */
    public function setTotalHt($totalHt)
    {
        $this->totalHt = $totalHt;

        return $this;
    }

    /**
     * Get totalHt
     *
     * @return float
     */
    public function getTotalHt()
    {
        return $this->totalHt;
    }

    /**
     * Set totalTTC
     *
     * @param float $totalTTC
     *
     * @return LigneFacture
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
     * Set facture
     *
     * @param \AppBundle\Entity\Facture $facture
     *
     * @return LigneFacture
     */
    public function setFacture(\AppBundle\Entity\Facture $facture = null)
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * Get facture
     *
     * @return \AppBundle\Entity\Facture
     */
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * Set projetconsultant
     *
     * @param \AppBundle\Entity\Projetconsultant $projetconsultant
     *
     * @return LigneFacture
     */
    public function setProjetconsultant(\AppBundle\Entity\Projetconsultant $projetconsultant = null)
    {
        $this->projetconsultant = $projetconsultant;

        return $this;
    }

    /**
     * Get projetconsultant
     *
     * @return \AppBundle\Entity\Projetconsultant
     */
    public function getProjetconsultant()
    {
        return $this->projetconsultant;
    }
}
