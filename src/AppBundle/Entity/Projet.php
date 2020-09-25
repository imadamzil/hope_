<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projet
 *
 * @ORM\Table(name="projet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjetRepository")
 */
class Projet
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
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255, nullable=true)
     */
    private $statut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime", nullable=true)
     */
    private $dateDebut;

    /**
     * @var date
     *
     * @ORM\Column(name="dateFin", type="datetime", nullable=true)
     */
    private $dateFin;
    /**
     * @ORM\ManyToOne(targetEntity="Bcclient", inversedBy="projets")
     * @ORM\JoinColumn(name="id_bcclient", referencedColumnName="id")
     */
    private $bcclient;
    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="projets")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Projetconsultant", mappedBy="projet",cascade={"persist", "remove"})
     */
    private $projetconsultants;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Projet
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
     * Set bc
     *
     * @param string $bc
     *
     * @return Projet
     */
    public function setBc($bc)
    {
        $this->bc = $bc;

        return $this;
    }

    /**
     * Get bc
     *
     * @return string
     */
    public function getBc()
    {
        return $this->bc;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projetconsultants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->statut= 'En cours';
    }

    /**
     * Set bcclient
     *
     * @param \AppBundle\Entity\Bcclient $bcclient
     *
     * @return Projet
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
     * Add projetconsultant
     *
     * @param \AppBundle\Entity\Projetconsultant $projetconsultant
     *
     * @return Projet
     */
    public function addProjetconsultant(\AppBundle\Entity\Projetconsultant $projetconsultant)
    {
        $this->projetconsultants[] = $projetconsultant;

        return $this;
    }

    /**
     * Remove projetconsultant
     *
     * @param \AppBundle\Entity\Projetconsultant $projetconsultant
     */
    public function removeProjetconsultant(\AppBundle\Entity\Projetconsultant $projetconsultant)
    {
        $this->projetconsultants->removeElement($projetconsultant);
    }

    /**
     * Get projetconsultants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjetconsultants()
    {
        return $this->projetconsultants;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Projet
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Projet
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Projet
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Projet
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
     * @return Projet
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
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Projet
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
}
