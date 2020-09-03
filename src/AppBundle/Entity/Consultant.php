<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Consultant
 *
 * @ORM\Table(name="consultant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConsultantRepository")
 */
class Consultant
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
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="salaire", type="string", length=255, nullable=true)
     */
    private $salaire;

    /**
     * @var string
     *
     * @ORM\Column(name="rib", type="string", length=255, nullable=true)
     */
    private $rib;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Mission", mappedBy="consultant",cascade={"persist", "remove"})
     */
    private $mission;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Virement", mappedBy="consultant",cascade={"persist", "remove"})
     */
    private $virement;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Facture", mappedBy="consultant",cascade={"persist", "remove"})
     */
    private $factures;

 /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Bcclient", mappedBy="consultant",cascade={"persist", "remove"})
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Consultant
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
     * Set type
     *
     * @param string $type
     *
     * @return Consultant
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
     * Set salaire
     *
     * @param string $salaire
     *
     * @return Consultant
     */
    public function setSalaire($salaire)
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * Get salaire
     *
     * @return string
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * Set rib
     *
     * @param string $rib
     *
     * @return Consultant
     */
    public function setRib($rib)
    {
        $this->rib = $rib;

        return $this;
    }

    /**
     * Get rib
     *
     * @return string
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Consultant
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Consultant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Consultant
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
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
     * @return Consultant
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

    public function __toString()
    {
        return $this->getNom();
    }

    /**
     * Add virment
     *
     * @param \AppBundle\Entity\Virement $virment
     *
     * @return Consultant
     */
    public function addVirment(\AppBundle\Entity\Virement $virment)
    {
        $this->virment[] = $virment;

        return $this;
    }

    /**
     * Remove virment
     *
     * @param \AppBundle\Entity\Virement $virment
     */
    public function removeVirment(\AppBundle\Entity\Virement $virment)
    {
        $this->virment->removeElement($virment);
    }

    /**
     * Get virment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVirment()
    {
        return $this->virment;
    }

    /**
     * Add virement
     *
     * @param \AppBundle\Entity\Virement $virement
     *
     * @return Consultant
     */
    public function addVirement(\AppBundle\Entity\Virement $virement)
    {
        $this->virement[] = $virement;

        return $this;
    }

    /**
     * Remove virement
     *
     * @param \AppBundle\Entity\Virement $virement
     */
    public function removeVirement(\AppBundle\Entity\Virement $virement)
    {
        $this->virement->removeElement($virement);
    }

    /**
     * Get virement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVirement()
    {
        return $this->virement;
    }

    /**
     * Add bcclient
     *
     * @param \AppBundle\Entity\Bcclient $bcclient
     *
     * @return Consultant
     */
    public function addBcclient(\AppBundle\Entity\Bcclient $bcclient)
    {
        $this->bcclient[] = $bcclient;

        return $this;
    }

    /**
     * Remove bcclient
     *
     * @param \AppBundle\Entity\Bcclient $bcclient
     */
    public function removeBcclient(\AppBundle\Entity\Bcclient $bcclient)
    {
        $this->bcclient->removeElement($bcclient);
    }

    /**
     * Get bcclient
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBcclient()
    {
        return $this->bcclient;
    }

    /**
     * Add facture
     *
     * @param \AppBundle\Entity\Facture $facture
     *
     * @return Consultant
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
}
