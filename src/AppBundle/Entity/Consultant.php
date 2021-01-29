<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * Consultant
 *
 * @ORM\Table(name="consultant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConsultantRepository")
 * @Vich\Uploadable
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
     * @ORM\Column(name="tjm", type="string", length=255, nullable=true)
     */
    private $tjm;
    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=255, nullable=true)
     */
    private $cin;

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
     * @ORM\Column(name="adresse", type="text",nullable=true)
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Mission", mappedBy="consultant",cascade={"persist", "remove"})
     */
    private $missions;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Virement", mappedBy="consultant",cascade={"persist", "remove"})
     */
    private $virements;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Facture", mappedBy="consultant",cascade={"persist", "remove"})
     */
    private $factures;

 /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Bcclient", mappedBy="consultant",cascade={"persist", "remove"})
     */
    private $bcclients;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Bcfournisseur", mappedBy="consultant",cascade={"persist", "remove"})
     */
    private $bcfournisseurs;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Facturefournisseur", mappedBy="consultant",cascade={"persist", "remove"})
     */
    private $facturefournisseurs;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Production", mappedBy="consultant",cascade={"persist", "remove"})
     */
    private $productions;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Projetconsultant", mappedBy="consultant",cascade={"persist", "remove"})
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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="document_path", fileNameProperty="cvName")
     *
     * @var File
     */
    private $cvFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $cvName;


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $cv
     */
    public function setcvFile(?File $cv = null): void
    {
        $this->cvFile = $cv;

        if (null !== $cv) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getcvFile(): ?File
    {
        return $this->cvFile;
    }

    public function setcvName(?string $cvName): void
    {
        $this->cvName = $cvName;
    }

    public function getcvName(): ?string
    {
        return $this->cvName;
    }
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
        $this->createdAt = new \DateTime();
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
     * Get virements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVirements()
    {
        return $this->virements;
    }

    /**
     * Get bcclients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBcclients()
    {
        return $this->bcclients;
    }

    /**
     * Add projetconsultant
     *
     * @param \AppBundle\Entity\Projetconsultant $projetconsultant
     *
     * @return Consultant
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Consultant
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
     * @return Consultant
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
     * Set tjm
     *
     * @param string $tjm
     *
     * @return Consultant
     */
    public function setTjm($tjm)
    {
        $this->tjm = $tjm;

        return $this;
    }

    /**
     * Get tjm
     *
     * @return string
     */
    public function getTjm()
    {
        return $this->tjm;
    }

    /**
     * Set cin
     *
     * @param string $cin
     *
     * @return Consultant
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return string
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * Add bcfournisseur
     *
     * @param \AppBundle\Entity\Bcfournisseur $bcfournisseur
     *
     * @return Consultant
     */
    public function addBcfournisseur(\AppBundle\Entity\Bcfournisseur $bcfournisseur)
    {
        $this->bcfournisseurs[] = $bcfournisseur;

        return $this;
    }

    /**
     * Remove bcfournisseur
     *
     * @param \AppBundle\Entity\Bcfournisseur $bcfournisseur
     */
    public function removeBcfournisseur(\AppBundle\Entity\Bcfournisseur $bcfournisseur)
    {
        $this->bcfournisseurs->removeElement($bcfournisseur);
    }

    /**
     * Get bcfournisseurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBcfournisseurs()
    {
        return $this->bcfournisseurs;
    }

    /**
     * Add facturesfournisseur
     *
     * @param \AppBundle\Entity\Bcfournisseur $facturesfournisseur
     *
     * @return Consultant
     */
    public function addFacturesfournisseur(\AppBundle\Entity\Bcfournisseur $facturesfournisseur)
    {
        $this->facturesfournisseurs[] = $facturesfournisseur;

        return $this;
    }

    /**
     * Remove facturesfournisseur
     *
     * @param \AppBundle\Entity\Bcfournisseur $facturesfournisseur
     */
    public function removeFacturesfournisseur(\AppBundle\Entity\Bcfournisseur $facturesfournisseur)
    {
        $this->facturesfournisseurs->removeElement($facturesfournisseur);
    }

    /**
     * Get facturesfournisseurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturesfournisseurs()
    {
        return $this->facturesfournisseurs;
    }

    /**
     * Add facturefournisseur
     *
     * @param \AppBundle\Entity\Facturefournisseur $facturefournisseur
     *
     * @return Consultant
     */
    public function addFacturefournisseur(\AppBundle\Entity\Facturefournisseur $facturefournisseur)
    {
        $this->facturefournisseurs[] = $facturefournisseur;

        return $this;
    }

    /**
     * Remove facturefournisseur
     *
     * @param \AppBundle\Entity\Facturefournisseur $facturefournisseur
     */
    public function removeFacturefournisseur(\AppBundle\Entity\Facturefournisseur $facturefournisseur)
    {
        $this->facturefournisseurs->removeElement($facturefournisseur);
    }

    /**
     * Get facturefournisseurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturefournisseurs()
    {
        return $this->facturefournisseurs;
    }

    /**
     * Add production
     *
     * @param \AppBundle\Entity\Production $production
     *
     * @return Consultant
     */
    public function addProduction(\AppBundle\Entity\Production $production)
    {
        $this->productions[] = $production;

        return $this;
    }

    /**
     * Remove production
     *
     * @param \AppBundle\Entity\Production $production
     */
    public function removeProduction(\AppBundle\Entity\Production $production)
    {
        $this->productions->removeElement($production);
    }

    /**
     * Get productions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductions()
    {
        return $this->productions;
    }
}
