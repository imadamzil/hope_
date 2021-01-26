<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Heuresup
 *
 * @ORM\Table(name="heuresup")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HeuresupRepository")
 */
class Heuresup
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
     * @ORM\Column(name="item", type="string", length=255, nullable=true)
     */
    private $item;

    /**
     * @var float
     *
     * @ORM\Column(name="pourcentage", type="float", nullable=true)
     */
    private $pourcentage;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FactureHsup", mappedBy="heuresup",cascade={"persist", "remove"})
     */
    private $heures;

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
     * Set item
     *
     * @param string $item
     *
     * @return Heuresup
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set pourcentage
     *
     * @param float $pourcentage
     *
     * @return Heuresup
     */
    public function setPourcentage($pourcentage)
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    /**
     * Get pourcentage
     *
     * @return float
     */
    public function getPourcentage()
    {
        return $this->pourcentage;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Heuresup
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->heures = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add heure
     *
     * @param \AppBundle\Entity\FactureHsup $heure
     *
     * @return Heuresup
     */
    public function addHeure(\AppBundle\Entity\FactureHsup $heure)
    {
        $this->heures[] = $heure;

        return $this;
    }

    /**
     * Remove heure
     *
     * @param \AppBundle\Entity\FactureHsup $heure
     */
    public function removeHeure(\AppBundle\Entity\FactureHsup $heure)
    {
        $this->heures->removeElement($heure);
    }

    /**
     * Get heures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHeures()
    {
        return $this->heures;
    }
    public function __toString()
    {
        return $this->getItem().'';
    }
}
