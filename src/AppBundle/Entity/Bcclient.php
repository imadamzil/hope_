<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bcclient
 *
 * @ORM\Table(name="bcclient")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BcclientRepository")
 */
class Bcclient
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="nbJrs", type="string", length=255)
     */
    private $nbJrs;

    /**
     * @var int
     *
     * @ORM\Column(name="nbJrsR", type="integer", nullable=true)
     */
    private $nbJrsR;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Mission", mappedBy="bclient",cascade={"persist", "remove"})
     */
    private $mission;


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
     * Set code
     *
     * @param string $code
     *
     * @return Bcclient
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Bcclient
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
     * Set nbJrs
     *
     * @param string $nbJrs
     *
     * @return Bcclient
     */
    public function setNbJrs($nbJrs)
    {
        $this->nbJrs = $nbJrs;

        return $this;
    }

    /**
     * Get nbJrs
     *
     * @return string
     */
    public function getNbJrs()
    {
        return $this->nbJrs;
    }

    /**
     * Set nbJrsR
     *
     * @param integer $nbJrsR
     *
     * @return Bcclient
     */
    public function setNbJrsR($nbJrsR)
    {
        $this->nbJrsR = $nbJrsR;

        return $this;
    }

    /**
     * Get nbJrsR
     *
     * @return int
     */
    public function getNbJrsR()
    {
        return $this->nbJrsR;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mission = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add mission
     *
     * @param \AppBundle\Entity\Mission $mission
     *
     * @return Bcclient
     */
    public function addMission(\AppBundle\Entity\Mission $mission)
    {
        $this->mission[] = $mission;

        return $this;
    }

    /**
     * Remove mission
     *
     * @param \AppBundle\Entity\Mission $mission
     */
    public function removeMission(\AppBundle\Entity\Mission $mission)
    {
        $this->mission->removeElement($mission);
    }

    /**
     * Get mission
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMission()
    {
        return $this->mission;
    }
}
