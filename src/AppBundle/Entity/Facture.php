<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureRepository")
 */
class Facture
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
     * @ORM\Column(name="etat", type="string", length=255, nullable=true)
     */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="mois", type="string", length=255, nullable=true)
     */
    private $mois;

    /**
     * @ORM\ManyToOne(targetEntity="Consultant", inversedBy="facture")
     * @ORM\JoinColumn(name="id_consultant", referencedColumnName="id")
     */
    private $consultant;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="facture")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;
    /**
     * @ORM\ManyToOne(targetEntity="Bcclient", inversedBy="facture")
     * @ORM\JoinColumn(name="id_bcclient", referencedColumnName="id")
     */
    private $bcclient;
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
     * Set etat
     *
     * @param string $etat
     *
     * @return Facture
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
     * Set mois
     *
     * @param string $mois
     *
     * @return Facture
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return string
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Facture
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
     * Set consultant
     *
     * @param \AppBundle\Entity\Consultant $consultant
     *
     * @return Facture
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
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Facture
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
     * Set bcclient
     *
     * @param \AppBundle\Entity\Bcclient $bcclient
     *
     * @return Facture
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
}
