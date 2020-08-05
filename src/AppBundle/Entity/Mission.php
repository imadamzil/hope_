<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Mission
 *
 * @ORM\Table(name="mission")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MissionRepository")
 */
class Mission
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
     * @ORM\Column(name="prixVente", type="float", nullable=true)
     */
    private $prixVente;

    /**
     * @var string
     *
     * @ORM\Column(name="prixAchat", type="string", length=255, nullable=true)
     */
    private $prixAchat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date", nullable=true)
     */
    private $dateDebut;

    /**
     * @var date
     *
     * @ORM\Column(name="dateFin", type="date", length=255)
     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="contratFournisseur", type="string", length=255, nullable=true)
     */
    private $contratFournisseur;

    /**
     * @var string
     *
     * @ORM\Column(name="contratClient", type="string", length=255, nullable=true)
     */
    private $contratClient;


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
     * Set prixVente
     *
     * @param float $prixVente
     *
     * @return Mission
     */
    public function setPrixVente($prixVente)
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    /**
     * Get prixVente
     *
     * @return float
     */
    public function getPrixVente()
    {
        return $this->prixVente;
    }

    /**
     * Set prixAchat
     *
     * @param string $prixAchat
     *
     * @return Mission
     */
    public function setPrixAchat($prixAchat)
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    /**
     * Get prixAchat
     *
     * @return string
     */
    public function getPrixAchat()
    {
        return $this->prixAchat;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Mission
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
     * @param string $dateFin
     *
     * @return Mission
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return string
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set contratFournisseur
     *
     * @param string $contratFournisseur
     *
     * @return Mission
     */
    public function setContratFournisseur($contratFournisseur)
    {
        $this->contratFournisseur = $contratFournisseur;

        return $this;
    }

    /**
     * Get contratFournisseur
     *
     * @return string
     */
    public function getContratFournisseur()
    {
        return $this->contratFournisseur;
    }

    /**
     * Set contratClient
     *
     * @param string $contratClient
     *
     * @return Mission
     */
    public function setContratClient($contratClient)
    {
        $this->contratClient = $contratClient;

        return $this;
    }

    /**
     * Get contratClient
     *
     * @return string
     */
    public function getContratClient()
    {
        return $this->contratClient;
    }
}

