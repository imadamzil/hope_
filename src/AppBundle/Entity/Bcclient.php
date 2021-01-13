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
     * @ORM\Column(name="code", type="string", length=255,nullable=true)
     */
    private $code;
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255,nullable=true)
     */
    private $type;
    /**
     * @var string
     *
     * @ORM\Column(name="n_contrat", type="string", length=255,nullable=true)
     */
    private $ncontrat;
    /**
     * @var string
     *
     * @ORM\Column(name="application", type="string", length=255,nullable=true)
     */
    private $application;
    /**
     * @var string
     *
     * @ORM\Column(name="avenant", type="string", length=255,nullable=true)
     */
    private $avenant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date",nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="nbJrs", type="integer", nullable=true)
     */
    private $nbJrs;

    /**
     * @var int
     *
     * @ORM\Column(name="nbJrsR", type="integer", nullable=true)
     */
    private $nbJrsR;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Mission", mappedBy="bcclient",cascade={"persist", "remove"})
     */
    private $missions;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Projet", mappedBy="bcclient",cascade={"persist", "remove"})
     */
    private $projets;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Facture", mappedBy="bcclient",cascade={"persist", "remove"})
     */
    private $factures;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="bcclients")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="Consultant", inversedBy="bcclients")
     * @ORM\JoinColumn(name="id_consultant", referencedColumnName="id")
     */
    private $consultant;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ceation", type="datetime", nullable=true)
     */
    private $createdAt;
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
        $this->mission =  new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Bcclient
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
     * Set consultant
     *
     * @param \AppBundle\Entity\Consultant $consultant
     *
     * @return Bcclient
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

    public function __toString()
    {
        return $this->getClient()->getNom() . '~' . $this->getCode();
    }



    /**
     * Add facture
     *
     * @param \AppBundle\Entity\Facture $facture
     *
     * @return Bcclient
     */
    public function addFacture(\AppBundle\Entity\Facture $facture)
    {
        $this->factures[] = $facture;

        return $this;
    }

    /**
     * Remove facture
     *
     * @param \AppBundle\Entity\Facture $facture
     */
    public function removeFacture(\AppBundle\Entity\Facture $facture)
    {
        $this->factures->removeElement($facture);
    }

    /**
     * Get factures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFactures()
    {
        return $this->factures;
    }

    /**
     * Get missions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMissions()
    {
        return $this->missions;
    }

    /**
     * Add projet
     *
     * @param \AppBundle\Entity\Projet $projet
     *
     * @return Bcclient
     */
    public function addProjet(\AppBundle\Entity\Projet $projet)
    {
        $this->projets[] = $projet;

        return $this;
    }

    /**
     * Remove projet
     *
     * @param \AppBundle\Entity\Projet $projet
     */
    public function removeProjet(\AppBundle\Entity\Projet $projet)
    {
        $this->projets->removeElement($projet);
    }

    /**
     * Get projets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjets()
    {
        return $this->projets;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Bcclient
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Bcclient
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Bcclient
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set ncontrat
     *
     * @param string $ncontrat
     *
     * @return Bcclient
     */
    public function setNcontrat($ncontrat)
    {
        $this->ncontrat = $ncontrat;

        return $this;
    }

    /**
     * Get ncontrat
     *
     * @return string
     */
    public function getNcontrat()
    {
        return $this->ncontrat;
    }

    /**
     * Set application
     *
     * @param string $application
     *
     * @return Bcclient
     */
    public function setApplication($application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return string
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set avenant
     *
     * @param string $avenant
     *
     * @return Bcclient
     */
    public function setAvenant($avenant)
    {
        $this->avenant = $avenant;

        return $this;
    }

    /**
     * Get avenant
     *
     * @return string
     */
    public function getAvenant()
    {
        return $this->avenant;
    }
}
