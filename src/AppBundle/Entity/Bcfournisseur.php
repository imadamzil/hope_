<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bcfournisseur
 *
 * @ORM\Table(name="bcfournisseur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BcfournisseurRepository")
 */
class Bcfournisseur
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
     * @var float
     *
     * @ORM\Column(name="achat", type="float", nullable=true)
     */
    private $achat;

    /**
     * @var string
     *
     * @ORM\Column(name="factureFournisseur", type="string", length=255, nullable=true)
     */
    private $factureFournisseur;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=true)
     */
    private $etat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;


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
     * Set achat
     *
     * @param float $achat
     *
     * @return Bcfournisseur
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
     * Set factureFournisseur
     *
     * @param string $factureFournisseur
     *
     * @return Bcfournisseur
     */
    public function setFactureFournisseur($factureFournisseur)
    {
        $this->factureFournisseur = $factureFournisseur;

        return $this;
    }

    /**
     * Get factureFournisseur
     *
     * @return string
     */
    public function getFactureFournisseur()
    {
        return $this->factureFournisseur;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return Bcfournisseur
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Bcfournisseur
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
}
