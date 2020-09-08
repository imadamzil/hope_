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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Virement", mappedBy="virementf",cascade={"persist", "remove"})
     */
    private $virements;
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
}
