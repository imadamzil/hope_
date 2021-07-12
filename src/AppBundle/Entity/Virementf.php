<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Virementf
 *
 * @ORM\Table(name="virementf")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VirementfRepository")
 */
class Virementf
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;
    /**
     * @var boolean
     *
     * @ORM\Column(name="auto", type="boolean", nullable=true)
     */
    private $auto;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Virement", mappedBy="virementf",cascade={"persist", "remove"})
     */
    private $virements;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Detailvirement", mappedBy="virementf",cascade={"persist", "remove"})
     */
    private $detailfournisseurs;
    /**
     * @ORM\ManyToOne(targetEntity="Comptebancaire", inversedBy="virements")
     * @ORM\JoinColumn(name="id_compte", referencedColumnName="id")
     */
    private $comptebancaire;

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
     * @return Virementf
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
     * Constructor
     */
    public function __construct()
    {
        $this->virements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->auto = false;
    }

    /**
     * Add virement
     *
     * @param \AppBundle\Entity\Virement $virement
     *
     * @return Virementf
     */
    public function addVirement(\AppBundle\Entity\Virement $virement)
    {
        $this->virements[] = $virement;

        return $this;
    }

    /**
     * Remove virement
     *
     * @param \AppBundle\Entity\Virement $virement
     */
    public function removeVirement(\AppBundle\Entity\Virement $virement)
    {
        $this->virements->removeElement($virement);
    }

    /**
     * Get virements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVirements()
    {
        return $this->virements;
    }

    /**
     * Add detailfournisseur
     *
     * @param \AppBundle\Entity\Detailvirement $detailfournisseur
     *
     * @return Virementf
     */
    public function addDetailfournisseur(\AppBundle\Entity\Detailvirement $detailfournisseur)
    {
        $this->detailfournisseurs[] = $detailfournisseur;

        return $this;
    }

    /**
     * Remove detailfournisseur
     *
     * @param \AppBundle\Entity\Detailvirement $detailfournisseur
     */
    public function removeDetailfournisseur(\AppBundle\Entity\Detailvirement $detailfournisseur)
    {
        $this->detailfournisseurs->removeElement($detailfournisseur);
    }

    /**
     * Get detailfournisseurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetailfournisseurs()
    {
        return $this->detailfournisseurs;
    }

    public function getFounisseursList()
    {
        $liste = $this->detailfournisseurs;

        foreach ($liste as $item) {

            $arr_fournisseurs [] = $item->getFournisseur();
        }

        return $arr_fournisseurs;

    }

    public function getTotalVirements()
    {
        $total = null;
        $liste = $this->detailfournisseurs;
        foreach ($liste as $item) {

            $total += $item->getTotal();
        }

        return $total;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Virementf
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
     * Set comptebancaire
     *
     * @param \AppBundle\Entity\Comptebancaire $comptebancaire
     *
     * @return Virementf
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
     * Set auto
     *
     * @param boolean $auto
     *
     * @return Virementf
     */
    public function setAuto($auto)
    {
        $this->auto = $auto;

        return $this;
    }

    /**
     * Get auto
     *
     * @return boolean
     */
    public function getAuto()
    {
        return $this->auto;
    }
}
