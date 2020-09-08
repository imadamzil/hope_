<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Virement
 *
 * @ORM\Table(name="virement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VirementRepository")
 */
class Virement
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=true)
     */
    private $etat;
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;
    /**
     * @var string
     *
     * @ORM\Column(name="achatTTC", type="float",  nullable=true)
     */
    private $achat;

    /**
     * @ORM\ManyToOne(targetEntity="Bcfournisseur", inversedBy="virements")
     * @ORM\JoinColumn(name="id_bcfournisseur", referencedColumnName="id")
     */
    private $bcfournisseur;
    /**
     * @ORM\ManyToOne(targetEntity="Virementf", inversedBy="virements")
     * @ORM\JoinColumn(name="id_virementf", referencedColumnName="id")
     */
    private $virementf;
    /**
     * @ORM\ManyToOne(targetEntity="Facturefournisseur", inversedBy="virements")
     * @ORM\JoinColumn(name="id_facturefournisseur", referencedColumnName="id")
     */
    private $facturefournisseur;
    /**
     * @ORM\ManyToOne(targetEntity="Consultant", inversedBy="virements")
     * @ORM\JoinColumn(name="id_consultant", referencedColumnName="id")
     */
    private $consultant;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Virement
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
     * Set etat
     *
     * @param string $etat
     *
     * @return Virement
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
     * Set code
     *
     * @param string $code
     *
     * @return Virement
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set bcfournissuer
     *
     * @param \AppBundle\Entity\Bcfournisseur $bcfournissuer
     *
     * @return Virement
     */
    public function setBcfournissuer(\AppBundle\Entity\Bcfournisseur $bcfournissuer = null)
    {
        $this->bcfournissuer = $bcfournissuer;

        return $this;
    }

    /**
     * Get bcfournissuer
     *
     * @return \AppBundle\Entity\Bcfournisseur
     */
    public function getBcfournissuer()
    {
        return $this->bcfournissuer;
    }

    /**
     * Set achat
     *
     * @param float $achat
     *
     * @return Virement
     */
    public function setAchat($achat)
    {
        $this->achat = $achat;

        return $this;
    }

    /**
     * Get achat
     *
     * @return float
     */
    public function getAchat()
    {
        return $this->achat;
    }

    /**
     * Set consultant
     *
     * @param \AppBundle\Entity\Consultant $consultant
     *
     * @return Virement
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
     * Set bcfournisseur
     *
     * @param \AppBundle\Entity\Bcfournisseur $bcfournisseur
     *
     * @return Virement
     */
    public function setBcfournisseur(\AppBundle\Entity\Bcfournisseur $bcfournisseur = null)
    {
        $this->bcfournisseur = $bcfournisseur;

        return $this;
    }

    /**
     * Get bcfournisseur
     *
     * @return \AppBundle\Entity\Bcfournisseur
     */
    public function getBcfournisseur()
    {
        return $this->bcfournisseur;
    }

    /**
     * Set facturefournisseur
     *
     * @param \AppBundle\Entity\Facturefournisseur $facturefournisseur
     *
     * @return Virement
     */
    public function setFacturefournisseur(\AppBundle\Entity\Facturefournisseur $facturefournisseur = null)
    {
        $this->facturefournisseur = $facturefournisseur;

        return $this;
    }

    /**
     * Get facturefournisseur
     *
     * @return \AppBundle\Entity\Facturefournisseur
     */
    public function getFacturefournisseur()
    {
        return $this->facturefournisseur;
    }

    /**
     * Set virementf
     *
     * @param \AppBundle\Entity\Virementf $virementf
     *
     * @return Virement
     */
    public function setVirementf(\AppBundle\Entity\Virementf $virementf = null)
    {
        $this->virementf = $virementf;

        return $this;
    }

    /**
     * Get virementf
     *
     * @return \AppBundle\Entity\Virementf
     */
    public function getVirementf()
    {
        return $this->virementf;
    }
}
