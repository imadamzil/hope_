<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Echeance
 *
 * @ORM\Table(name="echeance")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EcheanceRepository")
 */
class Echeance
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="min", type="integer", nullable=true)
     */
    private $min;
    /**
     * @var int
     *
     * @ORM\Column(name="cond", type="integer", nullable=true)
     */
    private $condition;

    /**
     * @var int
     *
     * @ORM\Column(name="max", type="integer", nullable=true)
     */
    private $max;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Consultant", mappedBy="echeance",cascade={"persist", "remove"})
     */
    private $consultants;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Echeance
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set min
     *
     * @param integer $min
     *
     * @return Echeance
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min
     *
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set max
     *
     * @param integer $max
     *
     * @return Echeance
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max
     *
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->consultants = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add consultant
     *
     * @param \AppBundle\Entity\Consultant $consultant
     *
     * @return Echeance
     */
    public function addConsultant(\AppBundle\Entity\Consultant $consultant)
    {
        $this->consultants[] = $consultant;

        return $this;
    }

    /**
     * Remove consultant
     *
     * @param \AppBundle\Entity\Consultant $consultant
     */
    public function removeConsultant(\AppBundle\Entity\Consultant $consultant)
    {
        $this->consultants->removeElement($consultant);
    }

    /**
     * Get consultants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConsultants()
    {
        return $this->consultants;
    }

    public function __toString()
    {

        return $this->getNom();
    }

    /**
     * Set condition
     *
     * @param integer $condition
     *
     * @return Echeance
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return integer
     */
    public function getCondition()
    {
        return $this->condition;
    }
}
